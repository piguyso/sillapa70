<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use Session;
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $class = classes::where('id','like','%')->paginate(10);
       // $subjectcount = 
        $data  = array(
           'class'=>$class,
        );

       // dd($data);
        return view('class.add',$data);
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
        $this->validate($request,[
            'class_name'=>'required',
            'class_detail'=>'required',

        ]);
        $class  = new classes;
        $class->class_name = $request->class_name;
        $class->class_detail = $request->class_detail;
        $class->save();
        Session::flash('massage','Success Create User !!');
        return redirect('/class');
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
        $class = classes::find($id);
        $data = array(
            'class'=>$class,
        );
        return view('class.edit',$data);
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
            'class_name'=>'required',
            'class_detail'=>'required',
          
        ]);
        $class = classes::find($id);
        $class->class_name = $request->class_name;
        $class->class_detail = $request->class_detail;
        $class->save();
        Session::flash('massage','Data update Successfull..');
        return redirect('class');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        adminchk();
        $class=classes::find($id);
        $class->delete();
        Session::flash('massage','class has been deleted!!');
        return redirect('class');
    }
}
