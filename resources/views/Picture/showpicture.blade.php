@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
          <div class="panel panel-default">
              <div class="panel-heading">
                <a href="" class="btn btn-info btn-md" onclick="">
                <span class="glyphicon glyphicon-backward"></span> 
                </a> รูปภาพต่างๆ</div>
                <input type="hidden" name="jowid" value=""/>
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
                       <th>หัวข้อ</th>
                       <th>รายละเอียด</th>
                       <th>ไฟลแนบ</th>
                        <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     
                    @forelse($showpicture as $l)
                     <tr>
                       <td><span class="glyphicon glyphicon-option-vertical"></span>{{Datethai6($l->updated_at)}}</td>
                       <td>{{$l->notice_text}}</td>
                       <td>{{$l->notice_detail}}</td>
                       <td><a href="upload/{{$l->notice_file}}">
                        {{$l->notice_file}}</a></td>
                 
                       <td>
                       
                        
                        <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('Notice.destroy',$l->id)}}">
                            <input type="hidden" name="_method" value="DELETE"/>
                            {!!csrf_field()!!}
                           </form>
                           <!--
                           <a href="{{'jow/'.$l->id.'/edit'}}" class="btn btn-info btn-md">
                           <span class="glyphicon glyphicon-pencil"></span>
                         </a>
-->
                           <a href="Javascript:DelConfirm({{$l->id}})" class="btn btn-info btn-md">
                             <span class="glyphicon glyphicon-trash"></span>
                           </a>
                           
                       
                        </td>
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
          @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif

            <div class="panel panel-default">
                <div class="panel-heading"> เพิ่มรูปภาพ</div>

                <div class="panel-body">
                    

                      <!-- form start -->
              <form class="form-horizontal" method="POST" action="{{ Route('Notice.store') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">


                <div class="form-group{{ $errors->has('notice_text') ? ' has-error' : '' }}">
                            <label for="notice_text" class="col-md-4 control-label">เรื่อง</label>

                            <div class="col-md-6">
                                <input id="notice_text" type="text" class="form-control" name="notice_text" value="{{ old('notice_text') }}" >

                                @if ($errors->has('notice_text'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notice_text') }}</strong>
                                    </span>
                                @endif
                            </div>
                  </div>



                        <div class="form-group{{ $errors->has('notice_detail') ? ' has-error' : '' }}">
                            <label for="notice_detail" class="col-md-4 control-label">รายละเอียด</label>

                            <div class="col-md-6">
                            <textarea class="form-control" rows="3" id="notice_detail" placeholder="คำอธิบาย" name="notice_detail" ></textarea>



                                @if ($errors->has('notice_detail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notice_detail') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                            <label for="file" class="col-md-4 control-label">ไฟลแนบ</label>

                            <div class="col-md-6">
                            <input type="file" name="file" class="custom-file-input" id="chooseFile">

                                @if ($errors->has('file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
        
              
                  
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">[ บันทึกข้อมูล ]</button>
                </div>
              </form>




                </div><!-- ebd body -->
            </div>
        </div>
    </div>
</div>
@endsection
