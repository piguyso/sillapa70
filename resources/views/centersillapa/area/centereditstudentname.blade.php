@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

          @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif

            <div class="panel panel-default">
                <div class="panel-heading"> แก้ไขข้อมูลนักเรียน</div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ url('updatestudentname',$addstudent->id) }}">
                        {{ csrf_field() }}
                      
                        <input type="hidden" name="school" value="{{$addschool[0]->id}}">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">คำนำหน้า</label>

                            <div class="col-md-6">
                              <select class="form-control" id="title" name="title">
                                @foreach($title as $l)
                                <option value="{{$l->title_id}}" @if($addstudent->title==$l->title_id) selected @endif>{{$l->title}}</option>
                                @endforeach
                                <option value="1" @if($addstudent->title==1) selected @endif>เด็กชาย</option>
                                <option value="2" @if($addstudent->title==2) selected @endif>เด็กหญิง</option>
                                <option value="3" @if($addstudent->title==3) selected @endif>นาย</option>
                                <option value="4" @if($addstudent->title==4) selected @endif>นางสาว</option>
                                <option value="5" @if($addstudent->title==5) selected @endif>นาง</option>
                                <option value="6" @if($addstudent->title==6) selected @endif>สิบเอก</option>
                                <option value="12" @if($addstudent->title==12) selected @endif>จ.ส.ต.หญิง</option>
                                <option value="13" @if($addstudent->title==13) selected @endif>ด.ต.</option>
                                <option value="7" @if($addstudent->title==7) selected @endif>ว่าที่ร้อยตรี</option>
                                <option value="8" @if($addstudent->title==8) selected @endif>ว่าที่ร้อยตรีหญิง</option>
                                <option value="9" @if($addstudent->title==9) selected @endif>Mr.</option>
                                <option value="10" @if($addstudent->title==10) selected @endif>Mrs.</option>
                                <option value="11" @if($addstudent->title==11) selected @endif>Miss</option>
                              </select>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>






                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">ชื่อ</label>

                            <div class="col-md-6">
                                <input id="addstudent_name" type="text" class="form-control" name="name" value="{{$addstudent->name}}" >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">รายละเอียด</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $addstudent->lastname }}">

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
