@extends('layouts.app')

@section('content')
<div class="container">

<div class="jumbotron">
  <h1>งานศิลปหัตถกรรมนักเรียนครั้งที่ 70</h1>
  <p>สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑</p>
</div>


 
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            @if(Session::has('massage'))
              <div class="alert alert-success">{{Session::get('massage')}}</div>
            @endif
            <div class="panel panel-default">
              <div class="panel-heading">ประกาศผลการแข่งขัน</div>

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
                       <th>กลุ่มสาระการเรียนรู้</th>
                       <th>จำนวนรายการ</th>
                       <th>จำนวนรายการที่ประกาศผลแล้ว</th>
                       <th></th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php $num = 1; ?>
                    @forelse($subjectgroup as $l)
                     <tr>
                       <td>{{$num}}</td>
                       <td><a href="#">{{$l->subjectgroup_name}}</a></td>
                       <td>
                        
                       
                        
                        <?php
                        
                        

                          if(isset($activitycount[$l->id]))
                          {
                                 echo $activitycount[$l->id]." รายการ ";
                          }else{
                                  echo "0 รายการ ";
                         }
                        $num++;
                          
                       ?>
                       </td>
                       <td></td>
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
        </div>
    </div>
	
</div>
@endsection
