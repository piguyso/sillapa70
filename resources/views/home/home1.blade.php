@extends('layouts.app')

@section('content')
<div class="container">


<!-- slide -->

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
     <!-- <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>-->
    </ol>

    <!-- Wrapper for slides -->
   

 
<!-- end slide-->

<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading"></div>
              <div class="panel-body">
                <div class="container"><a href="https://www.obec.go.th/wp-content/uploads/2024/09/%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%81%E0%B8%B2%E0%B8%A8%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%81%E0%B8%82%E0%B9%88%E0%B8%87%E0%B8%82%E0%B8%B1%E0%B8%99%E0%B8%87%E0%B8%B2%E0%B8%99%E0%B8%A8%E0%B8%B4%E0%B8%A5%E0%B8%9B%E0%B8%AB%E0%B8%B1%E0%B8%95%E0%B8%96%E0%B8%81%E0%B8%A3%E0%B8%A3%E0%B8%A1%E0%B8%99%E0%B8%B1%E0%B8%81%E0%B9%80%E0%B8%A3%E0%B8%B5%E0%B8%A2%E0%B8%99-%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B9%89%E0%B8%87%E0%B8%97%E0%B8%B5%E0%B9%88-72-1.pdf" target="_blank">
              <img src="imgs/banner_top.png" class="img-responsive img-rounded" width="95%"></a>
                </div>
            </div> 
          </div>
        </div> <!--- endof panel-->
</div> <!-- end row -->
<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading">ประกาศจากศูนย์อำนวยการการแข่งขัน</div>
              <div class="panel-body">
  
              @forelse($notice_1 as $b)
          <br><div class="btn btn-success btn-md">{{$b->notice_owner}}</div> <a href="notice_detail/{{$b->id}}"> [{{Datethai6($b->created_at)}}] {{$b->notice_text}}</a> 
          <?php 
          $to_time = strtotime(now());
          $from_time = strtotime($b->created_at);
        //  echo $to_time.' '.$b->updates_at;
         if(round(abs($to_time - $from_time) / 60,2)<= 180){
          echo "<img src='imgs/New2.gif' width='25px'>";
         };
          ?>
            @empty
           <br>   -  ไม่พบข้อมูล
            @endforelse
            </div> 
          </div>
          
        </div> <!--- endof panel-->
</div> <!-- end row -->

<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading">ประกาศจากศูนย์การแข่งขัน</div>
              <div class="panel-body">
              @forelse($notice_2 as $b)
          <br><div class="btn btn-primary btn-xs">{{$b->notice_owner}}</div> <a href="notice_detail/{{$b->id}}">[{{Datethai6($b->created_at)}}] {{$b->notice_text}}</a> 
          <?php 
          $to_time = strtotime(now());
          $from_time = strtotime($b->create_at);
        //  echo $to_time.' '.$b->updates_at;
         if(round(abs($to_time - $from_time) / 60,2)<= 180){
          echo "<img src='imgs/New2.gif' width='25px'>";
         };
          ?>
            @empty
           <br>   -  ไม่พบข้อมูล
            @endforelse
           
            </div>
              
          </div>
          
        </div> <!--- endof panel-->
</div> <!-- end row -->


