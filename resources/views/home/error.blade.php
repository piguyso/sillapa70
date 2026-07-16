@extends('layouts.apparea')

@section('content')
<div class="container">

<div class="jumbotron">

</div>


 
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            @if(Session::has('massage'))
              <div class="alert alert-success">{{Session::get('massage')}}</div>
            @endif
            <div class="panel panel-default">
              <div class="panel-heading"></div>

              <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif

                คุณไม่สามารถใช้งานหน้านี้ได้
                
              </div>
          </div>
        </div>
    </div>
	
</div>
@endsection
