@extends('layouts.apparea')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading">รายการแข่งขันตามสาระการเรียนรู้</div>

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
                       <th>สาระการเรียนรู้</th>
                       <th>รายการ</th>
                       <th>ประกาศผลแล้ว</th>
                       
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $x=1;?>
                    @forelse($activityinbranch as $l)
                     <tr>
                       <td>
                        <?php
                            echo $x;
                            $x++;
                        ?>
                       </td>
                       <td><a href="centerviewactivity/{{$l[0]->activity_subject}}">{{$l[0]->getactivitysubject->subjectgroup_name}}</a></td>
                       <td>
                        
                       
                        
                       <?php
                        
                                                      
                             // echo $activitycount;
                              if(isset($activitycount[$l[0]->activity_subject]))
                              {
                                    echo $activitycount[$l[0]->activity_subject]." รายการ ";
                              }else{
                                      echo "0 รายการ ";
                              }
                                                    
                        
                     ?>
                       </td>
                       <td>
                       <?php
                        
                                                      
                        // echo $activitycount;
                         if(isset($activityactivecount[$l[0]->activity_subject]))
                         {
                               echo $activityactivecount[$l[0]->activity_subject]." รายการ ";
                         }else{
                                 echo "0 รายการ ";
                         }
                                               
                   
                ?>

                       </td>
                      
                       <td>
                        
                        <a href="#" class="btn btn-info btn-md">
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
