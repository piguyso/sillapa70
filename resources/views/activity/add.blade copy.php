@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading">Dashboard</div>

              <div class="panel-body">

                                  <div class='col-lg-2 col-lg-offset-10'>

                    <div class="btn-group btn-group-sm">
                    @if(officerchk())
                    <input type="button" onclick="window.location.href='subjectgroup'" class='btn btn-sm btn-info' value="+เพิ่มรายการ/แก้ไขรายการ" >
                    @endif
                    
                    </div>

                    </div>






                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                  <table class="table table-hover" id="">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>ชื่อ</th>
                       <th>กลุ่มสาระ</th>
                       <th>ศูนย์การแข่งขัน</th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     
                    @forelse($activity as $l)

                                                          <!-- The Modal -->
                                      <div class="modal fade" id="myModal{{$l->id}}">
                                        <div class="modal-dialog modal-lg">
                                          <div class="modal-content">
                                          
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                              <h4 class="modal-title">{{$l->activity_name}}</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                            
                                              <ul class="list-group">
                                                <li class="list-group-item list-group-item-success">ข้อมูลรายละเอียด</li>

                                                <li class="list-group-item list-group-item-secondary">
                                                  กลุ่มสาระการเรียนรู้ : {{$l->getactivitysubject->subjectgroup_name}}<br>
                                                  ศูนย์การแข่งขัน : {{$l->getactivitybranch->branch_name}}<br>
                                                  ระดับชั้น : {{$l->getclass->class_name}}<br>
                                                  จำนวนนักเรียน : {{$l->activity_studennumber}} คน<br>
                                                  จำนวนครูที่เข้าร่วม : {{$l->activity_teachernumber}} คน<br>
                                                  จำนวนทีมต่อโรงเรียน : {{$l->activity_schoolteamnumber}} ทีม<br>
                                               <!--   จำนวนทีมที่เข้ารอบ : {{$l->activity_teamnumber}} ทีม<br> -->
                                                  คะแนน : {{$l->activity_score}} คะแนน<br>
                                                  สถานที่ : {{$l->activity_branchroom}}<br>
                                                  วันที่แข่งขัน : {{Datethai($l->activity_day)}}<br>
                                                  เวลาแข่งขัน : {{$l->activity_time}}<br>
                                                
                                                </li>
                                         </ul>





                                            </div>
                                            
                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                            
                                          </div>
                                        </div>
                                      </div>



                     <tr>
                       <td>{{$l->id}}</td>
                       <td><a href="#" data-toggle="modal" data-target="#myModal{{$l->id}}">{{$l->activity_name}}</a></td>
                       <td>{{$l->getactivitysubject->subjectgroup_name}}</td>
                       <td>{{$l->getactivitybranch->branch_name}}</td>
                       <td></td>
                       <td>
                         <!-- <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('myuser.destroy',$l->id)}}">
                           <input type="hidden" name="_method" value="DELETE"/>
                           {!!csrf_field()!!}
                          <a href="Javascript:DelConfirm({{$l->id}})">ลบ</a>
                          |
                          <a href='{{'myuser/'.$l->id.'/edit?action=edit'}}'>แก้ไขข้อมูล</a>
                        </form> -->

                        @if(officerchk())  <!-- 
                        <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('activity.destroy',$l->id)}}">
                          <input type="hidden" name="_method" value="DELETE"/>
                          {!!csrf_field()!!}
                         </form>
                         <a href="{{'addactivity/'.$l->activity_subject}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-plus"></span>
                         </a>
                      <a href="{{'activity/'.$l->id.'/edit?action=edit'}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"></span>
                         </a>
                         <a href="Javascript:DelConfirm({{$l->id}})" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-trash"></span>
                         </a> -->
                         @endif
                       </td>
                     </tr>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $activity->links()}}
              </div>
          </div>
          @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif
