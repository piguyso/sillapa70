@extends('layouts.app')

@section('content')
<div class="container">

<div class="jumbotron">
<h1>{{session('app_name').' '.session('app_group')}}</h1>
<p>{{session('app_area')}}</p>
</div>


<div class="row">
        <div class="col-md-12">
    
            <div class="panel panel-default">
              <div class="panel-heading">ผลการแข่งขันตามโรงเรียน</div>
              <div class="panel-body">
                  <table class="table table-hover" id="">
                   <thead>
                     <tr>
                       <th>อันดับ</th>
                       <th>โรงเรียน</th>
                       
                       
                                
                       <th style="text-align: center"><img src='./imgs/gold.png' width='20px'> เหรียญทอง</th>
                       <th style="text-align: center"><img src='./imgs/silver.png' width='20px'> เหรียญเงิน</th>
                       <th style="text-align: center"><img src='./imgs/bronze.png' width='20px'> เหรียญทองแดง</th>
                       <th>รวม</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($dbrank as $l)
                     <tr>
                       <td>{{$num1}}</td>
                       <td><a href="showschooldetail/{{$l->school_name}}/0" target="_blank">{{$selectschool[$l->school_name]}}</a></td>
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
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                 
                   </tbody>
                
                 </table>
                 
              </div>
               <div class="panel-footer">
                <a href="./" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-info-sign"> กลับไปหน้าแรก</span>
                         </a></div>
          </div>
         
        </div> <!--- endof panel-->
                      </div> <!-- end row -->

 
    
	
</div>
@endsection
