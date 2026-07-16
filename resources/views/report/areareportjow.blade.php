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
              <div class="panel-heading"><a href="../report/{{session('area')}}" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span>
                </a> รายชื่อกรรมการดำเนินงาน </div>

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
                       <th>ชื่อ นามสกุล</th>
                     
                       
                       <th>
                        @if(!$jow->isEmpty())
                   <a href="{{url('area/printjow2')}}/{{$branch}}" class="btn btn-info btn-md" target='_black'> <span class="glyphicon glyphicon-print"> ทั้งหมด[ไม่มีพื้นหลัง]</span></a>
    <a href="{{url('area/printjow2_showbg')}}/{{$branch}}" class="btn btn-success btn-md" target='_black'> <span class="glyphicon glyphicon-print"> ทั้งหมด[มีพื้นหลัง]</span></a>
                      
                       @endif
                      </th>
                      

                     </tr>
                   </thead>
                   <tbody>
                   <?php 
                    $page = $jow->currentPage();
                    if($jow->currentPage()==1){
                      $x=0;
                    }else{
                      $x = $jow->perPage()*($jow->currentPage()-1);
                    }
                    ?>
                    @forelse($jow as $l)

            
                     <tr>
                       <td>
                       <?php
                        $x++;
                        echo $x.'.';
                        ?>
                      </td>
                       <td>{{$l->getjowtitle->title}} {{$l->jow_name}} {{$l->jow_lastname}}</td>

                   
                     
                       <td> 
                      
                       
                      <a href="../printreportjow/{{$l->id}}" class="btn btn-info btn-md" target='_black'> <span class="glyphicon glyphicon-print"> ไม่มีพื้นหลัง</span></a>
                       
                       <a href="../printreportjow_showbg/{{$l->id}}" class="btn btn-success btn-md" target='_black'> <span class="glyphicon glyphicon-print"> มีพื้นหลัง</span></a>
                       


                      
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
                 {{ $jow->links()}}
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
