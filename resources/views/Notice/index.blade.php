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
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase text-white">
                    Notice Management
                </h1>
                <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium italic">
                    <i class="ri-notification-3-line mr-2"></i>จัดการข่าวสารและประกาศสำคัญของระบบ
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('Notice.create') }}" class="px-8 py-4 bg-white text-emerald-700 font-black rounded-2xl hover:bg-emerald-50 transition-all shadow-lg flex items-center gap-2 uppercase tracking-widest text-sm transform active:scale-95">
                    <i class="ri-add-circle-line text-xl"></i> เพิ่มประกาศใหม่
                </a>
            </div>
        </div>
        <i class="ri-megaphone-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide italic">
                <i class="ri-list-check-2 text-emerald-600"></i> ทั้งหมด {{ count($notice) }} รายการ
            </h2>
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3" id="example1">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-6 py-2 text-center w-16">ID</th>
                            <th class="px-6 py-2">หัวข้อประกาศ / ผู้ประกาศ</th>
                            <th class="px-6 py-2 text-center">ประเภท</th>
                            <th class="px-6 py-2">รายละเอียด</th>
                            <th class="px-6 py-2">ไฟล์แนบ</th>
                            <th class="px-6 py-2 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($notice as $l)
                        <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                            <td class="px-6 py-6 text-center font-bold text-emerald-300 italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">
                                {{$l->id}}
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-black text-gray-800 text-base leading-tight uppercase italic group-hover:text-emerald-700 transition-colors">
                                    {{$l->notice_text}}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">
                                    <i class="ri-user-received-line"></i> {{$l->getschoolgroup->schoolgroup_name ?? 'ADMIN'}}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-200 shadow-sm">
                                    {{$l->notice_type ?: '-'}}
                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="text-xs text-gray-500 line-clamp-2 leading-relaxed max-w-[200px]">
                                    {!! preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline font-bold">$1</a>', $l->notice_detail) !!}
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                @if(!empty($l->files))
                                    <div class="flex flex-col gap-2">
                                        @foreach($l->files as $filename)
                                            @php
                                                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                $filePath = get_secure_file_url('upload', $filename, $l->year);
                                            @endphp
                                            @if($extension == 'pdf')
                                                <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg font-bold text-xs hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="ri-file-pdf-2-line text-lg shrink-0"></i>
                                                    <span class="truncate max-w-[100px]">{{ $filename }}</span>
                                                </a>
                                            @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <div class="relative group/img cursor-zoom-in inline-block" onclick="window.open('{{ $filePath }}', '_blank')">
                                                    <img src="{{ $filePath }}" class="w-20 h-12 object-cover rounded-lg shadow-sm border border-gray-100 group-hover/img:scale-110 transition-transform" alt="{{ $filename }}">
                                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/img:opacity-100 rounded-lg flex items-center justify-center transition-opacity">
                                                        <i class="ri-eye-line text-white"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ $filePath }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg font-bold text-xs hover:bg-slate-700 hover:text-white transition-all">
                                                    <i class="ri-file-line text-lg shrink-0"></i>
                                                    <span class="truncate max-w-[100px]">{{ $filename }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-300 italic text-[10px]">ไม่มีไฟล์แนบ</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{'Notice/'.$l->id.'/edit'}}" class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                        <i class="ri-edit-2-line text-lg"></i>
                                    </a>
                                    <form id="delform{{$l->id}}" method="post" action="{{ route('Notice.destroy',$l->id)}}" class="hidden">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        {!!csrf_field()!!}
                                    </form>
                                    <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="ri-delete-bin-line text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#example1').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาหัวข้อประกาศ...",
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
            'ยืนยันการลบประกาศ',
            'คุณต้องการลบประกาศนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้',
            function() {
                document.getElementById('delform' + id).submit();
            }
        );
    }
</script>
@endsection