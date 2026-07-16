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
                <div class="panel-heading"> Edit Branch</div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ Route('branch.update',$branch->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group{{ $errors->has('branch_name') ? ' has-error' : '' }}">
                            <label for="branch_name" class="col-md-4 control-label">ชื่อ</label>

                            <div class="col-md-6">
                                <input id="branch_name" type="text" class="form-control" name="branch_name" value="{{$branch->branch_name}}" >

                                @if ($errors->has('branch_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('branch_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('branch_detail') ? ' has-error' : '' }}">
                            <label for="branch_detail" class="col-md-4 control-label">รายละเอียด</label>

                            <div class="col-md-6">
                                <input id="branch_detail" type="text" class="form-control" name="branch_detail" value="{{ $branch->branch_detail }}">

                                @if ($errors->has('branch_detail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('branch_detail') }}</strong>
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