<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading">ผลการแข่งขันล่าสุด</div>
              <div class="panel-body">
  <!--
             <a href="https://www.cpn1.go.th/2021/?cat=295" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> ข้อมูลการแข่งขัน{{ session('app_name') ?: 'งานศิลปหัตถกรรมนักเรียนครั้งที่ 70' }} {{ session('app_area') ?: 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑' }} </a><br>
             <a href="https://south70.sillapa.net/" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> เว็บการแข่งขันระดับชาติ(ภาคใต้) </a><br>
             
             
             
             <hr>
             <a href="downloads/allarea.rar" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> สรุปผลตัวแทนระดับภาครายเครือข่ายไฟล์ excel </a></a><img src="imgs/New2.gif" width='40px'><br>
              
             <a href="downloads/cpn1_pla.pdf" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> แผนผังสนามแข่งขันสำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑ </a></a><img src="imgs/New2.gif" width='40px'><br>
              
             
             <a href="downloads/watsuban_pla.JPG" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> แผนผังสนามแข่งขันโรงเรียนอนุบาลเมืองชุมพร วัดสุบรรณนิมิตร </a><img src="imgs/New2.gif" width='40px'><br>
             <a href="downloads/watsuban_com_os.pdf" target="_blank"> <span class="glyphicon glyphicon-circle-arrow-right"></span> ประกาศข้อมูล Spec Computer สำหรับใช้ในการแข่งขัน โรงเรียนอนุบาลเมืองชุมพร วัดสุบรรณนิมิตร </a></a><img src="imgs/New2.gif" width='40px'><br>
              
            <hr>
-->
          
            @forelse($firstactivity as $b)
          <br> - <a href="viewactivitydetail/{{$b->id}}">{{$b->activity_name}} {{$b->getactivitysubject->subjectgroup_name}} -> {{$b->getactivitybranch->branch_name}}</a>
          <?php 
          $to_time = strtotime(now());
          $from_time = strtotime($b->updated_at);
        //  echo $to_time.' '.$b->updates_at;
         if(round(abs($to_time - $from_time) / 60,2)<= 10){
          echo "<img src='imgs/New2.gif' width='25px'>";
         };
          ?>
            @empty
           <br>   -  ไม่พบข้อมูล
            @endforelse
    
            </div>
              
          </div>
          
        </div> <!--- endof panel-->
                      </div> <!-- end row -->




<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading">สรุปเหรียญการแข่งขันรายโรงเรียน TOP 10</div>
              <div class="panel-body">
              <table class="table table-hover table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>อันดับ</th>
                       <th>โรงเรียน</th>
                       
                       
                       <th style="text-align: center"><span class='glyphicon glyphicon-certificate' style='color:gold;text-align: center'></span> เหรียญทอง</th>
                       <th style="text-align: center"><span class='glyphicon glyphicon-certificate' style='color:gray;text-align: center'></span> เหรียญเงิน</th>
                       <th style="text-align: center"><span class='glyphicon glyphicon-certificate' style='color:brown;text-align: center'></span> เหรียญทองแดง</th>
                       <th>รวม</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($dbrank as $l)
                     <tr>
                       <td>{{$num1}}</td>
                       <td><a href="showschooldetail/{{$l->school_name}}/0">{{$selectschool[$l->school_name]}}</a></td>
                       <td style="text-align: center">
                       
                       {{$l->Gold}}

                       </td>
                       <td style="text-align: center">
                       {{$l->Silver}}
                    
                       </td>
                       <td style="text-align: center">{{$l->Bronce}}</td>
                       <td>
                   
                        {{$l->Gold+$l->Silver+$l->Bronce}}
                   
                       </td>
                     </tr>
                     <?php $num1++; ?>
                   @empty
             
                   @endforelse
                 
                   </tbody>
                
                 </table>
                 
              </div>
               <div class="panel-footer">
                <a href="./viewall" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-info-sign"> ลำดับผลการแข่งขันทั้งหมด</span>
                         </a></div>
          </div>
         
        </div> <!--- endof panel-->
                      </div> <!-- end row -->

 
    <div class="row">
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">ผลการแข่งขันตามกลุ่มสาระ</div>
              <div class="panel-body">
                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                       
                       <th>จำนวนรายการ</th>
                       <th>ประกาศผลแล้ว</th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td>
                       <td><a href="./viewactivity/{{$l->id}}">{{$l->subjectgroup_name}}</a></td>
                       <td>
                        
                       
                        
                        <?php
                        
                        

                          if(isset($activitycount[$l->id]))
                          {
                                 echo $activitycount[$l->id]." รายการ ";
                          }else{
                                  echo "0 รายการ ";
                         }
                        $num1++;
                          
                       ?>
                       </td>
                       <td>
                           
                       <?php
                        
                        

                        if(isset($activityfinish[$l->id]))
                        {
                               echo $activityfinish[$l->id]." รายการ ";
                        }else{
                                echo "0 รายการ ";
                       }
                    
                        
                     ?>
                       </td>
                       <td></td>
                       <td>
                   

                   
                       </td>
                     </tr>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $subjectgroup->links()}}
              </div>
          </div>
        </div> <!--- endof panel-->

        <div class="col-md-6">    
              <div class="panel panel-default">
              <div class="panel-heading">ตรวจสอบรายชื่อ/ผลการแข่งขันรายโรงเรียน</div>
              <div class="panel-body">
              <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>เครือข่ายสถานศึกษา</th>
                       
                       
                       <th>จำนวนสถานศึกษาทั้งหมด</th>
                       <th></th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num = 1; ?>
                    @forelse($schoolgroup as $l)
                     <tr>
                       <td>{{$num}}</td>
                       <td><a href="showschool/{{$l->id}}">เครือข่ายสถานศึกษา{{$l->schoolgroup_name}}</a></td>
                       <td>
                        
                       
                        
                        <?php
                        
                        

                          if(isset($schoolgroupcount[$l->id]))
                          {
                                 echo $schoolgroupcount[$l->id]." โรงเรียน ";
                          }else{
                                  echo "0 โรงเรียน ";
                         }
                        $num++;
                          
                       ?>
                       </td>
                       <td>
                           
                       <?php
                        
                        

                      //  if(isset($activityfinish[$l->id]))
                     //   {
                     //          echo $activityfinish[$l->id]." รายการ ";
                      //  }else{
                     //           echo "0 รายการ ";
                     //  }
                     // $num++;
                        
                     ?>
                       </td>
                       <td></td>
                       <td>
                   

                   
                       </td>
                     </tr>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $schoolgroup->links()}}

              </div> <!-- end body-->
              </div><!--end panel -->
        </div>







        <div class="col-md-6">    
              <div class="panel panel-default">
              <div class="panel-heading">ศูนย์การแข่งขัน</div>
              <div class="panel-body">
              <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>ศูนย์การแข่งขัน</th>
                       
                       
                       <th>จำนวนกิจกรรม</th>
                       <th></th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num = 1; ?>
                    @forelse($Branch as $l)
                     <tr>
                       <td>{{$num}}</td>
                       <td><a href="viewbranch/{{$l->id}}">{{$l->branch_name}}</a></td>
                       <td>
                        
                       
                        
                        <?php
                        
                        

                          if(isset($branchactivitycount[$l->id]))
                          {
                                 echo $branchactivitycount[$l->id]." รายการ ";
                          }else{
                                  echo "0 รายการ ";
                         }
                        $num++;
                          
                       ?>
                       </td>
                       <td>
                           
                       <?php
                        
                        

                      //  if(isset($activityfinish[$l->id]))
                     //   {
                     //          echo $activityfinish[$l->id]." รายการ ";
                      //  }else{
                     //           echo "0 รายการ ";
                     //  }
                     // $num++;
                        
                     ?>
                       </td>
                       <td></td>
                       <td>
                   

                   
                       </td>
                     </tr>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $schoolgroup->links()}}

              </div> <!-- end body-->
              </div><!--end panel -->
        </div>










    </div>
    <!-------  ------->
    
	

