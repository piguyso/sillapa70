@extends('layouts.app')
@section('content')
<div>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
                          
                <div class="card">
                              <div class="card-header">
                                <h3 class="card-title">Notice | <a href="{{url('shownotice')}}">เพิ่มข้อมูล</a></h3>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                        
                                <table id="example1" class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                    <th>id</th>
                                    <th>หัวข้อ</th>
                                    <th>-</th>
                                    <th>-</th>
                                    <th>-</th>
                                    <th></th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                @forelse($notice as $l)
                                  <tr>
                                    <td>{{$l->id}}</td>
                                    <td>{{$l->notice_text}}</td>
                                    <td>{{$l->notice_type}}</td>
                                    <td>{!!$l->notice_detail!!}</td>
                                    <td>
                                    <a href="{{'Notice/'.$l->id.'/edit'}}" class="btn btn-info btn-ms">
                                          <span class="glyphicon glyphicon-pencil">EDIT</span>
                                        </a>
                                      
                                    </td>
                                    <td>
                                    <form id="delform{{$l->id}}" class="form-horizontal" method="post" action="{{ route('Notice.destroy',$l->id)}}">
                                          <input type="hidden" name="_method" value="DELETE"/>
                                          {!!csrf_field()!!}
                                    </form>
                                    <a href="Javascript:DelConfirm({{$l->id}})" class="btn btn-danger btn-ms">
                                          <span class="glyphicon glyphicon-trash">DEL</span>
                                    </a>
                                    </td>
                                  </tr>
                                @empty
                                @endforelse
                                  </tbody>
                                  <!--
                                  <tfoot>
                                  <tr>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>Platform(s)</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                  </tr>
                                  </tfoot>
                -->
                                </table>

                              </div>
                              <!-- /.card-body -->
                            </div>
                            <!-- /.card -->










                </div>
</div></div></div>
@endsection
