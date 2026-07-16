@extends('layouts.app')

@section('content')
<div class="min-h-[90vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <div class="mx-auto h-16 w-16 bg-secondary rounded-full flex items-center justify-center shadow-lg mb-4">
            <i class="fas fa-user-plus text-3xl text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">
            ลงทะเบียนผู้ใช้งานใหม่
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            กรุณากรอกข้อมูลให้ครบถ้วนเพื่อเข้าใช้งานระบบ
        </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-xl sm:px-10 border border-gray-100">

            {{-- ข้อความสำเร็จ --}}
            @if(session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-4 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- แสดง Error --}}
            @if($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-4 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">เกิดข้อผิดพลาด</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('myreg.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้ (Username)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="username" id="username" required
                            class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border"
                            placeholder="ตั้งชื่อผู้ใช้งาน" value="{{ old('username') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน (Password)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border"
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-check-double text-gray-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border"
                            placeholder="กรอกรหัสผ่านอีกครั้ง">
                    </div>
                </div>

                <div class="border-t border-gray-100 my-4 pt-4">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">ข้อมูลสังกัด</h4>

                    {{-- School Group --}}
                    <div class="mb-4">
                        <label for="schoolgroup_id" class="block text-sm font-medium text-gray-700">กลุ่มโรงเรียน (School Group)</label>
                        <select name="schoolgroup_id" id="schoolgroup_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md border bg-white">
                            <option value="">-- เลือกกลุ่มโรงเรียน --</option>
                            @foreach($schoolgroups as $g)
                                <option value="{{ $g->id }}" {{ old('schoolgroup_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->schoolgroup_name ?? $g->name ?? 'ID: '.$g->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- School --}}
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700">โรงเรียน</label>
                        <select name="school_id" id="school_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md border bg-white disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="">-- กรุณาเลือกกลุ่มโรงเรียนก่อน --</option>
                        </select>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-secondary hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2 mt-1"></i> ลงทะเบียน
                    </button>
                    
                    <a href="{{ route('login') }}" class="mt-3 w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                        กลับไปหน้าเข้าสู่ระบบ
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Script เดิมของคุณ (ยังคงใช้ได้ปกติเพราะ ID ตรงกัน) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var groupSelect  = document.getElementById('schoolgroup_id');
    var schoolSelect = document.getElementById('school_id');

    groupSelect.addEventListener('change', function () {
        var groupId = this.value;
        // เพิ่ม Animation Loading เล็กน้อย
        schoolSelect.disabled = true;
        schoolSelect.innerHTML = '<option value="">กำลังโหลดข้อมูล...</option>';

        if (!groupId) {
            schoolSelect.innerHTML = '<option value="">-- กรุณาเลือกกลุ่มโรงเรียนก่อน --</option>';
            schoolSelect.disabled = true;
            return;
        }

        var url = './get-schools' + '?schoolgroup_id=' + encodeURIComponent(groupId);
        
        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            schoolSelect.innerHTML = '<option value="">-- เลือกโรงเรียน --</option>';
            schoolSelect.disabled = false; // ปลดล็อก

            data.forEach(function (item) {
                var opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.school_name || ('โรงเรียน ID: ' + item.id);
                schoolSelect.appendChild(opt);
            });
        })
        .catch(function(err) {
            console.error(err);
            schoolSelect.innerHTML = '<option value="">เกิดข้อผิดพลาดในการโหลดข้อมูล</option>';
            schoolSelect.disabled = false;
        });
    });
});
</script>
@endsection