@extends('layouts.app')

@section('content')
<div class="container">
<div class="jumbotron">
<h1>{{session('app_name').' '.session('app_group')}}</h1>
<p>{{session('app_area')}}</p>
</div>



    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading"><a href="../" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span>
                </a> 
           
                {{$activity[0]->getactivitybranch->branch_name ??''}}
             
              </div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                  <table class="table table-hover table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>ชื่อกิจกรรม</th>
                       <th>ช่วงชั้น</th>
                       <th>จำนวนทีม</th>
                       <th>{{$activity[0]->getactivitybranch->branch_name ??''}}</th>
                       <th>ประกาศผล</th>
                       <th></th>
             
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
                                              <h4 class="modal-title">{{$l->activity_name}}</h4>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            
                                            <!-- Modal body -->
                                            <div class="modal-body">
                                            
                                              <ul class="list-group">
                                                <li class="list-group-item list-group-item-success">ข้อมูลรายละเอียด</li>

                                                <li class="list-group-item list-group-item-secondary">
                                                  กลุ่มสาระการเรียนรู้ : {{$l->getactivitysubject->subjectgroup_name}}<br>
                                                  ศูนย์การแข่งขัน : {{$l->getactivitybranch->branch_name??''}}<br>
                                                  ระดับชั้น : {{$l->getclass->class_name}}<br>
                                                  จำนวนนักเรียน : {{$l->activity_studennumber}} คน<br>
                                                  จำนวนครูที่เข้าร่วม : {{$l->activity_teachernumber}} คน<br>
                                                  จำนวนทีมต่อโรงเรียน : {{$l->activity_schoolteamnumber}} ทีม<br>
                                              <!--    จำนวนทีมที่เข้ารอบ : {{$l->activity_teamnumber}} ทีม<br> -->
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
                       <td><a href="../viewactivitydetail/{{$l->id}}">{{$l->activity_name}}</a></td>
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
                       <!--
                     
                        -->
                        <td>
                        สถานที่ : {{$l->activity_branchroom}}<br>
                                                  วันที่แข่งขัน : {{Datethai($l->activity_day)}}<br>
                                                  เวลาแข่งขัน : {{$l->activity_time}}<br>
                        </td>
                       <td>{{getactivitystatus($l->activity_status)}} </td>
                       <td> 
                      
                         <a href="#" data-toggle="modal" data-target="#myModal{{$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-info-sign"></span>
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
          @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif
                        </div>
    </div>
</div>
@endsection
