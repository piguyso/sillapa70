@extends('layouts.apparea')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading"><a href="../addactivity/{{$activity->activity_subject}}" class="btn btn-info btn-md"> <span class="glyphicon glyphicon-backward"></span></a> {{$activity->getactivitysubject->subjectgroup_name}}->{{$activity->activity_name}} จำนวนนักเรียน {{$activity->activity_studennumber}} คน,จำนวนครูผู้ควบคุม {{$activity->activity_teachernumber}} คน,จำนวนทีมต่อโรงเรียน {{$activity->activity_schoolteamnumber}} ทีม</div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>ชื่อโรงเรียน</th>
                       <th>ชื่อเครือข่ายสถานศึกษา</th>
                       <th>ชื่อนักเรียน</th>
                       <th>ชื่อครูผู้ควบคุม</th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                   <?php 
                    
                    $page = $addschool->currentPage();
                    if($addschool->currentPage()==1){
                      $x=0;
                    }else{
                      $x = $addschool->perPage()*($addschool->currentPage()-1);
                    }


                    ?>
                    @forelse($addschool as $l)
                     <tr>
                       <td>
                       <?php
                          $x++;
                          echo $x.'.';
                          ?>
                      </td>
                       <td>{{$l->getschoolname->school_name}}</td>
                       <td>
                       {{$l->getschoolgroup->schoolgroup_name}}
                       
                     
                       </td>
                       <td>
                      @forelse($student as $s)
                      
                      
                      <?php
                      for($i=0;$i<count($s->getstudentname);$i++) {
                        if($l->id == $s->getstudentname[$i]->school){
                          if($s->getstudentname[$i]->type==0){
                           echo studenttitle($s->getstudentname[$i]->title).' '.$s->getstudentname[$i]->name.' '.$s->getstudentname[$i]->lastname."<br>";
                          }
                        }   
                      }
                      ?>
                      @empty
                      @endforelse
                       </td>
                       <td>
                       @forelse($student as $s)
                       <?php
                      for($k=0;$k<count($s->getstudentname);$k++) {
                        if($l->id == $s->getstudentname[$k]->school){
                          if($s->getstudentname[$k]->type==1){
                           echo studenttitle($s->getstudentname[$k]->title).' '.$s->getstudentname[$k]->name.' '.$s->getstudentname[$k]->lastname."<br>";
                          }
                        }   
                      }
                      ?>
                      @empty
                      @endforelse
                       </td>
                       <td>
                

                       @if(adminchk())

                       
                        <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('addschool.destroy',$l->id)}}">
                          <input type="hidden" name="_method" value="DELETE"/>
                          {!!csrf_field()!!}
                         </form>
                         <a href="../addaddstudent/{{$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-plus"></span>
                         </a>
                        
                         <a href="Javascript:DelConfirm({{$l->id}})" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-trash"></span>
                         </a>
                       
                      @endif

                      @if($l->school_schoolgroup==Auth::user()->schoolgroup)
                        
                      <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('addschool.destroy',$l->id)}}">
                          <input type="hidden" name="_method" value="DELETE"/>
                          {!!csrf_field()!!}
                         </form>
                     
                        
                        
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
                 {{ $addschool->links()}}
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
