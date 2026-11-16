<?php

namespace App\Http\Controllers\area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subjectgroup;
use App\Schoolgroup;
use App\addschool;
use App\addstudent;
use App\addschools_areas;
use App\addstudents_areas;
use App\Activities;
use App\activities_areas;
use App\Branch;
use App\Dayofsillapas;
use App\Classes;
use App\School;
use App\Schoolranks;
use App\Jows;
use App\Jows_areas;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // ** เพิ่มบรรทัดนี้
class DatatranferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('datatranfer.index');
    }

  

    // ... code ส่วน index, create, dataempty เหมือนเดิม ...

    // ฟังก์ชันใหม่สำหรับเช็คสถานะ (Frontend จะยิงมาที่นี่เรื่อยๆ)
    public function checkProgress()
    {
        $progress = Cache::get('transfer_progress', 0);
        return response()->json(['progress' => $progress]);
    }

    public function store(Request $request)
    {
        return $this->transferRankedWinners($request);

        $this->audit($request, 'data_transfer_started');
        // ตั้งค่าเริ่มต้น Progress เป็น 0
        Cache::put('transfer_progress', 0);

        DB::beginTransaction();

        try {
            // 1. Update Activities Status
            $activityIdsToUpdate = Addschool::pluck('school_activity')->unique();
            if ($activityIdsToUpdate->isNotEmpty()) {
                Activities::whereIn('id', $activityIdsToUpdate)->update(['activity_status' => 0]);
            }

            // 2. Clear Tables
            $targetEventId = active_event_id();
            Addstudent::where('event_id', $targetEventId)->delete();
            Addschool::where('event_id', $targetEventId)->delete();
            
            // Commit การล้างข้อมูลก่อน (หรือจะรวมใน Transaction เดียวก็ได้ แต่ถ้ายาวมากอาจจะแยก)
            // ในที่นี้ขอรวมไว้ตาม logic เดิม

            // 3. ดึงข้อมูล
            $schoolsToTransfer = addschools_areas::whereIn('activity_rank', [0,1, 2, 3, 98, 99])
                ->whereIn('activity_status', [1, 98, 99])
                ->where('student_score','>',0)
                ->get();

            if ($schoolsToTransfer->isEmpty()) {
                DB::commit();
                Cache::put('transfer_progress', 100); // เสร็จสิ้น
                return response()->json(['status' => 'success', 'message' => 'ไม่พบข้อมูลโรงเรียนที่ต้องย้าย']);
            }

            $total = $schoolsToTransfer->count();
            $count = 0;

            // 4. ลูปย้ายข้อมูล
            foreach ($schoolsToTransfer as $areaSchool) {
                
                $newSchool = Addschool::create([
                    'school' => $areaSchool->school,
                    'school_name' => $areaSchool->school_name,
                    'school_schoolgroup' => $areaSchool->school_schoolgroup,
                    'school_activity' => $areaSchool->school_activity,
                ]);

                if ($newSchool) {
                    $newSchoolId = $newSchool->id;
                    $oldschoolId = $areaSchool->id;

                    $studentsToTransfer = addstudents_areas::where('school', $oldschoolId)->get();

                    if ($studentsToTransfer->isNotEmpty()) {
                        $studentsData = [];
                        foreach ($studentsToTransfer as $areaStudent) {
                            $studentsData[] = [
                                'title' => $areaStudent->title,
                                'name' => $areaStudent->name,
                                'lastname' => $areaStudent->lastname,
                                'activity' => $areaStudent->activity,
                                'type' => $areaStudent->type,
                                'school' => $newSchoolId,
                                'student_image' => $areaStudent->student_image,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        Addstudent::insert($studentsData);
                    }
                }

                // *** คำนวณและอัปเดต Progress ***
                $count++;
                $percent = intval(($count / $total) * 100);
                
                // อัปเดตลง Cache (เก็บไว้ 60 วินาที)
                Cache::put('transfer_progress', $percent, 60);
            }

            DB::commit();
            Cache::put('transfer_progress', 100); // มั่นใจว่าเต็ม 100 ตอนจบ

            // ถ้าเรียกผ่าน AJAX ให้ตอบกลับเป็น JSON
            return response()->json(['status' => 'success', 'message' => 'การย้ายข้อมูลเสร็จสมบูรณ์แล้ว']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
        public function dataempty(Request $request)
    {
        $this->audit($request, 'data_empty_started');
        // 1. เริ่มต้น Transaction เพื่อความปลอดภัยในการลบข้อมูลที่เชื่อมโยง
        DB::beginTransaction();

        try {
            // 2. ลบข้อมูลใน Addstudent ก่อน เนื่องจากมีการเชื่อมโยง Foreign Key กับ Addschool
            // **การใช้ truncate() จะเร็วกว่า delete() และรีเซ็ต Auto-Increment ID**
            $timestamp = date('YmdHis'); 
            $backupSchoolTable = 'addschools_backup_' . $timestamp;
            $backupStudentTable = 'addstudents_backup_' . $timestamp;
// 1. สร้างตารางสำเนา Addschool พร้อมคัดลอกข้อมูล
            DB::statement("CREATE TABLE {$backupSchoolTable} AS SELECT * FROM addschools");
            
            // 2. สร้างตารางสำเนา Addstudent พร้อมคัดลอกข้อมูล
            DB::statement("CREATE TABLE {$backupStudentTable} AS SELECT * FROM addstudents");
            // ตาราง Addstudent
            Addstudent::truncate(); 

            // ตาราง Addschool
            Addschool::truncate(); 

            // 3. Commit Transaction หากสำเร็จ
            DB::commit();
           // return response()->json(['status' => 'success', 'message' => 'ล้างข้อมูลตาราง Addschool และ Addstudent เสร็จสมบูรณ์แล้ว']);
            return back()->with('massage', 'ล้างข้อมูลตาราง Addschool และ Addstudent เสร็จสมบูรณ์แล้ว');

        } catch (\Exception $e) {
            // 4. Rollback หากเกิดข้อผิดพลาด
            DB::rollback();
            
     

            return back()->with('massage', 'การล้างข้อมูลล้มเหลว: ' . $e->getMessage());
        }
    }


    // ฟังก์ชันเช็ค Progress ของการ Backup
    public function checkBackupProgress()
    {
        $progress = Cache::get('backup_progress', 0);
        return response()->json(['progress' => $progress]);
    }

  public function databackup(Request $request)
    {
        $this->audit($request, 'database_backup_started');
        set_time_limit(0); 
        ini_set('memory_limit', '-1');

        Cache::put('backup_progress', 0);

        $sourceDb = 'sillapa.cpn1.go.th.2569'; 
        $cleanSourceName = str_replace('.', '_', $sourceDb);
        $targetDb = $cleanSourceName . '_' . date('Ymd_His');

        // เริ่ม Transaction
        DB::beginTransaction();

        try {
            // ✅ 1. (เพิ่มบรรทัดนี้) ปลดล็อก Strict Mode ชั่วคราว เพื่อให้รองรับค่า 0000-00-00 ได้
            DB::statement("SET SESSION sql_mode = ''");

            // 2. สร้างฐานข้อมูลใหม่
            DB::statement("CREATE DATABASE `$targetDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // 3. ดึงรายชื่อตาราง
            $tables = DB::select("SHOW FULL TABLES IN `$sourceDb` WHERE Table_Type = 'BASE TABLE'");
            
            $totalTables = count($tables);
            $count = 0;

            if ($totalTables == 0) {
                 DB::rollback();
                 return response()->json(['status' => 'error', 'message' => 'ไม่พบตารางข้อมูล']);
            }

            foreach ($tables as $tableInfo) {
                $tableArray = (array) $tableInfo;
                $tableName = reset($tableArray); 

                // สร้างตาราง (ตอนนี้จะผ่านแล้ว เพราะเราปลดล็อก Mode แล้ว)
                DB::statement("CREATE TABLE `$targetDb`.`$tableName` LIKE `$sourceDb`.`$tableName`");
                
                // ย้ายข้อมูล
                DB::statement("INSERT INTO `$targetDb`.`$tableName` SELECT * FROM `$sourceDb`.`$tableName`");

                $count++;
                $percent = intval(($count / $totalTables) * 100);
                Cache::put('backup_progress', $percent, 60);
            }

            DB::commit();
            Cache::put('backup_progress', 100);

            return response()->json([
                'status' => 'success', 
                'message' => "Backup ฐานข้อมูลเสร็จสมบูรณ์!<br>ชื่อฐานข้อมูลใหม่: <b>$targetDb</b>"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            // แสดง Error ชัดเจนขึ้น
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }





    private function audit(Request $request, string $action): void
    {
        Log::notice('System administrator operation', [
            'action' => $action,
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);
    }

    private function transferRankedWinners(Request $request)
    {
        $this->audit($request, 'data_transfer_started');

        $stage = 'เตรียมข้อมูล';
        $currentTeam = null;

        try {

        $eventId = active_event_id();
        $year = active_year();
        if (! $eventId) {
            throw new \RuntimeException('ไม่พบรหัสครั้งการแข่งขันจาก URL ปัจจุบัน');
        }

        $stage = 'ค้นหาผู้ชนะอันดับ 1–3 ระดับเครือข่าย';
        $schools = addschools_areas::with('getstudentname')
            ->whereIn('activity_rank', [1, 2, 3])
            ->where('activity_status', 1)
            ->where('student_score', '>', 0)
            ->orderBy('school_activity')
            ->orderBy('activity_rank')
            ->get();

        if ($schools->isEmpty()) {
            Cache::put('transfer_progress', 100, 60);

            return response()->json([
                'status' => 'success',
                'message' => 'ไม่พบผู้แข่งขันอันดับ 1–3 ที่ประกาศผลแล้ว ข้อมูลเดิมไม่ได้ถูกเปลี่ยนแปลง',
                'transferred_teams' => 0,
                'transferred_people' => 0,
            ]);
        }

        $incomplete = $schools->filter(fn ($school) => $school->getstudentname->isEmpty())->values();
        if ($incomplete->isNotEmpty()) {
            $activityNames = Activities::whereIn('id', $incomplete->pluck('school_activity')->unique())
                ->pluck('activity_name', 'id');
            $schoolNames = School::whereIn('id', $incomplete->pluck('school')->unique())
                ->pluck('school_name', 'id');
            $details = $incomplete->map(fn ($school) => [
                'team_id' => $school->id,
                'school' => $schoolNames->get($school->school, 'ไม่พบชื่อโรงเรียน').' (ID '.$school->school.')',
                'activity' => $activityNames->get($school->school_activity, 'ไม่พบชื่อกิจกรรม').' (ID '.$school->school_activity.')',
                'rank' => $school->activity_rank,
                'problem' => 'ไม่พบรายชื่อนักเรียนหรือครูในทีม',
            ])->values();

            return response()->json([
                'status' => 'error',
                'error_code' => 'TRANSFER_DATA_INCOMPLETE',
                'stage' => 'ตรวจสอบความครบถ้วนก่อนโอน',
                'message' => 'ยกเลิกการโอน เนื่องจากพบทีมอันดับ 1–3 ที่ไม่มีรายชื่อนักเรียนหรือครู จำนวน '.$details->count().' ทีม',
                'team_ids' => $incomplete->pluck('id'),
                'errors' => $details,
            ], 422);
        }

        Cache::put('transfer_progress', 0, 60);
        $peopleCount = 0;

        $stage = 'บันทึกข้อมูลระดับเขตพื้นที่';
        DB::transaction(function () use ($schools, $eventId, $year, &$peopleCount, &$stage, &$currentTeam): void {
            $activityIds = $schools->pluck('school_activity')->unique()->values();
            $stage = 'ปรับสถานะรายการแข่งขันระดับเขตพื้นที่';
            Activities::whereIn('id', $activityIds)->update(['activity_status' => 0]);

            $stage = 'ล้างข้อมูลผู้แข่งขันระดับเขตพื้นที่เดิม';
            Addstudent::where('event_id', $eventId)->delete();
            Addschool::where('event_id', $eventId)->delete();

            foreach ($schools as $index => $sourceSchool) {
                $stage = 'โอนทีมลำดับที่ '.($index + 1).' จาก '.$schools->count();
                $currentTeam = [
                    'team_id' => $sourceSchool->id,
                    'school_id' => $sourceSchool->school,
                    'activity_id' => $sourceSchool->school_activity,
                    'rank' => $sourceSchool->activity_rank,
                ];
                $newSchoolId = DB::table('addschools')->insertGetId([
                    'event_id' => $eventId,
                    'year' => $year,
                    'school' => $sourceSchool->school,
                    'school_name' => $sourceSchool->school_name,
                    'school_schoolgroup' => $sourceSchool->school_schoolgroup,
                    'school_activity' => $sourceSchool->school_activity,
                    'student_score' => 0,
                    'activity_rank' => 0,
                    'activity_status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $studentRows = $sourceSchool->getstudentname->map(function ($person) use ($eventId, $year, $newSchoolId) {
                    return [
                        'event_id' => $eventId,
                        'year' => $year,
                        'title' => $person->title,
                        'name' => $person->name,
                        'lastname' => $person->lastname,
                        'activity' => $person->activity,
                        'school' => $newSchoolId,
                        'type' => $person->type,
                        'medel' => $person->medel,
                        'student_image' => $person->student_image,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->all();

                DB::table('addstudents')->insert($studentRows);
                $peopleCount += count($studentRows);
                Cache::put('transfer_progress', (int) ((($index + 1) / $schools->count()) * 100), 60);
            }
        });

        Cache::put('transfer_progress', 100, 60);

        return response()->json([
            'status' => 'success',
            'message' => 'โอนผู้แข่งขันอันดับ 1–3 ไปยังระดับเขตพื้นที่เรียบร้อยแล้ว',
            'transferred_teams' => $schools->count(),
            'transferred_people' => $peopleCount,
        ]);
        } catch (\Throwable $exception) {
            Cache::put('transfer_progress', 0, 60);
            $errorId = 'TR-'.now()->format('Ymd-His').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

            Log::error('Competition data transfer failed', [
                'error_id' => $errorId,
                'event_id' => active_event_id(),
                'stage' => $stage,
                'team' => $currentTeam,
                'exception' => $exception,
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
            ]);

            $technicalMessage = $exception instanceof \Illuminate\Database\QueryException
                ? 'ฐานข้อมูลปฏิเสธคำสั่ง: '.$exception->getMessage()
                : $exception->getMessage();

            return response()->json([
                'status' => 'error',
                'error_code' => 'TRANSFER_FAILED',
                'error_id' => $errorId,
                'stage' => $stage,
                'message' => 'โอนข้อมูลไม่สำเร็จ ระบบยกเลิกการเปลี่ยนแปลงทั้งหมดแล้ว',
                'technical_message' => $technicalMessage,
                'team' => $currentTeam,
            ], 500);
        }
    }
}

