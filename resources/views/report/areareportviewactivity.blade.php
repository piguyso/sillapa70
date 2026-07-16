@extends('layouts.apparea')

@section('content')
<div class="container">

<div class="jumbotron" >
  <h2> 
              @guest
                      
 
                       {{ session('app_name')}}ระดับเครือข่ายสถานศึกษา{{session()->get('schoolgroup')}} {{ 'ปีการศึกษา '. session('app_year') }}
                         
                     @else
                        
 
                          {{ session('app_name')}}ระดับเครือข่ายสถานศึกษา{{session()->get('schoolgroup')}} {{ 'ปีการศึกษา '. session('app_year') }}
                        
                     @endif

</h2>

</div>




    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading"><a href="../report/{{session('area')??'99'}}" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span>
                </a> รายชื่อกรรมการตัดสิน </div>

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
                       
                       <th>ชื่อกรรมการ</th>
                       <th>-</th>
                       <th>Print เกียรติบัตร</th>
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
                                            
                                                  ระดับชั้น : {{$l->getclass->class_name}}<br>
                                                  จำนวนนักเรียน : {{$l->activity_studennumber}} คน<br>
                                                  จำนวนครูที่เข้าร่วม : {{$l->activity_teachernumber}} คน<br>
                                                  จำนวนทีมต่อโรงเรียน : {{$l->activity_schoolteamnumber}} ทีม<br>
                                                  จำนวนทีมที่เข้ารอบ : {{$l->activity_teamnumber}} ทีม<br>
                                                  คะแนน : {{$l->activity_score}} คะแนน<br>
                                    
                                                
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
                       <td>{{$l->activity_name}}</td>
                       <td>{{$l->getclass->class_name}}</td>
                   
                       <td>
                      @foreach($jow as $i)
                          @if($i->jow_activity==$l->id)
                          {{$i->getjowtitle->title}}{{$i->jow_name}} {{$i->jow_lastname}} <br>
                          @endif
                      @endforeach
                       </td>
                       <td>
                          {{getactivitystatus($l->activity_status)}}

                       </td>
                       <td> 
                      
                       
                       <a href="../printjow/{{$l->id}}" class="btn btn-info btn-md" target='_black'> <span class="glyphicon glyphicon-print"> ไม่มีพื้นหลัง</span></a>
                  
                       <a href="../printjow_showbg/{{$l->id}}" class="btn btn-success btn-md" target='_black'> <span class="glyphicon glyphicon-print"> มีพื้นหลัง</span></a>
                       


                      
                         <!--
                         <a href="{{'../centereditactiviy/'.$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"></span> 
                         </a> -->
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
