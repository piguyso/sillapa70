@extends('layouts.app')
@section('content')
<div>

 <!-- general form elements -->
 <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Add Data</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="POST" action="{{ Route('Notice.store') }}">
                {{ csrf_field() }}
                <div class="card-body">


                <div class="form-group{{ $errors->has('notice_text') ? ' has-error' : '' }}">
                            <label for="notice_text" class="col-md-4 control-label">เรื่อง</label>

                            <div class="col-md-6">
                                <input id="notice_text" type="text" class="form-control" name="notice_text" value="{{ old('notice_text') }}" >

                                @if ($errors->has('notice_text'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notice_text') }}</strong>
                                    </span>
                                @endif
                            </div>
                  </div>


                  <div class="form-group{{ $errors->has('notice_type') ? ' has-error' : '' }}">
                            <label for="notice_type" class="col-md-4 control-label">ชนิดของประกาศ</label>

                            <div class="col-md-6">
                              <select class="form-control" id="notice_type" name="notice_type">
                                
                               
									              <option value="1">ประกาศแบบแสดงรายละเอียด</option>
									              <option value="2">ประกาศแบบไม่แสดงรายละเอียด</option>
                                                  <option value="3">ข้อชี้แจง</option>
                              
                              </select>

                                @if ($errors->has('notice_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notice_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('notice_detail') ? ' has-error' : '' }}">
                            <label for="notice_detail" class="col-md-4 control-label">รายละเอียด</label>

                            <div class="col-md-6">
                            <textarea class="form-control" rows="3" id="notice_detail" placeholder="คำอธิบาย" name="notice_detail" ></textarea>



                                @if ($errors->has('notice_detail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notice_detail') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
              
                  
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">[ บันทึกข้อมูล ]</button>
                </div>
              </form>
            </div>
            <!-- /.card -->










</div>
@endsection