<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">สถิติการแข่งขัน งานศิลปหัตถกรรมนักเรียน</div>
              <div class="panel-body">
  

              <div class="col-md-3">
                 <div class="panel panel-default">
                   <div class="panel-heading" style="text-align:center;"><h4>จำนวนนักเรียน</h4></div>
                     <div class="panel-body"></div>
                        <h1 style="color:blue;text-align:center;font-size:70px;margin-top:-15px;">{{number_format($countstudent)}}</h0>
                  </div>
             </div> 

             <div class="col-md-3">
                 <div class="panel panel-default">
                   <div class="panel-heading" style="text-align:center;"><h4>จำนวนครู</h4></div>
                     <div class="panel-body"></div>
                        <h1 style="color:blue;text-align:center;font-size:70px;margin-top:-15px;">{{number_format($countteacher)}}</h0>
                  </div>
             </div> 

             <div class="col-md-3">
                 <div class="panel panel-default">
                   <div class="panel-heading" style="text-align:center;"><h4>จำนวนกรรมการตัดสิน</h4></div>
                     <div class="panel-body"></div>
                        <h1 style="color:blue;text-align:center;font-size:70px;margin-top:-15px;">{{number_format($countjow)}}</h0>
                  </div>
             </div> 

             <div class="col-md-3">
                 <div class="panel panel-default">
                   <div class="panel-heading" style="text-align:center;"><h4>จำนวนกรรมการดำเนินงาน</h4></div>
                     <div class="panel-body"></div>
                        <h1 style="color:blue;text-align:center;font-size:70px;margin-top:-15px;">{{number_format($countjow2)}}</h0>
                  </div>
             </div> 

                 
              </div>
               <div class="panel-footer">
      
              </div>
          </div>
         
        </div> <!--- endof panel-->
                      </div> <!-- end row -->


                        </div>

</div>
@endsection
่