<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notices;
use Auth;
use Illuminate\Support\Facades\Session;
class NoticeController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function noticeupload(Request $request){
     
       // dd($request);
        if ($_FILES['image']['name']) {
            if (!$_FILES['image']['error']) {
               $name = md5(rand(100, 200));
               $ext = explode('.', $_FILES['image']['name']);
               $filename = $name . '.' . $ext[1];
               $destination = 'upload/' . $filename; //change this directory
               $location = $_FILES["image"]["tmp_name"];
               move_uploaded_file($location, $destination);
               echo 'upload/' . $filename;//change this URL
            }
            else
            {
             echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
            }
           }


    }




    public function notice_detail($id){
        $notice = Notices::where('id','=',$id)->get();
        $data = Array(
            'notice'=>$notice,
        );
        return view('Notice.notice_detail',$data);
    }

   public function areanotice_detail($id){
        $notice = Notices::where('id','=',$id)->get();
        $data = Array(
            'notice'=>$notice,
        );
        return view('Notice.areanotice_detail',$data);
    }







    public function index()
    {
        $notice = Notices::all();
        $data=Array(
            'notice'=>$notice,
        );

        //dd($data);
        return view('Notice.index',$data);
        
    }

    public function shownotice()
    {
      //  return $type;
       $shownotice = Notices::where('owner_id','=',Auth::user()->id)->paginate(20);
        $data = Array(
            'shownotice' =>$shownotice,
        );
     //   return $data;
        return view('Notice.shownotice',$data);
    }
    public function shownoticearea()
    {
      //  return $type;
       $shownotice = Notices::where('owner_id','=',Auth::user()->id)->paginate(20);
        $data = Array(
            'shownotice' =>$shownotice,
        );
     //   return $data;
        return view('Notice.shownoticearea',$data);
    }

    public function adddata(){
        return view('myuser.adddata');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Notice.adddata');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request);
        $this->validate($request,[
            'notice_text'=>'required',
            'notice_detail'=>'required',
            'notice_file' => 'mimes:csv,gif,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,zip,rar|max:12048'
           
        ]);
        $fileupload = $request->file('notice_file');
        if($fileupload){

            $fileupload_filename = time().'_'.$fileupload->getClientOriginalName();
           $fileupload_filepath = $fileupload->move(public_path("upload"), $fileupload_filename);
         
           // return($fileupload_filename.'=='.$fileupload_filepath);
       }else{
            $fileupload_filename='';
        }

        $notice = new Notices;
        $notice->notice_text = $request->notice_text;
        $notice->notice_detail = $request->notice_detail;
        $notice->notice_type = Auth::user()->position;
        $notice->notice_owner = Auth::user()->name.' '.Auth::user()->lastname;
        $notice->owner_id = Auth::user()->id;
        $notice->notice_file = $fileupload_filename;
        $notice->schoolgroup = Auth::user()->schoolgroup;
        $notice->save();
        Session::flash('massage','เพิ่มข้อมูลสำเร็จ !!');
        if(Auth::user()->position == 0){
           return redirect('/Notice');
        }
        return back()->with('massage', 'บันทึกข้อมูลเรียบร้อยแล้ว');

       // return redirect('/Notice');
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
        //dd($id);
        $notice = Notices::where('id','=',$id)->get();
        $data = array(
            "notice"=>$notice,
        );
       // dd($data);
        return view('Notice.edit',$data);
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
    //dd($request);
        $this->validate($request,[
            'notice_text'=>'required',
            'notice_detail'=>'required',
            'notice_file' => 'mimes:csv,gif,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,zip,rar|max:12048'
           
        ]);

        $fileupload = $request->file('notice_file');
        if($fileupload){

           $fileupload_filename = time().'_'.$fileupload->getClientOriginalName();
           $fileupload_filepath = $fileupload->move(public_path("upload"), $fileupload_filename);
         
           // return($fileupload_filename.'=='.$fileupload_filepath);
       }else{
            $fileupload_filename='';
        }

       // dd($fileupload);

        $notice= Notices::find($id);
        
        $notice->notice_text = $request->notice_text;
   //     $notice->notice_type = $request->notice_type;
        $notice->notice_detail = $request->notice_detail;
        $notice->notice_file = $fileupload_filename;
    
    
    
        $notice->save();
        Session::flash('massage','Data was update successfull!!');
        return redirect('Notice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Notices::find($id);
        $data->delete();
       // addstudent::where('school',$id)->delete();
        Session::flash('massage','ลบข้อมูลเรียบร้อยแล้ว..');
        return redirect(url()->previous());
    }
}
