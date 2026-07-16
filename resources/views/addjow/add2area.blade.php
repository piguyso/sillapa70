@extends('layouts.apparea')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<style>
    /* ปรับแต่ง DataTables ให้เข้ากับธีมส้ม-น้ำตาล */
    .dataTables_wrapper .dataTables_filter input {
        @apply bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all ml-2 font-bold;
    }
    .dataTables_wrapper .dataTables_length select {
        @apply bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 outline-none font-bold;
    }
    .dt-buttons .dt-button {
        @apply bg-amber-900 text-white border-none rounded-xl px-4 py-2 text-xs font-black uppercase italic hover:bg-orange-600 transition-all shadow-md mr-2 mb-4;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                จัดการกรรมการดำเนินงาน
            </h1>
        </div>
        <i class="ri-settings-5-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden mb-8">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-800">
                    <div class="flex items-center gap-4">
                        <a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center bg-white text-orange-600 rounded-xl border border-slate-200 hover:bg-orange-600 hover:text-white transition-all shadow-sm group">
                            <i class="ri-arrow-left-s-line text-2xl group-hover:-translate-x-1 transition-transform"></i>
                        </a>
                        <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none text-slate-800">รายชื่อกรรมการดำเนินการศูนย์ฯ</h2>
                    </div>
                    <a href="{{url('/area/jow2registerform')}}/{{Auth::user()->schoolgroup}}" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 text-white rounded-xl font-black text-xs hover:bg-red-700 transition-all shadow-lg shadow-red-100 uppercase italic text-white" target="_blank">
                        <i class="ri-printer-line text-white text-white"></i> ใบลงเวลากรรมการ
                    </a>
                </div>

                <div class="p-6 md:p-8">
                    <table id="jowTable" class="w-full text-left border-separate border-spacing-y-2">
                        <thead>
                            <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic">
                                <th class="px-4 py-2 text-center w-12 text-slate-400">#</th>
                                <th class="px-4 py-2 text-slate-400">ชื่อ-นามสกุล</th>
                                <th class="px-4 py-2 text-center text-slate-400">เกียรติบัตร</th>
                                <th class="px-4 py-2 text-right text-slate-400">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-bold italic">
                            @php $x = 0; @endphp
                            @forelse($jow as $l)
                            @php $x++; @endphp
                            <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group">
                                <td class="px-4 py-4 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500">
                                    {{ $x }}
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-slate-800 font-black italic uppercase leading-none">{{$l->getjowtitle->title}}{{$l->jow_name}} {{$l->jow_lastname}}</p>
                                    <span class="text-[9px] text-slate-400 mt-1 inline-block">Staff Member</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-1">
                                        <a href="./printreportjow/{{$l->id}}" class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-800 hover:text-white transition-all text-[8px] font-black uppercase italic" target="_blank">NO BG</a>
                                        <a href="./printreportjow_showbg/{{$l->id}}" class="px-2 py-1 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-600 hover:text-white transition-all text-[8px] font-black uppercase italic" target="_blank">WITH BG</a>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right rounded-r-2xl">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{'areajow2/'.$l->id.'/edit'}}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                            <i class="ri-edit-2-line"></i>
                                        </a>
                                        <button onclick="DelConfirm({{$l->id}})" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        <form id="delform{{$l->id}}" method="post" action="{{ route('areajow.destroy',$l->id)}}" class="hidden">
                                            <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-user-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase italic leading-none text-white">Add Staff</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white">เพิ่มชื่อกรรมการดำเนินงาน</p>
                    </div>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ Route('areajow.store') }}" class="space-y-6 font-bold italic">
                        {{ csrf_field() }}
                        <input type="hidden" name="jow_type" value="1"/>
                        <input type="hidden" name="jowbrance2" value="{{Auth::user()->branch}}"/>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">ศูนย์การแข่งขัน</label>
                            <select name="jow_branch" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                                <option value="{{Auth::user()->schoolgroup}}">เครือข่ายฯ {{Auth::user()->getschoolgroup->schoolgroup_name}}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">คำนำหน้า</label>
                            <select name="title" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-black text-slate-700">
                                @foreach($title as $l)
                                    <option value="{{$l->title_id}}">{{$l->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">ชื่อ (Name)</label>
                            <input type="text" name="name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic text-slate-800">นามสกุล (Lastname)</label>
                            <input type="text" name="lastname" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-700 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all">
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white mt-4">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>


    function DelConfirm(id) {
        if (confirm('⚠️ ยืนยันการลบรายชื่อกรรมการดำเนินงานท่านนี้?')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#jowTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 50,
                "deferRender": true, // ช่วยให้ Browser ไม่ค้างเวลาข้อมูลเยอะ
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-line mr-2"></i> ส่งออก Excel',
                        className: 'bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg border-none'
                    }
                ],
                "language": {
                    "search": "",
                    "searchPlaceholder": "🔍 ค้นหาข้อมูล...",
                    "processing": "กำลังประมวลผล...", // ข้อความขณะค้นหา/เปลี่ยนหน้า
                    "loadingRecords": "กำลังโหลดข้อมูล...", 
                },
                // เมื่อวาดตารางเสร็จ (ทุกอย่างพร้อม) ให้ปิด Overlay
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove(); // ลบทิ้งเพื่อไม่ให้ขวางหน้าจอ
                    }, 500);
                }
            });

            table.buttons().container().appendTo('#excel_tools_area');
        }
    });
</script>
@endsection