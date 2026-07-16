@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
           @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif
                  @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif
          <div class="panel panel-default">
              <div class="panel-heading">
                <a href="../centerviewactivityschool/{{$addschool[0]->school_activity}}" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span>
                </a> {{$addschool[0]->getschoolname->school_name}}->{{$addschool[0]->getactivityname->activity_name}}</div>
          
              <div class="panel-body">
              

                  <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>คำนำหน้า</th>
                       <th>ชื่อ</th>
                       <th>นามสกุล</th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $nm=1; ?>
                    @forelse($addstudent as $l)
                     <tr>
                       <td>{{$nm}}</td>
                       <td>{{$l->getstudenttitle->title}}</td>
                       <td>{{$l->name}}</td>
                       <td>{{$l->lastname}}</td>
                       <td>{{$l->getstudentactivity->activity_name}}</td>
                       <td>นักเรียน</td>
                       <td>
                      
                        
                        <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('addstudent.destroy',$l->id)}}">
                            <input type="hidden" name="_method" value="DELETE"/>
                            {!!csrf_field()!!}
                           </form>
                           <a href="{{'../centereditstudentname/'.$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"></span>
                         </a>
                          
                           
                      
                        </td>
                     </tr>
                     <?php $nm++; ?>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $addstudent->links()}}

<!--  teacher table-->
<table class="table table-hover">
                   <thead>
                     <tr>
                       <th>#</th>
                       <th>คำนำหน้า</th>
                       <th>ชื่อ</th>
                       <th>นามสกุล</th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $nm = 1 ?>
                    @forelse($addteacher as $l)
                     <tr>
                       <td>{{$nm}}</td>
                       <td>{{studenttitle($l->title)}}</td>
                       <td>{{$l->name}}</td>
                       <td>{{$l->lastname}}</td>
                       <td>{{$l->getstudentactivity->activity_name}}</td>
                       <td>ครูผู้ควบคุม</td>
                       <td>
                      
                        
                        <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('addstudent.destroy',$l->id)}}">
                            <input type="hidden" name="_method" value="DELETE"/>
                            {!!csrf_field()!!}
                           </form>
                           <a href="{{'../centereditstudentname/'.$l->id}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"></span>
                         </a>
                          
                        
                       
                        </td>
                     </tr>
                     <?php $nm++; ?>
                   @empty
                     <tr>
                       <td>ไม่พบข้อมูล</td>

                     </tr>
                   @endforelse
                   </tbody>
                 </table>
                 {{ $addteacher->links()}}

              </div>
          </div>



        </div>
    </div>
</div>
@endsection
