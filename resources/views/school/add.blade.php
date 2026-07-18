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

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">เครือข่ายสถานศึกษา</label>
                            <select name="school_group" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700">
                                @foreach($schoolgroup as $i)
                                    <option value="{{$i->id}}">{{$i->schoolgroup_name}} ({{$i->schoolgroup_detail}})</option>
                                @endforeach
                            </select>
                            @if ($errors->has('school_group'))
                                <p class="text-red-500 text-xs mt-1 font-bold italic">* {{ $errors->first('school_group') }}</p>
                            @endif
                        </div>

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
                                            {{$l->getschoolgroup->schoolgroup_name}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if(officerchk())
                                        <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                            <a href="{{'school/'.$l->id.'/edit?action=edit'}}" 
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