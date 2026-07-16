@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-emerald-800 to-green-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none text-white">
                Staff Management
            </h1>
            <p class="mt-4 text-green-100 font-bold italic uppercase tracking-widest text-sm text-white">
                <i class="ri-user-settings-line mr-1 text-white"></i> จัดการรายชื่อกรรมการดำเนินงานประจำศูนย์การแข่งขัน
            </p>
        </div>
        <i class="ri-team-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm font-bold animate-fade-in italic text-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-xl text-emerald-500"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-slate-800">
        
        <div class="lg:col-span-1 text-slate-800">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10 text-slate-800">
                <div class="bg-emerald-900 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm text-white">
                        <i class="ri-user-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase italic leading-none text-white text-white">Add New Staff</h3>
                        <p class="text-green-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white leading-none text-white">เพิ่มชื่อกรรมการดำเนินงาน</p>
                    </div>
                </div>

                <div class="p-8 text-slate-800">
                    <form method="POST" action="{{ Route('jow.store') }}" class="space-y-6">
                        {{ csrf_field() }}
                        <input type="hidden" name="jow_type" value="1"/>
                        <input type="hidden" name="jowbrance2" value="{{Auth::user()->branch}}"/>

                        <div class="space-y-4 text-slate-800">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">ศูนย์การแข่งขัน</label>
                                <select name="jow_branch" class="w-full px-4 py-3 bg-emerald-50/30 border border-emerald-100 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-sm outline-none text-slate-800 shadow-inner">
                                    @foreach($jowbranch as $l)
                                        <option value="{{$l->id}}">{{$l->branch_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1 text-slate-800">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">คำนำหน้า</label>
                                <select name="title" class="w-full px-4 py-3 bg-emerald-50/30 border border-emerald-100 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-sm outline-none text-slate-800 shadow-inner">
                                    @foreach($title as $l)
                                        <option value="{{$l->title_id}}">{{$l->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1 text-slate-800">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">ชื่อ (Name)</label>
                                <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-sm outline-none text-slate-800 shadow-inner">
                            </div>

                            <div class="space-y-1 text-slate-800">
                                <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest italic">นามสกุล (Lastname)</label>
                                <input type="text" name="lastname" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-sm outline-none text-slate-800 shadow-inner">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-emerald-100 uppercase italic tracking-widest text-white">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 text-slate-800">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden text-slate-800">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-800">
                    <h2 class="text-base font-black text-emerald-800 uppercase italic tracking-wide leading-none text-emerald-800 text-slate-800">รายชื่อกรรมการดำเนินงาน</h2>
                    <a href="{{url('jow2registerform')}}/{{Auth::user()->branch}}" target="_blank" class="px-5 py-2.5 bg-green-700 text-white rounded-xl font-black text-[10px] hover:bg-green-800 transition-all shadow-lg shadow-green-100 uppercase italic tracking-widest text-white">
                        <i class="ri-printer-line mr-1 text-white"></i> ใบลงเวลากรรมการ
                    </a>
                </div>

                <div class="p-4 md:p-8 text-slate-800">
                    <div class="overflow-x-auto text-slate-800">
                        <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-800">
                                    <th class="px-6 py-2 text-center w-12 text-slate-400">#</th>
                                    <th class="px-6 py-2 text-slate-400">ชื่อ-นามสกุล</th>
                                    <th class="px-6 py-2 text-slate-400">ศูนย์การแข่งขัน</th>
                                    <th class="px-6 py-2 text-center text-slate-400">เกียรติบัตร</th>
                                    <th class="px-6 py-2 text-right text-slate-400">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-bold italic text-slate-800 text-slate-800">
                                @php $x = $jow->currentPage() == 1 ? 0 : $jow->perPage() * ($jow->currentPage() - 1); @endphp
                                @forelse($jow as $l)
                                @php $x++; @endphp
                                <tr class="bg-white hover:bg-emerald-50/30 transition-all border border-slate-100 shadow-sm rounded-2xl group text-slate-800">
                                    <td class="px-6 py-5 text-center text-slate-300 rounded-l-2xl border-l-4 border-transparent group-hover:border-emerald-500 text-slate-800">
                                        {{ $x }}.
                                    </td>
                                    <td class="px-6 py-5 text-slate-800 font-black text-slate-800">
                                        {{$l->getjowtitle->title}}{{$l->jow_name}} {{$l->jow_lastname}}
                                    </td>
                                    <td class="px-6 py-5 text-slate-400 font-medium text-slate-800 text-slate-800">
                                        {{$l->getjowbranch->branch_name}}
                                    </td>
                                    <td class="px-6 py-5 text-center text-slate-800">
                                        <div class="flex justify-center gap-1 text-slate-800">
                                            <a href="./printreportjow/{{$l->id}}" target="_blank" class="px-2 py-1 bg-slate-100 text-slate-500 rounded-md hover:bg-slate-800 hover:text-white transition-all text-[9px] text-slate-800">No BG</a>
                                            <a href="./printreportjow_showbg/{{$l->id}}" target="_blank" class="px-2 py-1 bg-emerald-100 text-emerald-600 rounded-md hover:bg-emerald-600 hover:text-white transition-all text-[9px] text-slate-800 text-white">Show BG</a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right rounded-r-2xl text-slate-800">
                                        <div class="flex justify-end gap-2 text-slate-800">
                                            <a href="{{'jow2/'.$l->id.'/edit'}}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            
                                            <form id="delform{{$l->id}}" method="POST" action="{{ route('jow.destroy', $l->id) }}" class="inline">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="button" onclick="DelConfirm({{$l->id}})" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="ri-trash-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-10 italic text-slate-300">ไม่พบรายชื่อกรรมการ</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 text-slate-800">
                        {{ $jow->links() }}
                    </div>
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
        if (confirm('คุณต้องการลบรายชื่อกรรมการท่านนี้ใช่หรือไม่?')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection