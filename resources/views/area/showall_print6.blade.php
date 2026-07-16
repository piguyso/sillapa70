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
              <div class="panel-heading">
               
            <a href="{{url('area/'.session('area'))}}" class="btn btn-info btn-md" onclick="">
                
                <span class="glyphicon glyphicon-backward"></span>
                </a> รายการแข่งขันทั้งหมดที่มีการลงทะเบียน
                <?php 
             //   if(isset($addschool[0])){
              //    echo $addschool[0]->getschoolname->school_name;
            //    }
                ?>
              </div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif
                  <h4>รายชื่อกิจกรรม </h4>
                  <table class="table table-hover table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระ</th>
                       <th>รายการแข่งขัน</th>
                       <th>ระดับชั้น</th>
                       <th>จำนวนทีม</th>

       
                     
                     </tr>
                   </thead>
                   <tbody>
         
    
                    
      @foreach($schoolcountactivity as $s)
        
      <tr>
                      <td></td>
                      <td>{{$s->getactivityname->getactivitysubject->subjectgroup_name}}</td>
                       <td><a href="{{url('area/areaviewactivitydetail').'/'.$area.'/'.$s->school_activity}}">{{$s->getactivityname->activity_name}}</a></td>
                       <td>{{$s->getactivityname->getclass->class_name}}</td>
                       <td>{{$s->count}} ทีม</td>
       
                     </tr>
 
      @endforeach
  

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
<?php
/*
                      $ArrayData=[];
                      $ii=1;
                      for($i=0;$i<count($s->getstudentname);$i++) {   // วนลูปเอารายชื่อ นักเรียนมาใส่ใน Array
                        if($l->id == $s->getstudentname[$i]->school){
                          if($s->getstudentname[$i]->type==0){
                           
                            $ArrayData[$i + 1] = [
                              'student_title' => $s->getstudentname[$i]->getstudenttitle->title,
                              'student_name' => $s->getstudentname[$i]->name,
                              'student_lname' => $s->getstudentname[$i]->lastname,
                              ];
//  echo $ii.'. '.$s->getstudentname[$i]->getstudenttitle->title.' '.$s->getstudentname[$i]->name.' '.$s->getstudentname[$i]->lastname."<br>";
// echo $ArrayData[$i+1]['student_title'];
                            $ii++;
                          }
                        }   
                      }
                        
                      
                      
                       @foreach($addschool2 as $l)
                     <tr>
                      <td></td>
                      <td>{{$l->getactivityname->getactivitysubject->subjectgroup_name}}</td>
                       <td>{{$l->getactivityname->activity_name}}</td>
                       <td>{{$l->getschoolname->school_name}}</td>
                       <td>{{$s->getstudentname}}</td>
                       <td></td>
                     </tr>
                     @endforeach
                      
                      
                      
                      
                      
                      
                      
                      
                      */


                      ?>