<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configs;
use App\Event;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageUploadService;
class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
     {
         $this->middleware('auth');
     }
    public function index(Request $request)
    {
        $currentEvent = active_event();
        abort_unless($currentEvent, 404);
        $selectedYear = $currentEvent->academic_year;
        
        $config = Configs::where('schoolgroup', 0)
                         ->where('event_id', $currentEvent->id)
                         ->get();
                         
        if ($config->isEmpty() && $currentEvent->is_active) {
            // Clone the most recent config info if available, to make creation seamless
            $latestConfig = Configs::where('schoolgroup', 0)->orderBy('app_year', 'desc')->first();
            
            $newConfig = new Configs();
            $newConfig->event_id = $currentEvent->id;
            $newConfig->schoolgroup = 0;
            $newConfig->app_year = $selectedYear;
            
            if ($latestConfig) {
                $newConfig->app_name = $latestConfig->app_name;
                $newConfig->app_slogan = $latestConfig->app_slogan;
                $newConfig->app_group = $latestConfig->app_group;
                $newConfig->app_area = $latestConfig->app_area;
                $newConfig->network_label = $latestConfig->network_label;
                $newConfig->representative_count = $latestConfig->representative_count;
                $newConfig->app_certificate = $latestConfig->app_certificate;
                $newConfig->app_logo = $latestConfig->app_logo;
                $newConfig->link_criteria = $latestConfig->link_criteria;
                $newConfig->link_ai = $latestConfig->link_ai;
                $newConfig->link_survey = $latestConfig->link_survey;
                $newConfig->survey_qr = $latestConfig->survey_qr;
                $newConfig->theme_primary = $latestConfig->theme_primary ?: '#059669';
                $newConfig->theme_secondary = $latestConfig->theme_secondary ?: '#0f172a';
            } else {
                $newConfig->app_name = "งานศิลปหัตถกรรมนักเรียน";
                $newConfig->theme_primary = '#059669';
                $newConfig->theme_secondary = '#0f172a';
            }
            $newConfig->save();

            // Auto-clone configs ของทุก schoolgroup (1, 2, 3, ...) จากปีก่อนหน้า
            // เพื่อให้รูปพื้นหลังเกียรติบัตรของแต่ละเครือข่ายไม่หายไปในปีใหม่
            $prevYearAreaConfigs = Configs::where('schoolgroup', '>', 0)
                ->where('event_id', '!=', $currentEvent->id)
                ->orderBy('app_year', 'desc')
                ->get()
                ->groupBy('schoolgroup');

            foreach ($prevYearAreaConfigs as $sgId => $sgConfigs) {
                $alreadyExists = Configs::where('schoolgroup', $sgId)
                    ->where('event_id', $currentEvent->id)
                    ->exists();
                if (!$alreadyExists) {
                    $src = $sgConfigs->first();
                    $clone = new Configs();
                    $clone->event_id = $currentEvent->id;
                    $clone->schoolgroup = $sgId;
                    $clone->app_year = $selectedYear;
                    $clone->app_name = $src->app_name;
                    $clone->app_slogan = $src->app_slogan;
                    $clone->app_group = $src->app_group;
                    $clone->app_area = $src->app_area;
                    $clone->network_label = $src->network_label;
                    $clone->app_certificate = $src->app_certificate; // รูปพื้นหลังเดิม (จนกว่าจะ upload ใหม่)
                    $clone->app_logo = $src->app_logo;
                    $clone->link_criteria = $src->link_criteria;
                    $clone->link_ai = $src->link_ai;
                    $clone->link_survey = $src->link_survey;
                    $clone->survey_qr = $src->survey_qr;
                    $clone->theme_primary = $src->theme_primary ?: '#059669';
                    $clone->theme_secondary = $src->theme_secondary ?: '#0f172a';
                    $clone->save();
                }
            }
            
            $config = Configs::where('schoolgroup', 0)
                             ->where('event_id', $currentEvent->id)
                             ->get();
        }


        $configarea = Configs::where('event_id', $currentEvent->id)->get();
        $allyears = \App\Years::orderBy('years', 'desc')->get();
        $years = \App\Years::orderBy('years', 'desc')->get();
        $data = Array(
            'config' => $config,
            'configarea' => $configarea,
            'years' => $years,
            'allyears' => $allyears,
            'selectedYear' => $selectedYear,
            'events' => Event::orderByDesc('event_number')->get(),
            'currentEvent' => $currentEvent,
            'readOnly' => ! $currentEvent->is_active,
        );
    
        return view('Home.config', $data);
    }

    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'event_number' => 'required|integer|min:1|unique:events,event_number',
            'academic_year' => 'required|integer|digits:4|unique:events,academic_year',
            'name' => 'nullable|string|max:255',
        ]);

        $sourceConfigs = Configs::where('event_id', active_event_id())->get();

        $event = DB::transaction(function () use ($data, $sourceConfigs) {
            $event = Event::create([
                'event_number' => $data['event_number'],
                'academic_year' => $data['academic_year'],
                'name' => $data['name'] ?: 'การแข่งขันครั้งที่ '.$data['event_number'],
                'status' => 'draft',
                'is_active' => false,
            ]);

            if ($sourceConfigs->isEmpty()) {
                $config = new Configs();
                $config->event_id = $event->id;
                $config->schoolgroup = 0;
                $config->app_year = $event->academic_year;
                $config->app_name = $data['name'] ?: 'งานศิลปหัตถกรรมนักเรียน';
                $config->save();
            } else {
                foreach ($sourceConfigs as $sourceConfig) {
                    $config = $sourceConfig->replicate();
                    $config->event_id = $event->id;
                    $config->app_year = $event->academic_year;
                    if ((int) $config->schoolgroup === 0 && ! empty($data['name'])) {
                        $config->app_name = $data['name'];
                    }
                    $config->save();
                }
            }

            return $event;
        });

        return redirect(event_url('config', $event->event_number))->with('massage', 'เพิ่มครั้งการแข่งขันแล้ว');
    }

    public function updateEvent(Request $request, Event $event)
    {
        $data = $request->validate([
            'event_number' => 'required|integer|min:1|unique:events,event_number,'.$event->id,
            'academic_year' => 'required|integer|digits:4|unique:events,academic_year,'.$event->id,
            'name' => 'nullable|string|max:255',
        ]);

        $event->update($data);
        Configs::where('event_id', $event->id)->update(['app_year' => $event->academic_year]);

        return redirect(event_url('config', $event->event_number))->with('massage', 'บันทึกข้อมูลครั้งการแข่งขันแล้ว');
    }

    public function activateEvent(Event $event)
    {
        DB::transaction(function () use ($event) {
            Event::query()->update(['is_active' => false, 'status' => 'archived']);
            Event::whereKey($event->getKey())->update(['is_active' => true, 'status' => 'active']);
        });

        return redirect(event_url('config', $event->event_number))->with('massage', 'ตั้งเป็นครั้งที่ใช้งานปัจจุบันแล้ว');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->is_active) {
            return redirect()->back()->with('error', 'ไม่สามารถลบครั้งที่กำลังเปิดใช้งานปัจจุบันได้ กรุณาสลับไปใช้งานครั้งอื่นก่อนทำการลบ');
        }

        $eventTables = [
            'configs',
            'subjectgroups',
            'activities',
            'activities_areas',
            'branches',
            'dayofsillapas',
            'notices',
            'pictures',
            'addschools',
            'addstudents',
            'jows',
            'schoolranks',
            'addschools_areas',
            'addstudents_areas',
            'jows_areas',
        ];

        $deletedEventNumber = $event->event_number;

        DB::transaction(function () use ($event, $eventTables) {
            $eventId = $event->id;

            foreach ($eventTables as $table) {
                if (\Illuminate\Support\Facades\Schema::hasTable($table) && \Illuminate\Support\Facades\Schema::hasColumn($table, 'event_id')) {
                    DB::table($table)->where('event_id', $eventId)->delete();
                }
            }

            $event->delete();
        });

        $fallback = Event::where('is_active', true)->first() ?: Event::orderByDesc('event_number')->first();
        $targetNumber = $fallback ? $fallback->event_number : 74;

        return redirect(event_url('config', $targetNumber))->with('massage', 'ลบการแข่งขันครั้งที่ ' . $deletedEventNumber . ' เรียบร้อยแล้ว');
    }

    public function addYear(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|digits:4|unique:years,years'
        ]);

        $year = new \App\Years();
        $year->years = $request->year;
        $year->save();

        return response()->json([
            'success' => true,
            'year' => $year
        ]);
    }

    public function deleteYear($id)
    {
        $year = \App\Years::findOrFail($id);
        $year->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function setActiveYear($id)
    {
        $year = \App\Years::findOrFail($id);
        
        \App\Years::where('active', 1)->update(['active' => 0]);
        
        $year->active = 1;
        $year->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function setActiveYearByValue($yearVal)
    {
        $year = \App\Years::where('years', $yearVal)->first();
        if ($year) {
            \App\Years::where('active', 1)->update(['active' => 0]);
            
            $year->active = 1;
            $year->save();

            session(['year' => $yearVal]);
            session()->forget('manual_year');

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Year not found'
        ], 404);
    }

    public function copyActivities(Request $request)
    {
        $request->validate([
            'source_event_id' => 'required|integer|exists:events,id',
            'clear_existing' => 'nullable|string'
        ]);

        $activeYear = active_year();
        $targetEvent = active_event();
        $sourceEvent = Event::find($request->source_event_id);

        if (! $targetEvent || ! $sourceEvent) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบครั้งการแข่งขันต้นทางหรือปลายทาง'
            ], 422);
        }

        if ((int) $sourceEvent->id === (int) $targetEvent->id) {
            return response()->json([
                'success' => false,
                'message' => 'ปีการศึกษาต้นทางและปลายทางต้องไม่ซ้ำกัน'
            ], 422);
        }

        $subjectgroups = \Illuminate\Support\Facades\DB::table('subjectgroups')
            ->where('event_id', $sourceEvent->id)
            ->get();

        $branches = \Illuminate\Support\Facades\DB::table('branches')
            ->where('event_id', $sourceEvent->id)
            ->get();

        $activities = \Illuminate\Support\Facades\DB::table('activities')
            ->where('event_id', $sourceEvent->id)
            ->get();

        if ($subjectgroups->isEmpty() && $branches->isEmpty() && $activities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลโครงสร้างกลุ่มสาระ สถานที่แข่งขัน หรือกิจกรรมการแข่งขันในปีการศึกษาต้นทางที่เลือก'
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($subjectgroups, $branches, $activities, $activeYear, $targetEvent, $request) {
                if ($request->clear_existing === 'on' || $request->clear_existing === '1' || $request->clear_existing === 'true') {
                    \Illuminate\Support\Facades\DB::table('activities')
                        ->where('event_id', $targetEvent->id)
                        ->delete();
                    
                    \Illuminate\Support\Facades\DB::table('branches')
                        ->where('event_id', $targetEvent->id)
                        ->delete();

                    \Illuminate\Support\Facades\DB::table('subjectgroups')
                        ->where('event_id', $targetEvent->id)
                        ->delete();
                }

                $subjectgroupMap = [];
                foreach ($subjectgroups as $sg) {
                    $newId = \Illuminate\Support\Facades\DB::table('subjectgroups')->insertGetId([
                        'event_id'            => $targetEvent->id,
                        'year'                 => $activeYear,
                        'subjectgroup_name'    => $sg->subjectgroup_name,
                        'subjectgroup_detail'  => $sg->subjectgroup_detail,
                        'in_dex'               => $sg->in_dex,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                    $subjectgroupMap[$sg->id] = $newId;
                }

                $branchMap = [];
                foreach ($branches as $br) {
                    $newId = \Illuminate\Support\Facades\DB::table('branches')->insertGetId([
                        'event_id'      => $targetEvent->id,
                        'year'          => $activeYear,
                        'branch_name'   => $br->branch_name,
                        'branch_detail' => $br->branch_detail,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    $branchMap[$br->id] = $newId;
                }

                foreach ($activities as $act) {
                    if ($act->activity_subject && ! isset($subjectgroupMap[$act->activity_subject])) {
                        throw new \RuntimeException('กลุ่มสาระของรายการแข่งขันไม่พบในข้อมูลต้นทาง');
                    }
                    if ($act->activity_branch && ! isset($branchMap[$act->activity_branch])) {
                        throw new \RuntimeException('ศูนย์การแข่งขันของรายการแข่งขันไม่พบในข้อมูลต้นทาง');
                    }
                    $newSubjectId = $act->activity_subject ? $subjectgroupMap[$act->activity_subject] : $act->activity_subject;
                    $newBranchId = $act->activity_branch ? $branchMap[$act->activity_branch] : $act->activity_branch;

                    $data = [
                        'event_id'                  => $targetEvent->id,
                        'year'                      => $activeYear,
                        'in_dex'                    => $act->in_dex,
                        'activity_name'             => $act->activity_name,
                        'activity_subject'          => $newSubjectId,
                        'activity_class'            => $act->activity_class,
                        'activity_studennumber'     => $act->activity_studennumber,
                        'activity_teachernumber'    => $act->activity_teachernumber,
                        'activity_teamnumber'       => 999,
                        'activity_schoolteamnumber' => $act->activity_schoolteamnumber,
                        'activity_score'            => $act->activity_score,
                        'activity_branch'           => $newBranchId,
                        'activity_branchroom'       => $act->activity_branchroom,
                        'activity_day'              => $act->activity_day,
                        'activity_time'             => $act->activity_time,
                        'activity_timestart'        => $act->activity_timestart,
                        'activity_status'           => 0,
                        'created_at'                => now(),
                        'updated_at'                => now(),
                    ];

                    \Illuminate\Support\Facades\DB::table('activities')->insert($data);
                }
            });

            Log::notice('Competition structure transferred between events', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'source_event_id' => $sourceEvent->id,
                'target_event_id' => $targetEvent->id,
                'clear_existing' => $request->boolean('clear_existing'),
                'subjectgroups_count' => $subjectgroups->count(),
                'branches_count' => $branches->count(),
                'activities_count' => $activities->count(),
            ]);

            return response()->json([
                'success' => true,
                'activities_count' => $activities->count(),
                'branches_count' => $branches->count(),
                'subjectgroups_count' => $subjectgroups->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการคัดลอกข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request);
        $this->validate($request,[
            'app_certificate' => 'nullable|mimes:JPG,jpg,jpeg,png,webp|max:12048',
            'app_year' => 'nullable|integer|digits:4',
            'app_name' => 'nullable|string|max:255',
            'app_group' => 'nullable|string|max:255',
            'app_area' => 'nullable|string|max:255',
            'network_label' => 'nullable|string|max:100',
            'representative_count' => 'nullable|integer|min:1|max:'.\App\Services\TransferReadinessService::MAX_REPRESENTATIVES,
            'link_criteria' => 'nullable|url|max:2048',
            'link_ai' => 'nullable|url|max:2048',
            'link_survey' => 'nullable|url|max:2048',
           
        ]);

        $user = Auth::user();

        // position=5 (เครือข่ายสถานศึกษา) — แก้ได้เฉพาะ app_certificate ของ schoolgroup ตัวเอง
        if ($user->position == '5') {
            $Configs = Configs::where('schoolgroup', $user->schoolgroup)
                              ->where('event_id', active_event_id())
                              ->where('id', $id)
                              ->firstOrFail();
            $storageYear = $Configs->app_year ?: active_year();

            $fileupload = $request->file('app_certificate');
            if ($fileupload) {
                $fileupload_filename = safe_upload_filename($fileupload, false);
                $fileupload->storeAs(storage_year_path('upload', '', $storageYear), $fileupload_filename, 'public');
                $Configs->app_certificate = $fileupload_filename;
                $Configs->save();
            }

            Session::flash('massage', 'อัปเดตรูปพื้นหลังเกียรติบัตรเรียบร้อย!');
            return redirect()->back();
        }

        // position=1, 10 (admin) — แก้ไขได้ทั้งหมด
        if ($user->position == '10' || $user->position == '1') {
            $Configs = Configs::where('event_id', active_event_id())->findOrFail($id);
        } else {
            $Configs = Configs::where('event_id', active_event_id())
                ->where('schoolgroup', $user->schoolgroup)
                ->where('id', $id)
                ->firstOrFail();
        }
        $storageYear = $Configs->app_year ?: active_year();

        $fileupload = $request->file('app_certificate');
        if($fileupload){
            $fileupload_filename = safe_upload_filename($fileupload, false);
            $fileupload->storeAs(storage_year_path('upload', '', $storageYear), $fileupload_filename, 'public');
        }else{
            $fileupload_filename='';
        }

        if ($request->filled('cropped_logo')) {
            $imageName = app(ImageUploadService::class)->storeBase64Webp(
                $request->cropped_logo, 'upload', (string) $storageYear, 'logo', $Configs->app_logo, 1200
            );
        }else{
            $imageName = "";
        }

        if ($request->filled('cropped_qr')) {
            $imageQrName = app(ImageUploadService::class)->storeBase64Webp(
                $request->cropped_qr, 'upload', (string) $storageYear, 'qr', $Configs->survey_qr, 1200
            );
        }else{
            $imageQrName = "";
        }

        if ($request->has('app_name')) {
            $Configs->app_name = $request->app_name;
        }
        if ($request->has('app_slogan')) {
            $Configs->app_slogan = $request->app_slogan;
        }
        if ($request->has('theme_primary')) {
            $validatedTheme = $request->validate([
                'theme_primary' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'theme_secondary' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            ]);
            if (!empty($validatedTheme['theme_primary'])) {
                $Configs->theme_primary = $validatedTheme['theme_primary'];
            }
            if (!empty($validatedTheme['theme_secondary'])) {
                $Configs->theme_secondary = $validatedTheme['theme_secondary'];
            }
        }
        if ($request->has('app_group')) {
            $Configs->app_group = $request->app_group;
        }
        if ($request->has('app_area')) {
            $Configs->app_area = $request->app_area;
        }
        if ($request->has('network_label')) {
            $label = trim((string) $request->network_label);
            $Configs->network_label = ($label === '' || $label === 'เครือข่ายสถานศึกษา') ? null : $label;
        }
        if ($request->has('representative_count')) {
            $Configs->representative_count = $request->filled('representative_count') ? (int) $request->representative_count : null;
        }
        if ($request->has('app_year')) {
            $Configs->app_year = $request->app_year;
        }
        if ($request->has('link_criteria')) {
            $Configs->link_criteria = $request->link_criteria;
        }
        if ($request->has('link_ai')) {
            $Configs->link_ai = $request->link_ai;
        }
        if ($request->has('link_survey')) {
            $Configs->link_survey = $request->link_survey;
        }

        if($fileupload_filename != ""){
            $Configs->app_certificate = $fileupload_filename;
        }
        if($imageQrName != ""){
            $Configs->survey_qr = $imageQrName;
        }
        if($imageName != "")
        {
            $Configs->app_logo = $imageName;
        }

   
        $Configs->save();

        if ($Configs->event_id) {
            $event = Event::find($Configs->event_id);
            if ($event) {
                if ($request->filled('app_year')) {
                    $event->academic_year = $request->app_year;
                    Configs::where('event_id', $event->id)->update(['app_year' => $request->app_year]);
                }
                if ($request->filled('app_name')) {
                    $event->name = $request->app_name;
                }
                $event->save();
            }
        }
        
        session([
            'app_name'  => $Configs->app_name,
            'app_group' => $Configs->app_group,
            'app_area'  => $Configs->app_area,
            'app_year'  => $Configs->app_year,
            'year'      => $Configs->app_year,
            'theme_primary' => $Configs->theme_primary,
            'theme_secondary' => $Configs->theme_secondary,
        ]);
        session()->forget('manual_year');

        Session::flash('massage', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
        return redirect(event_url('config', active_event_number()));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
