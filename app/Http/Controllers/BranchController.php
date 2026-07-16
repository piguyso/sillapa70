<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use Session;
class BranchController extends Controller
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
        $branch = Branch::where('id','like','%')->paginate(1000);
        $data  = array(
           'branch'=>$branch,
        );

       // dd($data);
        return view('branch.addbranch',$data);
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
            'branch_name'=>'required',
            'branch_detail'=>'required',

        ]);
        $branch  = new branch;
        $branch->branch_name = $request->branch_name;
        $branch->branch_detail = $request->branch_detail;
        $branch->save();
        Session::flash('massage','Success Create User !!');
        return redirect('/branch');
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
        $branch = branch::find($id);
        $data = array(
            'branch'=>$branch,
        );
        return view('branch.edit',$data);
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
            'branch_name'=>'required',
            'branch_detail'=>'required',
          
        ]);
        $branch = branch::find($id);
        $branch->branch_name = $request->branch_name;
        $branch->branch_detail = $request->branch_detail;
        $branch->save();
        Session::flash('massage','Data update Successfull..');
        return redirect('branch');
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
        $branch=branch::find($id);
        $branch->delete();
        Session::flash('massage','branch has been deleted!!');
        return redirect('branch');
    }
}
