@extends('layouts.app')

@section('content')
<div class="container">
<h2>เลือกฐานข้อมูลต้นทาง</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('copydata.process') }}" method="POST">
        @csrf

        <label for="source_db" class="form-label">เลือกฐานข้อมูลต้นทาง:</label>
        <select name="source_db" id="source_db" class="form-select">
            @foreach($databaseList as $db)
                <option value="{{ $db }}">{{ $db }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary mt-3">คัดลอกข้อมูล</button>
    </form>





</div> <!-- container -->
@endsection

