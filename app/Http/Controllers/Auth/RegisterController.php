<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\School;
use App\Schoolgroup;
use Illuminate\Http\Request;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function myreg(){
       $school = school::all();
       $schoolgroup = schoolgroup::all();
       $data = Array(
        'school'=>$school,
        'schoolgroups'=>$schoolgroup,
       );
       return view('Auth.myreg',$data);

      // dd($data);
    }
        // AJAX: ดึงข้อมูลโรงเรียนตามกลุ่มที่เลือก
    public function getSchoolsByGroup(Request $request)
    {
        $groupId = $request->input('schoolgroup_id');

        $schools = school::where('school_group', $groupId)
            ->orderBy('school_name', 'asc')  // แก้ชื่อคอลัมน์ให้ตรงกับของจริง
            ->get(['id', 'school_name']);    // แก้ชื่อคอลัมน์ให้ตรงกับของจริง

        return response()->json($schools);
    }
    protected function create(array $data)
    {
    //  dd($data);
    //  die();
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

    }
    // บันทึกข้อมูลลงทะเบียน
    public function storeMyreg(Request $request)
    {
        $request->validate([
            'username'        => 'required|string|max:255|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'schoolgroup_id'  => 'required|exists:schoolgroups,id',
           // 'school_id'       => 'required|exists:schools,id|unique:users,branch',
            'school_id'       => 'required|exists:schools,id|unique:users,school_id',
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'username.unique'   => 'ชื่อผู้ใช้นี้มีในระบบแล้ว',
            'school_id.unique'   => 'โรงเรียนนี้มีในระบบแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.confirmed'=> 'รหัสผ่านยืนยันไม่ตรงกัน',
            'schoolgroup_id.required' => 'กรุณาเลือกกลุ่มโรงเรียน',
            'school_id.required'      => 'กรุณาเลือกโรงเรียน',
            
        ]);

        $school = school::find($request->school_id);
       // dd($school->id);
        $user = new User;
        $user->email        = $request->username;
        $user->password        = bcrypt($request->password);
        $user->schoolgroup  = $request->schoolgroup_id;
        $user->branch       = $request->school_id;
        $user->school_id       = $request->school_id;
        $user->name = $school->school_name;
        $user->lastname = $school->school_detail;
        // ถ้าตาราง users มี field บังคับอื่น เช่น name, email ให้ใส่ด้วย
        // $user->name = $request->username;  // ตัวอย่าง
        $user->save();

        return redirect()->back()->with('success', 'ลงทะเบียนสำเร็จแล้ว');
    }
}
