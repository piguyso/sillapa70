@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

          @if(Session::has('massage'))
            <div class="alert alert-success">
                <strong>{{Session::get('massage')}}</strong>
            </div>
          @endif

            <div class="panel panel-default">
                <div class="panel-heading"> Edit subjectgroup</div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ Route('subjectgroup.update',$subjectgroup->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group{{ $errors->has('subjectgroup_name') ? ' has-error' : '' }}">
                            <label for="subjectgroup_name" class="col-md-4 control-label">ชื่อ</label>

                            <div class="col-md-6">
                                <input id="subjectgroup_name" type="text" class="form-control" name="subjectgroup_name" value="{{$subjectgroup->subjectgroup_name}}" >

                                @if ($errors->has('subjectgroup_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subjectgroup_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('subjectgroup_detail') ? ' has-error' : '' }}">
                            <label for="subjectgroup_detail" class="col-md-4 control-label">รายละเอียด</label>

                            <div class="col-md-6">
                                <input id="subjectgroup_detail" type="text" class="form-control" name="subjectgroup_detail" value="{{ $subjectgroup->subjectgroup_detail }}">

                                @if ($errors->has('subjectgroup_detail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subjectgroup_detail') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
