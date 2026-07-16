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
                </a> สรุปผลการแข่งขัน
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
                  <h4>สรุปผลการแข่งขัน รางวัลชนะเลิศอันดับ ๑</h4>
                  <table class="table table-hover table-bordered">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระ</th>
                       <th>ชื่อรายการแข่งขัน</th>
                       <th>โรงเรียน</th>
                       <th>ชื่อนักเรียน</th>
                       <th>ชื่อครู</th>
                    <!--   <th>คะแนน</th>
                       <th>เหรียญ</th> -->
                     
                     </tr>
                   </thead>
                   <tbody>
                   <?php 
                    
                    $page = $addschool2->currentPage();
                    if($addschool2->currentPage()==1){
                      $x=0;
                    }else{
                      $x = $addschool2->perPage()*($addschool2->currentPage()-1);
                    }


                    ?>
                    @forelse($addschool2 as $l)
                    
                     <tr>
                       <td>
                       <?php
                          $x++;
                          echo $x.'.';
                          ?>
                      </td>
                      <td></td>
                       <td>{{$l->getactivityname->activity_name}}</td>
                       <td>{{$l->getschoolname->school_name}}</td>
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
                      $ii=1;
                      for($i=0;$i<count($s->getstudentname);$i++) {
                        if($l->id == $s->getstudentname[$i]->school){
                          if($s->getstudentname[$i]->type==1){
                           
                           echo $ii.'. '.$s->getstudentname[$i]->getstudenttitle->title.' '.$s->getstudentname[$i]->name.' '.$s->getstudentname[$i]->lastname."<br>";
                            $ii++;
                          }
                        }   
                      }
                      ?>
                      @empty
                      @endforelse
                        
                

                      
                       </td>
                       <!--
                       <td>@if($l->getactivityname->activity_status == 1)
                        {{$l->student_score}}
                        @else
                        -
                        @endif
                       </td>
                       <td>@if($l->getactivityname->activity_status == 1)
                         {{getmedel($l->student_score)}}
                         @else
                        -
                        @endif
                        </td>
                      
                        <td>
                        @if($l->getactivityname->activity_status == 1)
                        <form id="printform" class="form-horizontal" method="post" action="{{url('printPDF')}}">
                          <input type="hidden" name="_method" value="POST"/>
                          <input type="hidden" name="id" value="{{$l->id}}"/>
                          <input type="hidden" name="student_score" value="{{$l->student_score}}"/>
                          <input type="hidden" name="x" value="{{$l->activity_rank}}"/>
                          {!!csrf_field()!!}
                          <button type="submit" class="btn btn-info btn-md" ><span class="glyphicon glyphicon-print">เกียติบัตร</span>
                              
                          </button>
                         </form>
                          @endif
                        </td> -->
                     </tr>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                  
                   </tbody>
                 </table>
                 {{ $addschool2->links()}}

                
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
