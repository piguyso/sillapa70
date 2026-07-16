@extends('layouts.app')

@section('content')
<div class="container">
<div class="jumbotron">
  <h1>{{config('app.header','test')}}</h1>
  <p>{{config('app.owner','test')}}</p>
</div>

    <div class="row">
        <div class="col-md-12 col-md-offset-0">

        @forelse($notice as $l)
        <div class="panel panel-default">
          <div class="panel-heading">
                <a href="" class="btn btn-info btn-md"> <span class="glyphicon glyphicon-backward"></span>   
            </a>
            {{$l->notice_text}}
          </div>

              <div class="panel-body">
              {{$l->notice_detail}}<br>
              <a href="../upload/{{$l->notice_file}}" target="_blank">
              {{$l->notice_file}}</a>

  
              </div>
          </div>
         @empty
         @endforelse

    
        </div>
    </div>
</div>
@endsection
