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
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">เกียรติบัตรนักเรียน/ครูผู้ควบคุม</div>
              <div class="panel-body">
              <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                     
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="{{url('area/viewactivity/'.$l->id??'')}}">{{$l->subjectgroup_name}}</a></td>
         
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
              <div class="panel-heading">เกียรติบัตรกรรมการตัดสิน</div>
              <div class="panel-body">
              <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                     
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="{{url('area/reportviewactivity/'.$l->id ??'')}}">{{$l->subjectgroup_name}}</a></td>
      
                     </tr>
                     
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                    
                   @endforelse
                   </tbody>
                 </table>
                 {{ $subjectgroup->links()}}
              </div> <!-- end body-->
              </div><!--end panel -->
        </div>

    </div>
    <!-------  end row------->
    <div class="row">
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">เกียรติบัตรกรรมการดำเนินงาน</div>
              <div class="panel-body">
              <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>เครือข่ายสถานศึกษา</th>
                       
                       
                  
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($area as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="{{url('area/reportviewjow/'.$l->id ??'')}}">{{$l->schoolgroup_name ??''}}</a></td>
          
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
        </div> <!--- endof panel-->

        <div class="col-md-6">    
              <div class="panel panel-default">
              <div class="panel-heading"></div>
              <div class="panel-body">
             
              </div> <!-- end body-->
              </div><!--end panel -->
        </div>

    </div>
    <!-------  end row-------> 
	
</div> <!-------  end container-------> 
@endsection
