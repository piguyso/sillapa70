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

<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner mb-4"></div>
        <p class="text-emerald-700 font-bold animate-pulse text-lg">กำลังโหลดข้อมูลระดับชั้น...</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase text-white">
                Class Management
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium italic">
                <i class="ri-卒業生-line mr-2"></i>จัดการข้อมูลระดับชั้นและประเภทการแข่งขัน
            </p>
        </div>
        <i class="ri-book-open-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold animate-fade-in">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-emerald-600 px-8 py-6 text-white">
                    <h2 class="text-xl font-bold flex items-center gap-2 italic uppercase text-white">
                        <i class="ri-add-box-line"></i> เพิ่มระดับชั้น
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ Route('class.store') }}" class="space-y-6">
                        {{ csrf_field() }}
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">ชื่อระดับชั้น</label>
                            <input type="text" name="class_name" value="{{ old('class_name') }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                   placeholder="เช่น มัธยมศึกษาตอนต้น, ปฐมวัย...">
                            @if ($errors->has('class_name'))
                                <p class="text-red-500 text-xs mt-1 font-bold italic">* {{ $errors->first('class_name') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">รายละเอียดเพิ่มเติม</label>
                            <textarea name="class_detail" rows="3" 
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner"
                                      placeholder="ระบุข้อมูลเพิ่มเติมเกี่ยวกับระดับชั้นนี้...">{{ old('class_detail') }}</textarea>
                            @if ($errors->has('class_detail'))
                                <p class="text-red-500 text-xs mt-1 font-bold italic">* {{ $errors->first('class_detail') }}</p>
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
                <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide italic">
                        <i class="ri-list-ordered text-emerald-600"></i> Class Directory
                    </h2>
                </div>
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3" id="table_classes">
                            <thead>
                                <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                    <th class="px-6 py-2 text-center w-16">ID</th>
                                    <th class="px-6 py-2">ชื่อระดับชั้น / ประเภท</th>
                                    <th class="px-6 py-2">รายละเอียด</th>
                                    <th class="px-6 py-2 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($class as $l)
                                <tr class="group bg-white hover:bg-emerald-50/30 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                    <td class="px-6 py-6 text-center font-bold text-emerald-300 text-lg italic border-l-4 border-transparent group-hover:border-emerald-500 rounded-l-2xl">
                                        {{$l->id}}
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="font-black text-gray-800 text-base leading-tight uppercase italic tracking-tight group-hover:text-emerald-700 transition-colors">
                                            {{$l->class_name}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-xs text-gray-400 font-bold flex items-center gap-1">
                                            <i class="ri-information-line"></i> {{$l->class_detail ?: 'ไม่มีรายละเอียด'}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if(officerchk())
                                        <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                            <a href="{{'class/'.$l->id.'/edit?action=edit'}}" 
                                               class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                               title="แก้ไข">
                                                <i class="ri-edit-2-line text-lg"></i>
                                            </a>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('class.destroy',$l->id)}}" class="hidden">
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_classes').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาระดับชั้น...",
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
        if (confirm('คุณต้องการลบข้อมูลระดับชั้นนี้ใช่หรือไม่? กิจกรรมการแข่งขันที่ผูกกับระดับชั้นนี้อาจได้รับผลกระทบ')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection