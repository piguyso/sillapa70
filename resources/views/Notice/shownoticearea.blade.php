@extends('layouts.apparea')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<style>
    .ql-toolbar.ql-snow {
        @apply border-none bg-slate-50 border-b border-slate-200 p-2 !important;
    }
    .ql-container.ql-snow {
        @apply border-none !important;
        font-family: 'Sarabun', sans-serif !important;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">

    <div class="relative bg-gradient-to-r from-amber-800 to-orange-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white overflow-hidden border-b-8 border-orange-500 text-white">
        <div class="relative z-10 text-white">
            <h2 class="text-xl md:text-2xl font-bold opacity-90 italic mb-2 text-white leading-none">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{Auth::user()->getschoolgroup->schoolgroup_name}}
            </h2>
            <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase italic text-white leading-none">
                ประกาศข่าวสาร
            </h1>
        </div>
        <i class="ri-notification-3-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12 text-white"></i>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-slate-800">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden mb-8">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100 flex justify-between items-center text-slate-800">
                    <h2 class="text-base font-black text-slate-800 uppercase italic tracking-wide leading-none text-slate-800">รายการประกาศทั้งหมด</h2>
                </div>

                <div class="p-4 md:p-8 text-slate-800">
                    <div class="overflow-x-auto text-slate-800">
                        <table class="w-full text-left border-separate border-spacing-y-3 text-slate-800">
                            <thead>
                                <tr class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black italic text-slate-800">
                                    <th class="px-6 py-2 w-32 text-slate-400">วันที่</th>
                                    <th class="px-6 py-2 text-slate-400">หัวข้อประกาศ</th>
                                    <th class="px-6 py-2 text-slate-400">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-bold italic text-slate-800">
                                @forelse($shownotice as $l)
                                <tr class="bg-white hover:bg-orange-50/50 transition-all border border-slate-100 shadow-sm rounded-2xl group text-slate-800">
                                    <td class="px-6 py-5 text-slate-400 rounded-l-2xl border-l-4 border-transparent group-hover:border-orange-500 text-slate-800">
                                        <span class="text-[10px] font-black leading-none text-slate-800">{{Datethai6($l->updated_at)}}</span>
                                    </td>
                                    <td class="px-6 py-5 text-slate-800">
                                        <p class="text-slate-800 font-black text-sm uppercase leading-tight text-slate-800"><a href="{{url('/area/notice_detail/'.$l->id)}}">{{$l->notice_text}}</a></p>
                                        <div class="text-[10px] text-slate-400 font-medium mt-1 italic line-clamp-1 text-slate-400">
                                            {{ strip_tags($l->notice_detail) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center rounded-r-2xl text-slate-800">
                                        <div class="flex justify-end gap-2 text-slate-800">
                                            <button onclick="DelConfirm({{$l->id}})" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('Notice.destroy',$l->id)}}" class="hidden">
                                                <input type="hidden" name="_method" value="DELETE"/> {!!csrf_field()!!}
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-10 font-bold italic text-slate-400">ไม่พบประกาศในขณะนี้</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 text-slate-800">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden sticky top-10 text-slate-800">
                <div class="bg-amber-900 px-8 py-6 text-white flex items-center gap-3 text-white">
                    <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm text-white">
                        <i class="ri-add-line text-xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase italic leading-none text-white">Create Notice</h3>
                        <p class="text-orange-200 text-[10px] mt-1 font-bold italic uppercase opacity-80 text-white leading-none">เพิ่มประกาศใหม่</p>
                    </div>
                </div>

                <div class="p-8 text-slate-800">
                    <form method="POST" action="{{ Route('Notice.store') }}" enctype="multipart/form-data" class="space-y-6 font-bold italic text-slate-800">
                        {{ csrf_field() }}

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-slate-800">หัวข้อเรื่อง (Subject)</label>
                            <input type="text" name="notice_text" value="{{ old('notice_text') }}" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-black text-slate-700 shadow-inner text-slate-800">
                        </div>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-slate-800">รายละเอียด (Detail)</label>
                            <input type="hidden" name="notice_detail" id="notice_detail" value="{{ old('notice_detail') }}">
                            <div class="rounded-2xl overflow-hidden bg-white border border-slate-200">
                                <div id="quill_editor" style="min-height: 200px; font-family: 'Sarabun', sans-serif;">
                                    {!! old('notice_detail') !!}
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1 text-slate-800">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-slate-800">ไฟล์แนบ (Attachment)</label>
                            <input type="file" name="notice_file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        </div>

                        <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-sm hover:bg-orange-700 shadow-xl shadow-orange-100 uppercase italic tracking-widest text-white mt-4">
                            <i class="ri-save-3-line mr-1 text-white"></i> บันทึกประกาศ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Quill Editor
    const quill = new Quill('#quill_editor', {
        theme: 'snow',
        placeholder: 'เขียนรายละเอียดประกาศตรงนี้...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    // Sync Quill content to hidden input on submit
    document.querySelectorAll('form').forEach(form => {
        const hiddenInput = form.querySelector('#notice_detail');
        const editorDiv = form.querySelector('#quill_editor');
        if (hiddenInput && editorDiv) {
            form.addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
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