<!---
            <div class="panel panel-default">
                <div class="panel-heading"> เพิ่มรายการกิจกรรมการแข่งขัน</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ Route('activity.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('activity_name') ? ' has-error' : '' }}">
                            <label for="activity_name" class="col-md-2 control-label">ชื่อ</label>

                            <div class="col-md-6">
                                <input id="activity_name" type="text" class="form-control" name="activity_name" value="{{ old('activity_name') }}" >

                                @if ($errors->has('activity_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activity_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_group') ? ' has-error' : '' }}">
                            <label for="school_group" class="col-md-2 control-label">กลุ่มสาระ</label>

                            <div class="col-md-6">
                              <select class="form-control" id="school_group" name="school_group">
                               
                                <option value=""></option>
                        
                              
                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('school_group') ? ' has-error' : '' }}">
                            <label for="school_group" class="col-md-2 control-label">ช่วงชั้น</label>

                            <div class="col-md-3">
                              <select class="form-control" id="school_group" name="school_group">
                               
                                <option value="999">ไม่กำหนดช่วงชั้น </option>
                                <option value="1">ระดับชั้น ป.1-ป.3</option>
                                <option value="2">ระดับชั้น ป.4-ป.6</option>
                                <option value="3">ระดับชั้น ป.1-ป.6</option>
                                <option value="4">ระดับชั้น ป.1-ม.3</option>
                                <option value="5">ระดับชั้น ม.1-ม.3</option>
                             

                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="school_group" class="col-md-2 control-label">จำนวนนักเรียนที่เข้าร่วม</label>

                            <div class="col-md-3">
                              <select class="form-control" id="school_group" name="school_group">
                               
                                <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=50;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                     
                                @endfor
                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>




                        <div class="form-group{{ $errors->has('school_group') ? ' has-error' : '' }}">
                            <label for="school_group" class="col-md-2 control-label">จำนวนครูที่เข้าร่วม</label>

                            <div class="col-md-3">
                              <select class="form-control" id="school_group" name="school_group">
                               
                              <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                     
                                @endfor

                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="school_group" class="col-md-2 control-label">จำนวนทีมที่เข้ารอบ</label>
                              <div class="col-md-3">
                                <select class="form-control" id="school_group" name="school_group">
                                
                                <option value="999">ไม่จำกัดจำนวน </option>
                                  @for($i=1;$i<=5;$i++)
                                  <option value="{{$i}}">{{$i}}</option>
                      
                                  @endfor

                                </select>

                                  @if ($errors->has('school_group'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('school_group') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group{{ $errors->has('school_group') ? ' has-error' : '' }}">
                            <label for="school_group" class="col-md-2 control-label">จำนวนทีมต่อโรงเรียน</label>

                            <div class="col-md-3">
                              <select class="form-control" id="school_group" name="school_group">
                               
                              <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                     
                                @endfor

                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="school_group" class="col-md-2 control-label">คะแนนรวม</label>
                              <div class="col-md-3">
                                <select class="form-control" id="school_group" name="school_group">
                                
                                <option value="999">ไม่จำกัดจำนวน </option>
                                  @for($i=50;$i<=100;$i++)
                                  <option value="{{$i}}">{{$i}}</option>
                      
                                  @endfor

                                </select>

                                  @if ($errors->has('school_group'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('school_group') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group{{ $errors->has('school_group') ? ' has-error' : '' }}">
                            <label for="school_group" class="col-md-2 control-label">ศูนย์การแข่งขัน</label>

                            <div class="col-md-3">
                              <select class="form-control" id="school_group" name="school_group">
                               
                              <option value="999">ไม่จำกัดจำนวน </option>
                                @for($i=1;$i<=10;$i++)
                                <option value="{{$i}}">{{$i}}</option>
                     
                                @endfor

                              </select>

                                @if ($errors->has('school_group'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('school_group') }}</strong>
                                    </span>
                                @endif
                            </div>
                             <label for="school_group" class="col-md-2 control-label">สถานที่/อาคาร/ชั้น</label>
                              <div class="col-md-3">
                                <select class="form-control" id="school_group" name="school_group">
                                
                                <option value="999">ไม่จำกัดจำนวน </option>
                                  @for($i=50;$i<=100;$i++)
                                  <option value="{{$i}}">{{$i}}</option>
                      
                                  @endfor

                                </select>

                                  @if ($errors->has('school_group'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('school_group') }}</strong>
                                      </span>
                                  @endif
                              </div>
                            

                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    เพิ่มข้อมูล
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> -->
        </div>

    </div>
</div>
@endsection
