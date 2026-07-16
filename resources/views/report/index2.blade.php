@extends('layouts.app')

@section('content')
<div class="container">

<div class="jumbotron">
  <h1 style="color:blue">{{config('app.header','test')}}</h1>
  <p>{{config('app.owner','test')}}</p>
</div>









 
    <div class="row">
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">เกียรติบัตรนักเรียน/ครู</div>
              <div class="panel-body">
                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                       
                       <th></th>
                       <th></th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="./viewactivity/{{$l->id}}">{{$l->subjectgroup_name}}</a></td>
                       <td>
                        
                       
            
                       </td>
                       <td>
                           
          
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
        <div class="row">
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">เกียรติบัตรกรรมการ</div>
              <div class="panel-body">
                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                       
                       <th></th>
                       <th></th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="./reportviewactivity/{{$l->id}}">{{$l->subjectgroup_name}}</a></td>
                       <td>
                        
                       
            
                       </td>
                       <td>
                           
          
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
        <div class="row">
        <div class="col-md-6">
    
            <div class="panel panel-default">
              <div class="panel-heading">เกียรติบัตรกรรมการ</div>
              <div class="panel-body">
                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>กลุ่มสาระการเรียนรู้</th>
                       
                       
                       <th></th>
                       <th></th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num1 = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num1}}</td> <?php $num1 = $num1+1; ?>
                       <td><a href="./reportviewactivity/{{$l->id}}">{{$l->subjectgroup_name}}</a></td>
                       <td>
                        
                       
            
                       </td>
                       <td>
                           
          
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

        

              
        </div>

    </div>
    <!-------  ------->
    
	
</div>
@endsection
