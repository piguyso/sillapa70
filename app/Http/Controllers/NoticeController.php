<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notices;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'notice_detail', 'areanotice_detail']);
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function noticeupload(Request $request){
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:12048'
            ]);

            $file = $request->file('image');
            $filename = md5(uniqid(rand(), true)) . '.' . $file->getClientOriginalExtension();
            $year = active_year();
            $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');

            echo get_secure_file_url('upload', $filename, $year);
        } else {
            echo 'No file uploaded';
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
        $this->validate($request,[
            'notice_text'=>'required',
            'notice_detail'=>'required',
            'notice_file' => 'nullable',
            'notice_file.*' => 'mimes:csv,gif,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,zip,rar|max:12048'
        ]);

        $uploadedFilenames = [];
        if ($request->hasFile('notice_file')) {
            $fileUploads = $request->file('notice_file');
            $year = active_year();
            if (is_array($fileUploads)) {
                foreach ($fileUploads as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                    $uploadedFilenames[] = $filename;
                }
            } else {
                $filename = time() . '_' . $fileUploads->getClientOriginalName();
                $fileUploads->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                $uploadedFilenames[] = $filename;
            }
        }

        $notice = new Notices;
        $notice->notice_text = $request->notice_text;
        $notice->notice_detail = $request->notice_detail;
        $notice->notice_type = Auth::user()->position;
        $notice->notice_owner = Auth::user()->name.' '.Auth::user()->lastname;
        $notice->owner_id = Auth::user()->id;
        $notice->notice_file = empty($uploadedFilenames) ? '' : json_encode($uploadedFilenames, JSON_UNESCAPED_UNICODE);
        $notice->schoolgroup = Auth::user()->schoolgroup;
        $notice->save();
        Session::flash('massage','เพิ่มข้อมูลสำเร็จ !!');
        if(Auth::user()->position == 0){
           return redirect('/Notice');
        }
        return back()->with('massage', 'บันทึกข้อมูลเรียบร้อยแล้ว');
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
        $this->validate($request,[
            'notice_text'=>'required',
            'notice_detail'=>'required',
            'notice_file' => 'nullable',
            'notice_file.*' => 'mimes:csv,gif,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,zip,rar|max:12048'
        ]);

        $notice = Notices::findOrFail($id);
        $notice->notice_text = $request->notice_text;
        $notice->notice_detail = $request->notice_detail;

        if ($request->hasFile('notice_file')) {
            $fileUploads = $request->file('notice_file');
            $uploadedFilenames = [];
            $year = active_year();
            if (is_array($fileUploads)) {
                foreach ($fileUploads as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                    $uploadedFilenames[] = $filename;
                }
            } else {
                $filename = time() . '_' . $fileUploads->getClientOriginalName();
                $fileUploads->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                $uploadedFilenames[] = $filename;
            }
            $notice->notice_file = json_encode($uploadedFilenames, JSON_UNESCAPED_UNICODE);
        }

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
