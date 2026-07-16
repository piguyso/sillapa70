@extends('layouts.apparea')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-lg-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading"><a href="../centersillapa" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span>
                </a> บันทึกข้อมูลการแข่งขัน {{$subjectgroup->subjectgroup_name}} {{Auth::user()->name.' '.Auth::user()->lastname}}</div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                  <table class="table table-hover table-bordered">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>ชื่อกิจกรรม</th>
                       <th>ช่วงชั้น</th>
                       <th>จำนวนทีม</th>
                       <th>วันที่แข่งขัน</th>
                       <th>สถานที่</th>
                       <th>ประกาศผล</th>
                       <th>หมายเหตุ</th>
                     </tr>
                   </thead>
                   <tbody>
                   <?php 
                    
                    $page = $activity->currentPage();
                    if($activity->currentPage()==1){
                      $x=0;
                    }else{
                      $x = $activity->perPage()*($activity->currentPage()-1);
                    }


                    ?>
                    @forelse($activity as $l)

                       <!-- The Modal -->
                       <div class="modal fade" id="myModal{{$l->id}}">
                                        <div class="modal-dialog modal-lg">
                                          <div class="modal-content">
                                          
                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                              <h4 class="modal-title">ข้อมูลการแข่งขัน {{$l->activity_name}}</h4>
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
                                                  จำนวนทีมที่เข้ารอบ : {{$l->activity_teamnumber}} ทีม<br>
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
                       <td>
                       <?php
                          $x++;
                          echo $x.'.';
                          ?>
                      </td>
                       <td><a href="../centerviewactivityschool/{{$l->id}}">{{$l->activity_name}}</a></td>
                       <td>{{$l->getclass->class_name}}</td>
                       <td>
                        
                        <?php
                       if(isset($schoolcountactivity[$l->id]))
                          {
                                 echo $schoolcountactivity[$l->id]." ทีม ";
                          }else{
                                  echo "0 ทีม ";
                          }
                        
                          ?>

                      
                       </td>
                       <td>{{datethai($l->activity_day)}}</td>
                       <td>{{$l->activity_branchroom}} {{$l->activity_time}}</td>
                       <td>
                          {{getactivitystatus($l->activity_status)}}

                       </td>
                       <td> 
                      
                         <a href="#" data-toggle="modal" data-target="#myModal{{$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-info-sign"></span>
                         </a>
                         <a href="{{'../centereditactiviy/'.$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"> แก้ไข</span>
                         </a>
                         <a href="{{url('/')}}/showprintpointdoc/{{$l->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> ใบบันทึกคะแนน</span>
                        </a> 
                         <a href="../studentregisterform/{{$l->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> ใบรายงานตัวนักเรียน</span>
                         </a> 
                         <a href="../teacherregisterform/{{$l->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> ใบลงเวลาครู</span>
                         </a>
                         <a href="../jowregisterform/{{$l->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> ใบลงเวลากรรมการ</span>
                         </a>
                         <a href="../printjow_showbg/{{$l->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> เกียรติบัตรกรรมการ</span>
                         </a>
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
          
                        </div>
    </div>
</div>
@endsection
