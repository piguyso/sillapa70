@if (auth()->check() && (int) auth()->user()->position === 10)
    @php $viewTotal = \App\Services\NoticeViewService::totals([(int) $noticeId])[(int) $noticeId] ?? 0; @endphp
    <div class="mb-4 flex justify-end">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-full px-3 py-1 shadow-sm" title="จำนวนครั้งที่เปิดอ่าน (เห็นเฉพาะผู้ดูแลระบบ)">
            <i class="ri-eye-line"></i> เปิดอ่าน {{ number_format($viewTotal) }} ครั้ง
        </span>
    </div>
@endif
