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
                <div class="panel-heading"> Edit activity</div>

                <div class="panel-body">

                <form class="form-horizontal" method="POST" action="{{ Route('centersillapa.update',$activity->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="subjectgroupid" value="{{$activity->activity_subject}}"/>
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group{{ $errors->has('activity_name') ? ' has-error' : '' }}">
                            <label for="activity_name" class="col-md-2 control-label">ชื่อ</label>

                            <div class="col-md-6">
                                <input id="activity_name" type="text" class="form-control" name="activity_name" value="{{$activity->activity_name}}" readonly >

                                @if ($errors->has('activity_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
<!--
                        <div class="form-group{{ $errors->has('activity_subject') ? ' has-error' : '' }}">
                            <label for="activity_subject" class="col-md-2 control-label">กลุ่มสาระ</label>

                            <div class="col-md-6">
                              <select class="form-control" id="activity_subject" name="activity_subject">
                             
                                <option value="{{$activity->id}}">{{$activity->activity_name}}</option>
                               
                              </select>

                                @if ($errors->has('activity_subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
-->
                        <div class="form-group{{ $errors->has('activity_class') ? ' has-error' : '' }}">
                            <label for="activity_class" class="col-md-2 control-label">ช่วงชั้น</label>

                            <div class="col-md-3">
                              <select class="form-control" id="activity_class" name="activity_class" disabled>
                               
                               
                                @foreach($class as $i)
                                <option value="{{$i->id}}" @if($activity->activity_class == $i->id) selected @endif >{{$i->class_name}}</option>
                      
                              @endforeach

                              </select>

                                @if ($errors->has('activity_class'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_class') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="activity_studennumber" class="col-md-2 control-label">จำนวนนักเรียนที่เข้าร่วม</label>

                            <div class="col-md-3">
                              <select class="form-control" id="activity_studennumber" name="activity_studennumber" disabled>
                               
                                <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=50;$i++)
                                <option value="{{$i}}" @if($activity->activity_studennumber == $i) selected @endif>{{$i}}</option>
                     
                                @endfor
                              </select>

                                @if ($errors->has('	activity_studennumber'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('	activity_studennumber') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>




                        <div class="form-group{{ $errors->has('activity_teachernumber') ? ' has-error' : '' }}">
                            <label for="activity_teachernumber" class="col-md-2 control-label">จำนวนครูที่เข้าร่วม</label>

                            <div class="col-md-3">
                              <select class="form-control" id="activity_teachernumber" name="activity_teachernumber" disabled>
                               
                              <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}"@if($activity->activity_teachernumber == $i) selected @endif>{{$i}}</option>
                     
                                @endfor

                              </select>

                                @if ($errors->has('activity_teachernumber'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_teachernumber') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="activity_teamnumber" class="col-md-2 control-label">จำนวนทีมที่เข้ารอบ</label>
                              <div class="col-md-3">
                                <select class="form-control" id="activity_teamnumber" name="activity_teamnumber" disabled>
                                
                                <option value="999">ไม่จำกัดจำนวน </option>
                                  @for($i=1;$i<=5;$i++)
                                  <option value="{{$i}}"@if($activity->activity_teamnumber == $i) selected @endif>{{$i}}</option>
                      
                                  @endfor

                                </select>

                                  @if ($errors->has('activity_teamnumber'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('activity_teamnumber') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group{{ $errors->has('activity_schoolteamnumber') ? ' has-error' : '' }}">
                            <label for="activity_schoolteamnumber" class="col-md-2 control-label">จำนวนทีมต่อโรงเรียน</label>

                            <div class="col-md-3">
                              <select class="form-control" id="activity_schoolteamnumber" name="activity_schoolteamnumber" disabled>
                               
                              <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}"@if($activity->activity_schoolteamnumber == $i) selected @endif>{{$i}}</option>
                     
                                @endfor

                              </select>

                                @if ($errors->has('activity_schoolteamnumber'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_schoolteamnumber') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="activity_score" class="col-md-2 control-label">คะแนน</label>
                              <div class="col-md-3">
                                <select class="form-control" id="" name="activity_score" disabled>
                                
                               
                                  @for($i=100;$i<=100;$i++)
                                  <option value="{{$i}}"@if($activity->activity_score == $i) selected @endif>{{$i}}</option>
                      
                                  @endfor

                                </select>

                                  @if ($errors->has('activity_score'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('activity_score') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group{{ $errors->has('	activity_branch') ? ' has-error' : '' }}">
                            <label for="activity_branch" class="col-md-2 control-label">ศูนย์การแข่งขัน</label>

                            <div class="col-md-3">
                              <select class="form-control" id="activity_branch" name="activity_branch" disabled>
                               
                              
                               @foreach($branch as $i)
                                <option value="{{$i->id}}"@if($activity->activity_branch == $i->id) selected @endif >{{$i->branch_name}}</option>
                     
                                @endforeach

                              </select>

                                @if ($errors->has('	activity_branch'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('	activity_branch') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="activity_branchroom" class="col-md-2 control-label">สถานที่/อาคาร/ชั้น</label>
                              <div class="col-md-3">
                               
                              <input id="activity_branchroom" type="text" class="form-control" name="activity_branchroom" value="{{$activity->activity_branchroom}}" >


                                  @if ($errors->has('activity_branchroom'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('activity_branchroom') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>

                        <div class="form-group{{ $errors->has('activity_day') ? ' has-error' : '' }}">
                            <label for="activity_day" class="col-md-2 control-label">วันที่แข่งขัน</label>

                            <div class="col-md-3">
                          <!--  <input id="activity_day" type="text" class="form-control date" name="activity_day" value="{{$activity->activity_day }}" >

-->


                                <select class="form-control" id="activity_day" name="activity_day">
                                
                               
                                @foreach($dayofsillapa as $i)
                                <option value="{{$i->dayofsillapa}}" @if($i->dayofsillapa==$activity->activity_day) selected @endif>{{datethai($i->dayofsillapa)}}</option>
                    
                                @endforeach

                              </select>
                                @if ($errors->has('activity_day'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_day') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="activity_time" class="col-md-2 control-label">เวลาแข่งขัน</label>
                              <div class="col-md-3">
                               
                              <input id="activity_time" type="text" class="form-control" name="activity_time" value="{{$activity->activity_time }}" >


                                  @if ($errors->has('activity_time'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('activity_time') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group{{ $errors->has('activity_status') ? ' has-error' : '' }}">
                            <label for="activity_status" class="col-md-2 control-label">สถานะ</label>

                            <div class="col-md-3">


                                <select class="form-control" id="activity_status" name="activity_status" disabled>
                                
                               
                             
                                <option value="1" @if($activity->activity_status==1) selected @endif>ประกาศผลแล้ว</option>
                                <option value="0" @if($activity->activity_status==0) selected @endif>ยังไม่ได้ประกาศผล</option>
                    
                                

                              </select>
                                @if ($errors->has('activity_status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_status') }}</strong>
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
