<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configs;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
        $selectedYear = $request->query('year') ?: active_year();
        
        $config = Configs::where('schoolgroup', 0)
                         ->where('app_year', $selectedYear)
                         ->get();
                         
        if ($config->isEmpty()) {
            // Clone the most recent config info if available, to make creation seamless
            $latestConfig = Configs::where('schoolgroup', 0)->orderBy('app_year', 'desc')->first();
            
            $newConfig = new Configs();
            $newConfig->schoolgroup = 0;
            $newConfig->app_year = $selectedYear;
            
            if ($latestConfig) {
                $newConfig->app_name = $latestConfig->app_name;
                $newConfig->app_slogan = $latestConfig->app_slogan;
                $newConfig->app_group = $latestConfig->app_group;
                $newConfig->app_area = $latestConfig->app_area;
                $newConfig->app_certificate = $latestConfig->app_certificate;
                $newConfig->app_logo = $latestConfig->app_logo;
                $newConfig->link_criteria = $latestConfig->link_criteria;
                $newConfig->link_ai = $latestConfig->link_ai;
                $newConfig->link_survey = $latestConfig->link_survey;
                $newConfig->survey_qr = $latestConfig->survey_qr;
            } else {
                $newConfig->app_name = "งานศิลปหัตถกรรมนักเรียน";
            }
            $newConfig->save();

            // Auto-clone configs ของทุก schoolgroup (1, 2, 3, ...) จากปีก่อนหน้า
            // เพื่อให้รูปพื้นหลังเกียรติบัตรของแต่ละเครือข่ายไม่หายไปในปีใหม่
            $prevYearAreaConfigs = Configs::where('schoolgroup', '>', 0)
                ->where('app_year', '!=', $selectedYear)
                ->orderBy('app_year', 'desc')
                ->get()
                ->groupBy('schoolgroup');

            foreach ($prevYearAreaConfigs as $sgId => $sgConfigs) {
                $alreadyExists = Configs::where('schoolgroup', $sgId)
                    ->where('app_year', $selectedYear)
                    ->exists();
                if (!$alreadyExists) {
                    $src = $sgConfigs->first();
                    $clone = new Configs();
                    $clone->schoolgroup = $sgId;
                    $clone->app_year = $selectedYear;
                    $clone->app_name = $src->app_name;
                    $clone->app_slogan = $src->app_slogan;
                    $clone->app_group = $src->app_group;
                    $clone->app_area = $src->app_area;
                    $clone->app_certificate = $src->app_certificate; // รูปพื้นหลังเดิม (จนกว่าจะ upload ใหม่)
                    $clone->app_logo = $src->app_logo;
                    $clone->link_criteria = $src->link_criteria;
                    $clone->link_ai = $src->link_ai;
                    $clone->link_survey = $src->link_survey;
                    $clone->survey_qr = $src->survey_qr;
                    $clone->save();
                }
            }
            
            $config = Configs::where('schoolgroup', 0)
                             ->where('app_year', $selectedYear)
                             ->get();
        }


        $configarea = Configs::where('app_year', $selectedYear)->get();
        $allyears = \App\Years::orderBy('years', 'desc')->get();
        $years = \App\Years::orderBy('years', 'desc')->get();
        $data = Array(
            'config' => $config,
            'configarea' => $configarea,
            'years' => $years,
            'allyears' => $allyears,
            'selectedYear' => $selectedYear,
        );
    
        return view('Home.config', $data);
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
            'source_year' => 'required|string|size:4',
            'clear_existing' => 'nullable|string'
        ]);

        $sourceYear = $request->source_year;
        $activeYear = active_year();

        if ($sourceYear == $activeYear) {
            return response()->json([
                'success' => false,
                'message' => 'ปีการศึกษาต้นทางและปลายทางต้องไม่ซ้ำกัน'
            ], 422);
        }

        $subjectgroups = \Illuminate\Support\Facades\DB::table('subjectgroups')
            ->where('year', $sourceYear)
            ->get();

        $branches = \Illuminate\Support\Facades\DB::table('branches')
            ->where('year', $sourceYear)
            ->get();

        $activities = \Illuminate\Support\Facades\DB::table('activities')
            ->where('year', $sourceYear)
            ->get();

        if ($subjectgroups->isEmpty() && $branches->isEmpty() && $activities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลโครงสร้างกลุ่มสาระ สถานที่แข่งขัน หรือกิจกรรมการแข่งขันในปีการศึกษาต้นทางที่เลือก'
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($subjectgroups, $branches, $activities, $activeYear, $request) {
                if ($request->clear_existing === 'on' || $request->clear_existing === '1' || $request->clear_existing === 'true') {
                    \Illuminate\Support\Facades\DB::table('activities')
                        ->where('year', $activeYear)
                        ->delete();
                    
                    \Illuminate\Support\Facades\DB::table('branches')
                        ->where('year', $activeYear)
                        ->delete();

                    \Illuminate\Support\Facades\DB::table('subjectgroups')
                        ->where('year', $activeYear)
                        ->delete();
                }

                $subjectgroupMap = [];
                foreach ($subjectgroups as $sg) {
                    $newId = \Illuminate\Support\Facades\DB::table('subjectgroups')->insertGetId([
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
                        'year'          => $activeYear,
                        'branch_name'   => $br->branch_name,
                        'branch_detail' => $br->branch_detail,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    $branchMap[$br->id] = $newId;
                }

                foreach ($activities as $act) {
                    $newSubjectId = isset($subjectgroupMap[$act->activity_subject]) ? $subjectgroupMap[$act->activity_subject] : $act->activity_subject;
                    $newBranchId = isset($branchMap[$act->activity_branch]) ? $branchMap[$act->activity_branch] : $act->activity_branch;

                    $data = [
                        'year'                      => $activeYear,
                        'in_dex'                    => $act->in_dex,
                        'activity_name'             => $act->activity_name,
                        'activity_subject'          => $newSubjectId,
                        'activity_class'            => $act->activity_class,
                        'activity_studennumber'     => $act->activity_studennumber,
                        'activity_teachernumber'    => $act->activity_teachernumber,
                        'activity_teamnumber'       => $act->activity_teamnumber,
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
    
            'app_certificate' => 'mimes:JPG,jpg,jpeg,png|max:12048'
           
        ]);

        $user = Auth::user();

        // position=5 (เครือข่ายสถานศึกษา) — แก้ได้เฉพาะ app_certificate ของ schoolgroup ตัวเอง
        if ($user->position == '5') {
            $Configs = Configs::where('schoolgroup', $user->schoolgroup)
                              ->where('id', $id)
                              ->firstOrFail();
            $storageYear = $Configs->app_year ?: active_year();

            $fileupload = $request->file('app_certificate');
            if ($fileupload) {
                $fileupload_filename = time().'_'.$fileupload->getClientOriginalName();
                $fileupload->storeAs(storage_year_path('upload', '', $storageYear), $fileupload_filename, 'public');
                $Configs->app_certificate = $fileupload_filename;
                $Configs->save();
            }

            Session::flash('massage', 'อัปเดตรูปพื้นหลังเกียรติบัตรเรียบร้อย!');
            return redirect()->back();
        }

        // position=1, 10 (admin) — แก้ไขได้ทั้งหมด
        $Configs = Configs::where('schoolgroup', $user->schoolgroup)->where('id', $id)->firstOrFail();
        $storageYear = $Configs->app_year ?: active_year();

        $fileupload = $request->file('app_certificate');
        if($fileupload){
            $fileupload_filename = time().'_'.$fileupload->getClientOriginalName();
            $fileupload->storeAs(storage_year_path('upload', '', $storageYear), $fileupload_filename, 'public');
        }else{
            $fileupload_filename='';
        }

        if ($request->filled('cropped_logo')) {
            $image = $request->cropped_logo;  // data:image/png;base64,xxxx
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time().'_logo.png';
            Storage::disk('public')->put(storage_year_path('upload', $imageName, $storageYear), base64_decode($image));
        }else{
            $imageName = "";
        }

        if ($request->filled('cropped_qr')) {
            $imageQr = $request->cropped_qr;  // data:image/png;base64,xxxx
            $imageQr = str_replace('data:image/png;base64,', '', $imageQr);
            $imageQr = str_replace(' ', '+', $imageQr);
            $imageQrName = time().'_qr.png';
            Storage::disk('public')->put(storage_year_path('upload', $imageQrName, $storageYear), base64_decode($imageQr));
        }else{
            $imageQrName = "";
        }

        //dd($fileupload_filename);
        $Configs->app_name = $request->app_name;
        $Configs->app_slogan = $request->app_slogan;
        $Configs->app_group = $request->app_group;
        $Configs->app_area = $request->app_area;
        $Configs->app_year = $request->app_year;
        
        $Configs->link_criteria = $request->link_criteria;
        $Configs->link_ai = $request->link_ai;
        $Configs->link_survey = $request->link_survey;

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
        
        session([
            'app_name'  => $Configs->app_name,
            'app_group' => $Configs->app_group,
            'app_area'  => $Configs->app_area,
            'app_year'  => $Configs->app_year,
            'year'      => $Configs->app_year,
        ]);
        session()->forget('manual_year');

        Session::flash('massage','Data was update successfull!!');
        // return redirect('config');
        return redirect()->back();
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
