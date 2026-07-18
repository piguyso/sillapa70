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
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                Title Management
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-text-spacing mr-2"></i>จัดการข้อมูลคำนำหน้าชื่อ
            </p>
        </div>
        <i class="ri-settings-3-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="ri-add-circle-line text-primary"></i> เพิ่มข้อมูลคำนำหน้า
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ Route('title.store') }}" class="space-y-6">
                        {{ csrf_field() }}
                        <div>
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest mb-2">ชื่อคำนำหน้า</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-gray-700"
                                   placeholder="เช่น นาย, นางสาว, เด็กชาย...">
                            @if ($errors->has('title'))
                                <p class="text-red-500 text-xs mt-2 font-bold italic">* {{ $errors->first('title') }}</p>
                            @endif
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-100 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-save-line text-xl"></i> เพิ่มข้อมูล
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">


            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3" id="table_titles">
                            <thead>
                                <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                    <th class="px-6 py-2 text-center w-16">ID</th>
                                    <th class="px-6 py-2">คำนำหน้า</th>
                                    <th class="px-6 py-2 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($title as $l)
                                <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                    <td class="px-6 py-6 text-center font-bold text-gray-400">{{$l->id}}</td>
                                    <td class="px-6 py-6 font-black text-gray-700 text-base">
                                        {{$l->title}}
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if(officerchk())
                                        <div class="flex justify-center gap-2">
                                            <a href="{{'title/'.$l->id.'/edit?action=edit'}}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-pencil-line text-lg"></i>
                                            </a>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('title.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                {!!csrf_field()!!}
                                            </form>
                                            <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
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
            var table = $('#table_titles').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาคำนำหน้า...",
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
            'ยืนยันการลบข้อมูล',
            'คุณต้องการลบข้อมูลนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้',
            function() {
                document.getElementById('delform' + id).submit();
            }
        );
    }
</script>
@endsection