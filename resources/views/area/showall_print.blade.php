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
                </a> รายชื่อโรงเรียนตัวแทนเครือข่าย
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
                  <h4>รายชื่อโรงเรียนที่ได้อันดับ 1-3 ทุกรายการแข่งขัน </h4>
                  <table class="table table-hover table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>อันดับที่</th>
                       <th>กลุ่มสาระ</th>
                       <th>รายการแข่งขัน</th>
                       <th>ระดับชั้น</th>
                       <th>โรงเรียน</th>
                       <th>คำนำหน้า</th>
                       <th>ชื่อ นร.</th>
                       <th>นามสกุล นร.</th>
                       <th>คำนำหน้า</th>
                       <th>ชื่อ ครู</th>
                       <th>นามสกุล ครู</th>
       
                     
                     </tr>
                   </thead>
                   <tbody>
         
    
                    
      @foreach($student as $s)
                  @if($s->getaddschool->activity_rank == 1??'0'||$s->getaddschool->activity_rank == 2??'0'||$s->getaddschool->activity_rank == 3??'0')
      <tr>
                      <td>{{$s->getaddschool->activity_rank??''}}</td>
                      <td> {{$s->getstudentactivity->getactivitysubject->subjectgroup_name}}</td>
                       <td>{{$s->getstudentactivity->activity_name}}</td>
                       <td>{{$s->getstudentactivity->getclass->class_name}}</td>
                       <td>{{$s->getaddschool->getschoolname->school_name}}</td>
                       <td>
                        @if($s->type == 0)
                        {{$s->getstudenttitle->title}}
                        @endif
                      </td>
                      <td>
                        @if($s->type == 0)
                       {{$s->name}} 
                        @endif
                      </td>
                      <td>
                        @if($s->type == 0)
                        {{$s->lastname}}
                        @endif
                      </td>
                       <td>
                       @if($s->type == 1)
                        {{$s->getstudenttitle->title}}
                        @endif
                       </td>
                       <td>
                       @if($s->type == 1)
                         {{$s->name}} 
                        @endif
                       </td>
                       <td>
                       @if($s->type == 1)
                         {{$s->lastname}}
                        @endif
                       </td>
                     </tr>
        @endif
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
