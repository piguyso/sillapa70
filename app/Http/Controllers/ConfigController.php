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
            
            $config = Configs::where('schoolgroup', 0)
                             ->where('app_year', $selectedYear)
                             ->get();
        }

        $configarea = Configs::all();
        $years = \App\Years::orderBy('years', 'desc')->get();
        $data = Array(
            'config' => $config,
            'configarea' => $configarea,
            'years' => $years,
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
        $Configs = Configs::where('schoolgroup', Auth::user()->schoolgroup)->where('id', $id)->firstOrFail();
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
