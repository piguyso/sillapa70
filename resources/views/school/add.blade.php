@extends('layouts.app')

@section('content')
<style>
    #loading-overlay {
        @apply fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-500;
    }
    .spinner {
        @apply w-16 h-16 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin;
    }
</style>


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase text-white">
                School Management
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium italic">
                <i class="ri-government-line mr-2"></i>จัดการข้อมูลสถานศึกษาและเครือข่ายต้นสังกัด
            </p>
        </div>
        <i class="ri-building-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-emerald-600 px-8 py-6 text-white text-center">
                    <h2 class="text-xl font-bold flex items-center justify-center gap-2 italic uppercase">
                        <i class="ri-add-circle-line"></i> Add New School
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ Route('school.store') }}" class="space-y-6">
                        {{ csrf_field() }}
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">ชื่อโรงเรียน</label>
                            <input type="text" name="school_name" value="{{ old('school_name') }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                   placeholder="เช่น โรงเรียนพญาไท...">
                            @if ($errors->has('school_name'))
                                <p class="text-red-500 text-xs mt-1 font-bold italic">* {{ $errors->first('school_name') }}</p>
                            @endif
                        </div>

                        @include('school._networks', ['school' => new \App\School, 'schoolgroups' => $schoolgroup])

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">ข้อมูลเพิ่มเติม</label>
                            <input type="text" name="school_detail" value="{{ old('school_detail') }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                   placeholder="เช่น ที่ตั้งโรงเรียน...">
                            @if ($errors->has('school_detail'))
                                <p class="text-red-500 text-xs mt-1 font-bold italic">* {{ $errors->first('school_detail') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-100 transition-all transform active:scale-95 flex items-center justify-center gap-2 text-lg italic uppercase tracking-widest">
                            <i class="ri-save-3-line text-xl"></i> บันทึกข้อมูล
                        </button>
                    </form>

                    <div class="mt-8 pt-8 border-t border-gray-100 space-y-4">
                        <div>
                            <h3 class="font-black text-gray-800 flex items-center gap-2">
                                <i class="ri-file-excel-2-line text-emerald-600"></i> นำเข้าโรงเรียนจาก Excel
                            </h3>
                            <p class="mt-1 text-xs text-gray-500">ระบบจะตรวจสอบข้อมูลทั้งหมดก่อนนำเข้าฐานข้อมูล</p>
                        </div>

                        <a href="{{ route('school.import.template') }}"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100 transition-all">
                            <i class="ri-download-2-line"></i> ดาวน์โหลดเทมเพลต Excel
                        </a>

                        <form method="POST" action="{{ route('school.import.inspect') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="file" name="school_file" accept=".xlsx,.xls" required
                                   class="block w-full text-xs text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-bold file:text-slate-700 hover:file:bg-slate-200">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-700 text-white font-bold hover:bg-slate-800 transition-all">
                                <i class="ri-shield-check-line"></i> ตรวจสอบไฟล์ก่อนนำเข้า
                            </button>
                        </form>

                        @error('school_file')
                            <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm font-bold text-red-700">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                        <i class="ri-list-settings-line text-emerald-600"></i> School Directory
                    </h2>
                </div>
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3" id="table_schools">
                            <thead>
                                <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                    <th class="px-6 py-2 text-center w-16">ID</th>
                                    <th class="px-6 py-2">ข้อมูลโรงเรียน</th>
                                    <th class="px-6 py-2">เครือข่ายต้นสังกัด</th>
                                    <th class="px-6 py-2 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($data as $l)
                                <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                    <td class="px-6 py-6 text-center font-bold text-gray-300 border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">
                                        {{$l->id}}
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="font-black text-gray-800 text-base leading-tight uppercase italic tracking-tight group-hover:text-emerald-700 transition-colors">
                                            {{$l->school_name}}
                                        </div>
                                        <div class="text-xs text-gray-400 font-bold mt-1 flex items-center gap-1">
                                            <i class="ri-information-line"></i> {{$l->school_detail ?: 'ไม่มีรายละเอียด'}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black border border-emerald-100 uppercase italic">
                                            {{ $l->schoolgroups->pluck("schoolgroup_name")->implode(", ") ?: optional($l->getschoolgroup)->schoolgroup_name }}
                                        </span>
                                        @if($l->is_expansion)<span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-full text-[10px] font-black border border-amber-100">ขยายโอกาส</span>@endif
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if(officerchk())
                                        <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ url('school/'.$l->id.'/edit?action=edit') }}" 
                                               class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                               title="แก้ไข">
                                                <i class="ri-edit-2-line text-lg"></i>
                                            </a>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('school.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                {!!csrf_field()!!}
                                            </form>
                                            <button onclick="DelConfirm({{$l->id}})" 
                                                    class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm"
                                                    title="ลบ">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('school_import_result'))
        @php
            $importResult = session('school_import_result');
            $importHasErrors = count($importResult['errors']) > 0 || collect($importResult['rows'])->contains(fn($row) => count($row['errors']) > 0);
            $importable = collect($importResult['rows'])->filter(fn($row) => empty($row['skip']))->count();
            $skipped = count($importResult['rows']) - $importable;
        @endphp
        <div class="mt-8 bg-white rounded-3xl shadow-sm border {{ $importHasErrors ? 'border-red-200' : 'border-emerald-200' }} overflow-hidden">
            <div class="px-6 py-5 {{ $importHasErrors ? 'bg-red-50 text-red-800' : 'bg-emerald-50 text-emerald-800' }}">
                <h2 class="font-black text-lg">ผลการตรวจสอบไฟล์: {{ count($importResult['rows']) }} รายการ</h2>
                <p class="text-sm mt-1">{{ $importHasErrors ? 'ยังนำเข้าไม่ได้ กรุณาแก้ไขข้อผิดพลาดแล้วอัปโหลดใหม่' : ($importable > 0 ? 'พร้อมนำเข้า '.$importable.' โรงเรียน' : 'ไม่มีโรงเรียนใหม่ให้นำเข้า').($skipped ? ' (ข้ามแถวที่ซ้ำ '.$skipped.' แถว)' : '') }}</p>
            </div>

            @foreach($importResult['errors'] as $error)
                <div class="mx-6 mt-4 rounded-xl bg-red-50 p-3 text-sm font-bold text-red-700">{{ $error }}</div>
            @endforeach

            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead><tr class="bg-slate-100 text-left"><th class="p-3">แถว</th><th class="p-3">โรงเรียน</th><th class="p-3">รายละเอียด</th><th class="p-3">เครือข่าย</th><th class="p-3">ผลตรวจ</th></tr></thead>
                    <tbody>
                    @foreach($importResult['rows'] as $row)
                        <tr class="border-b border-slate-100 align-top">
                            <td class="p-3">{{ $row['row'] }}</td>
                            <td class="p-3 font-bold">{{ $row['school_name'] }}</td>
                            <td class="p-3">{{ $row['school_detail'] }}</td>
                            <td class="p-3">{{ $row['schoolgroup_name'] ?: '-' }} ({{ $row['school_group'] ?: '-' }})
                                @if(!empty($row['extra_names']))<div class="text-xs text-slate-500">เพิ่มเติม: {{ $row['extra_names'] }}</div>@endif
                                @if(!empty($row['is_expansion']))<div class="text-xs text-amber-700 font-bold">ขยายโอกาส</div>@endif
                            </td>
                            <td class="p-3">
                                @if(!empty($row['skip']))
                                    <span class="font-bold text-amber-600">ข้าม: {{ $row['skip'] }}</span>
                                @elseif($row['errors'])
                                    <ul class="text-red-600 list-disc pl-4">@foreach($row['errors'] as $error)<li>{{ $error }}</li>@endforeach</ul>
                                @else
                                    <span class="font-bold text-emerald-600">ถูกต้อง</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if(!$importHasErrors && $importable > 0)
                <form method="POST" action="{{ route('school.import.store') }}" class="px-6 pb-6">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-5 py-4 text-white font-black hover:bg-emerald-700">
                        ยืนยันนำเข้า {{ $importable }} โรงเรียน
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_schools').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาชื่อโรงเรียน หรือเครือข่าย...",
                   "info": "แสดง _START_ - _END_ จากทั้งหมด _TOTAL_ โรงเรียน",
                   "infoEmpty": "ไม่พบข้อมูล",
                   "infoFiltered": "(กรองจาก _MAX_ โรงเรียน)",
                   "zeroRecords": "ไม่พบโรงเรียนที่ค้นหา",
                   "paginate": { "previous": "ก่อนหน้า", "next": "ถัดไป" },
                },
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove();
                    }, 500);
                }
            });
        }
    });

    function DelConfirm(id) {
        showCustomConfirm(
            'ยืนยันการลบข้อมูลโรงเรียน',
            'คุณต้องการลบข้อมูลโรงเรียนนี้ใช่หรือไม่? บัญชีผู้ใช้ที่ผูกกับโรงเรียนนี้อาจได้รับผลกระทบ การกระทำนี้ไม่สามารถย้อนกลับได้',
            function() {
                document.getElementById('delform' + id).submit();
            }
        );
    }
</script>
@endsection
