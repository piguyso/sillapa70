<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Configs;
use Auth;
use Illuminate\Support\Facades\Session;
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
    public function index()
    {
        
       $config = Configs::where('schoolgroup',0)->get();
       $configarea = Configs::all();
       $data = Array(
           'config' =>$config,
           'configarea' => $configarea,
       );
   
       return view('Home.config',$data);
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
        $fileupload = $request->file('app_certificate');
        if($fileupload){

            $fileupload_filename = time().'_'.$fileupload->getClientOriginalName();
           $fileupload_filepath = $fileupload->move(public_path("upload"), $fileupload_filename);
         
           // return($fileupload_filename.'=='.$fileupload_filepath);
       }else{
            $fileupload_filename='';
        }

        if ($request->filled('cropped_logo')) {
            $image = $request->cropped_logo;  // data:image/png;base64,xxxx
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time().'_logo.png';
            \File::put(public_path(). '/upload/' . $imageName, base64_decode($image));
            
           // dd($imageName);
            // อัปเดตชื่อไฟล์ลง Database
          //  $Configs->app_logo = $imageName;
        }else{
            $imageName = "";
        }

        //dd($fileupload_filename);
       // $Configs= Configs::find($id);
        $Configs = Configs::where('schoolgroup',Auth::user()->schoolgroup)->first();
        $Configs->app_name = $request->app_name;
        $Configs->app_group = $request->app_group;
        $Configs->app_area = $request->app_area;
        $Configs->app_year = $request->app_year;
        
        if($fileupload_filename != ""){
        $Configs->app_certificate = $fileupload_filename;
        }
        if($imageName != "")
            {
                $Configs->app_logo = $imageName;
            }
   
    
    
    
    
        $Configs->save();
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
