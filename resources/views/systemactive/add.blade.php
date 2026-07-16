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



<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sarabun text-slate-800">

    <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                System Activation
            </h1>
            <p class="text-slate-300 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-shield-flash-line mr-2"></i>ควบคุมการเปิด-ปิดฟังก์ชันต่างๆ ของระบบ (Maintenance Mode)
            </p>
        </div>
        <i class="ri-toggle-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    @if(Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ Session::get('massage') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                <i class="ri-list-settings-line text-emerald-600"></i> Module Activation List
            </h2>
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="table_system_active">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">ID</th>
                            <th class="px-6 py-2">โมดูล/ฟังก์ชัน</th>
                            <th class="px-6 py-2">รายละเอียดเงื่อนไข</th>
                            <th class="px-6 py-2 text-center">สถานะปัจจุบัน</th>
                            <th class="px-6 py-2 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($systemactive as $l)
                        <tr class="group bg-white hover:bg-slate-50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center font-bold text-slate-300 italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">
                                {{$l->id}}
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-black text-gray-800 text-base leading-tight uppercase italic tracking-tight group-hover:text-emerald-700 transition-colors">
                                    {{$l->systemname}}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase italic">
                                    Code: SYS-ACT-0{{$l->id}}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-gray-500 font-medium italic">
                                {{$l->detail ?: 'ไม่มีรายละเอียดเพิ่มเติม'}}
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($l->status == 1)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-100 shadow-sm">
                                        <span class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></span> ปิดระบบ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm">
                                        <span class="w-2 h-2 bg-emerald-600 rounded-full animate-pulse"></span> เปิดระบบ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if(officerchk())
                                <div class="flex justify-center gap-2">
                                    <a href="{{'systemactive/'.$l->id.'/edit?action=edit'}}" 
                                       class="w-12 h-12 flex items-center justify-center bg-white text-slate-400 border border-slate-200 rounded-2xl hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-sm group/btn">
                                        <i class="ri-settings-4-line text-2xl group-hover/btn:rotate-90 transition-transform duration-500"></i>
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50 rounded-2xl">
                                <i class="ri-inbox-line text-4xl block mb-2"></i> ไม่พบข้อมูลโมดูลระบบ
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_system_active').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "dom": '<"flex justify-end mb-4"f>rt', // ซ่อน pagination เพราะข้อมูลมีน้อย
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาโมดูลระบบ...",
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
</script>
@endsection