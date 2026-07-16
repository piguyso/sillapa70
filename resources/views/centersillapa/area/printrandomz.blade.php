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
              <div class="panel-heading">
               
                <a href="../../" class="btn btn-info btn-md" onclick="">
                
                <span class="glyphicon glyphicon-backward"></span>
                </a> รายการแข่งขันที่มีทีมไม่เกิน 3 ทีม (ไม่มีการแข่งขัน)
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
                  <h4>รายชื่อกิจกรรมที่มี ทีมไม่เกิน 3 ทีม </h4>
                  <table class="table table-hover table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระ</th>
                       <th>รายการแข่งขัน</th>
                       <th>ระดับชั้น</th>
                       <th>จำนวนทีม</th>

                        <th>Print</th>
                     
                     </tr>
                   </thead>
                   <tbody>
         
    
                    
      @foreach($schoolcountactivity as $s)
        
      <tr>
                      <td></td>
                      <td>{{$s->getactivityname->getactivitysubject->subjectgroup_name}}</td>
                       <td><a href="{{url('/')}}/viewactivitydetail/{{$s->school_activity}}">{{$s->getactivityname->activity_name}}</a></td>
                       <td>{{$s->getactivityname->getclass->class_name}}</td>
                       <td>{{$s->count}} ทีม</td>
                      <td><a href="{{url('/')}}/showprintrandomz/{{$s->getactivityname->id}}" class="btn btn-info btn-md" target="_blank">
                           <span class="glyphicon glyphicon-print"> ใบจับฉลาก</span>
                         </a> </td>
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