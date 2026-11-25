@extends($from === 'area' ? 'layouts.apparea' : 'layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
@php $backUrl = $from === 'area' ? url('area/shownotice') : url('shownotice'); @endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-slate-800">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ $backUrl }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-200 hover:bg-gray-50 shadow-sm">
            <i class="ri-arrow-left-s-line text-2xl" style="color: var(--ui-primary)"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 leading-none mb-1">แก้ไขประกาศ</h1>
            <p class="text-sm text-gray-400 font-bold">แก้ไขหัวข้อ รายละเอียด และไฟล์แนบ</p>
        </div>
    </div>

    @foreach($notice as $l)
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="px-8 py-5 text-white font-black flex items-center gap-2" style="background: var(--ui-primary)">
            <i class="ri-file-edit-line text-xl"></i> ประกาศ #{{ $l->id }}
        </div>
        <form id="noticeEditForm" method="POST" action="{{ route('Notice.update', $l->id) }}" enctype="multipart/form-data" class="p-8 space-y-6">
            {{ csrf_field() }}
            @method('PATCH')
            <input type="hidden" name="from" value="{{ $from }}">

            <x-form.input label="หัวข้อประกาศ" name="notice_text" :value="old('notice_text', $l->notice_text)" required />

            <div>
                <label class="ui-label">รายละเอียด</label>
                <input type="hidden" name="notice_detail" id="notice_detail" value="">
                <div class="rounded-xl overflow-hidden bg-white border border-gray-200">
                    <div id="quill_editor" style="min-height: 260px; font-family: 'Sarabun', sans-serif;">{!! old('notice_detail', $l->notice_detail) !!}</div>
                </div>
                @error('notice_detail')<p class="ui-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="ui-label">ไฟล์แนบปัจจุบัน</label>
                @forelse($l->files as $file)
                    <label class="flex items-center gap-3 p-3 mb-2 rounded-xl border border-gray-200 bg-gray-50">
                        <input type="checkbox" name="remove_files[]" value="{{ $file }}" class="w-4 h-4">
                        <a href="{{ get_secure_file_url('upload', $file, $l->year) }}" target="_blank" class="text-sm font-bold break-all" style="color: var(--ui-primary)">{{ $file }}</a>
                        <span class="ml-auto text-xs text-red-500 font-bold">ติ๊กเพื่อลบไฟล์นี้</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-400 italic">ไม่มีไฟล์แนบ</p>
                @endforelse
            </div>

            <x-form.input label="เพิ่มไฟล์แนบใหม่ (เลือกได้หลายไฟล์ ไม่เกิน 12 MB ต่อไฟล์)" name="notice_file[]" id="notice_file" type="file" multiple
                          accept="image/jpeg,image/png,image/webp,.pdf,.doc,.docx,.xls,.xlsx,.zip" help="ไฟล์ใหม่จะถูกเพิ่มต่อจากไฟล์เดิม (ไฟล์เดิมที่ไม่ได้ติ๊กลบจะยังอยู่)" />
            @error('notice_file.*')<p class="ui-error">{{ $message }}</p>@enderror

            <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ $backUrl }}" class="ui-btn ui-btn-secondary">ยกเลิก</a>
                <x-form.button type="submit"><i class="ri-save-3-line"></i> บันทึกการแก้ไข</x-form.button>
            </div>
        </form>
    </div>
    @endforeach
</div>

<script>
    const quill = new Quill('#quill_editor', {
        theme: 'snow',
        placeholder: 'เขียนรายละเอียดประกาศตรงนี้...',
        modules: { toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['link', 'clean']
        ] }
    });
    document.getElementById('noticeEditForm').addEventListener('submit', function (e) {
        var html = quill.root.innerHTML;
        if (quill.getText().trim() === '') {
            e.preventDefault();
            appAlert('กรุณากรอกรายละเอียดประกาศ');
            return;
        }
        document.getElementById('notice_detail').value = html;
    });
</script>
@endsection
