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
              <div class="panel-heading"><a href="#" onclick="history.back(); return false;" class="btn btn-info btn-md"> <span class="glyphicon glyphicon-backward"></span></a> {{$activity->getactivitysubject->subjectgroup_name ??''}}->{{$activity->activity_name ??''}} จำนวนนักเรียน {{$activity->activity_studennumber ??''}} คน,จำนวนครูผู้ควบคุม {{$activity->activity_teachernumber??''}} คน,จำนวนทีมต่อโรงเรียน {{$activity->activity_schoolteamnumber??''}} ทีม</div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                  <table class="table table-bordered" id="table1">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>เครือข่าย</th>
                       <th>ชื่อโรงเรียน</th>
                     
                       <th>ชื่อนักเรียน</th>
                       <th>ชื่อครูผู้ควบคุม</th>
                       <th>คะแนน</th>
                       <th>อันดับ</th>
                       <th>เหรียญ</th>
                       <th>พิมพ์เกียรติบัตร</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $x=0;?>
                    @forelse($addschool as $l)
                     <tr>
                       <td>
                        <?php 
                        $x++;
                        echo $x;
                       
                        ?>
                       </td>
                       <td>{{$l->getschoolgroup->schoolgroup_name??''}}</td>
                       <td>{{$l->getschoolname->school_name ??''}}</td>
                      
                       <td>
                      @forelse($student as $s)
                      
                      
                      <?php
                      $ii=1;
                      for($i=0;$i<count($s->getstudentname);$i++) {
                        if($l->id == $s->getstudentname[$i]->school){
                          if($s->getstudentname[$i]->type==0){
                           
                           echo $ii.'. '.$s->getstudentname[$i]->getstudenttitle->title.' '.$s->getstudentname[$i]->name.' '.$s->getstudentname[$i]->lastname."<br>";
                            $ii++;
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
                       $kk=1;
                      for($k=0;$k<count($s->getstudentname);$k++) {
                        if($l->id == $s->getstudentname[$k]->school){
                          if($s->getstudentname[$k]->type==1){
                            
                           echo $kk.'. '.$s->getstudentname[$k]->getstudenttitle->title.' '.$s->getstudentname[$k]->name.' '.$s->getstudentname[$k]->lastname."<br>";
                            $kk++;
                          }
                        }   
                      }
                      ?>
                      @empty
                      @endforelse
                       </td>
                       <td>
                
                      @if (($l->student_score ?? 0) > 0)
                        {{ $l->student_score }}
                      @else
                        -
                      @endif
                    
                      
                       </td>
                       <td>
           
            
                  
                      @if (($l->activity_rank ?? 0) > 0)
                        {{ $l->activity_rank }}
                      @else
                        -
                      @endif
                 
                      
                       </td>
                       <td>
            
                                @if (($l->student_score ?? 0) > 0)
                        {{getmedel($l->student_score ??'')}}
                      @else
                        -
                      @endif
                      
                    
                       </td>
                       <td>
                 
                  
                        @if($l->activity_status??'0' && $l->student_score > 0 ?? '0')
                        
                     
                         <form id="printform" class="form-horizontal" method="post" target="_blank" action="{{url('area/printPDF')}}">
                          <input type="hidden" name="_method" value="POST"/>
                          <input type="hidden" name="id" value="{{$l->id}}"/>
                          <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                          <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                          {!!csrf_field()!!}
                          <button type="submit" class="btn btn-info btn-md" ><span class="glyphicon glyphicon-print"> </span> ไม่มีพื้นหลัง
                              
                          </button><br>
                         </form>

                         <form id="printform" class="form-horizontal" method="post" target="_blank" action="{{url('area/printPDF2')}}">
                          <input type="hidden" name="_method" value="POST"/>
                          <input type="hidden" name="id" value="{{$l->id}}"/>
                          <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                          <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                          {!!csrf_field()!!}
                          <button type="submit" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-print"> </span> มีพื้นหลัง
                              
                          </button>
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
