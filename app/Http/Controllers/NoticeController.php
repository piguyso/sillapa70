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
     * ดึงประกาศที่ผู้ใช้ปัจจุบันมีสิทธิ์จัดการ (เจ้าของประกาศ หรือผู้ดูแลระบบ position 10)
     */
    private function findOwnedNotice($id)
    {
        $notice = Notices::findOrFail($id);
        $user = Auth::user();
        abort_unless(
            (int) $notice->owner_id === (int) $user->id || (int) $user->position === 10,
            403,
            'แก้ไขได้เฉพาะประกาศของตนเอง'
        );

        return $notice;
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function noticeupload(Request $request){
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:12048'
            ]);

            $file = $request->file('image');
            $filename = safe_upload_filename($file, false);
            $year = active_year();
            $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');

            echo get_secure_file_url('upload', $filename, $year);
        } else {
            echo 'No file uploaded';
        }
    }




    public function notice_detail(\Illuminate\Http\Request $request, $id){
        \App\Services\NoticeViewService::record($request, (int) $id, 'district');
        $notice = Notices::where('id','=',$id)->get();
        $data = Array(
            'notice'=>$notice,
        );
        return view('Notice.notice_detail',$data);
    }

   public function areanotice_detail(\Illuminate\Http\Request $request, $id){
        \App\Services\NoticeViewService::record($request, (int) $id, 'area');
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
            'viewTotals' => \App\Services\NoticeViewService::totals($shownotice->pluck('id')->all()),
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
        return view('Notice.adddata');
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
            'notice_file.*' => 'mimes:csv,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,webp,zip,rar|max:12048'
        ]);

        $uploadedFilenames = [];
        if ($request->hasFile('notice_file')) {
            $fileUploads = $request->file('notice_file');
            $year = active_year();
            if (is_array($fileUploads)) {
                foreach ($fileUploads as $file) {
                    $filename = safe_upload_filename($file);
                    $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                    $uploadedFilenames[] = $filename;
                }
            } else {
                $filename = safe_upload_filename($fileUploads);
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
    public function edit(Request $request, $id)
    {
        $notice = collect([$this->findOwnedNotice($id)]);
        $data = array(
            "notice"=>$notice,
            'from' => $request->query('from') === 'area' ? 'area' : 'district',
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
            'notice_file.*' => 'mimes:csv,docx,doc,txt,xlsx,xls,pdf,JPG,jpg,jpeg,png,webp,zip,rar|max:12048'
        ]);

        $notice = $this->findOwnedNotice($id);
        $notice->notice_text = $request->notice_text;
        $notice->notice_detail = $request->notice_detail;

        // ไฟล์เดิมที่ยังเก็บไว้ (ตัดไฟล์ที่ผู้ใช้ติ๊กลบ) + ไฟล์ใหม่ที่เพิ่ม
        $remove = (array) $request->input('remove_files', []);
        $files = array_values(array_filter($notice->files, fn ($f) => ! in_array($f, $remove, true)));

        if ($request->hasFile('notice_file')) {
            $year = active_year();
            foreach ((array) $request->file('notice_file') as $file) {
                $filename = safe_upload_filename($file);
                $file->storeAs(storage_year_path('upload', '', $year), $filename, 'public');
                $files[] = $filename;
            }
        }
        $notice->notice_file = $files === [] ? '' : json_encode($files, JSON_UNESCAPED_UNICODE);

        $notice->save();
        Session::flash('massage','แก้ไขประกาศเรียบร้อยแล้ว');
        return redirect($request->input('from') === 'area' ? url('area/shownotice') : url('shownotice'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->findOwnedNotice($id);
        $data->delete();
       // addstudent::where('school',$id)->delete();
        Session::flash('massage','ลบข้อมูลเรียบร้อยแล้ว..');
        return redirect(url()->previous());
    }
}
