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
                <a href="../" class="btn btn-info btn-md"> <span class="glyphicon glyphicon-backward"></span>   
            </a>
            {{$l->notice_text}}
          </div>

              <div class="panel-body">
              {!!$l->notice_detail!!}<br>
              <?php
              $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

              $explodeImage = explode('.',$l->notice_file);
              $extension = end($explodeImage);
              
              if(in_array($extension, $imageExtensions))
              {
              ?>
                 
              <img src="../upload/{{$l->notice_file}}" class="img-responsive img-rounded"><br>
              <?php
              }else
              {
                  // Is not image 
                  ?>
                  <embed src="../upload/{{$l->notice_file}}" width="100%" height="600px" type="application/pdf"> 
                  <?php
              }
              ?>
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
