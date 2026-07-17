@extends('layouts.app')

@section('content')
<div class="container">

<div class="jumbotron">
  <h1>{{ session('app_name') ?: 'งานศิลปหัตถกรรมนักเรียนครั้งที่ 70' }}</h1>
  <p>{{ session('app_area') ?: 'สำนักงานเขตพื้นที่การศึกษาประถมศึกษาชุมพร เขต ๑' }}</p>
</div>


 
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            @if(Session::has('massage'))
              <div class="alert alert-success">{{Session::get('massage')}}</div>
            @endif
            <div class="panel panel-default">
              <div class="panel-heading">ระบบบันทึก</div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                ปิดระบบ
                
              </div>
          </div>
        </div>
    </div>
	
</div>
@endsection
