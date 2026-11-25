{{-- กติกาการเปลี่ยนตัวทีม (ประกาศ ครั้งที่ 74 ข้อ 5.1 และ 6) แสดงอย่างเดียว ไม่บังคับ; ต้องส่ง $activity เข้ามา --}}
@if(\App\Services\TeamSubstitutionRule::appliesToActiveEvent() && $activity)
    @php $teamSize = (int) $activity->activity_studennumber; @endphp
    <div class="mb-4 p-4 rounded-2xl border flex items-start gap-3" style="border-color: var(--ui-primary); background: color-mix(in srgb, var(--ui-primary) 8%, white)">
        <i class="ri-information-line text-xl mt-0.5" style="color: var(--ui-primary)"></i>
        <div class="text-sm text-slate-700 leading-relaxed">
            <div class="font-black" style="color: var(--ui-primary)">กติกาการเปลี่ยนตัว</div>
            <div>{{ \App\Services\TeamSubstitutionRule::describe($teamSize) }}@if($teamSize > 1) · ครูผู้สอน {{ (int) $activity->activity_teachernumber }} คน @endif</div>
            <div class="text-xs text-slate-500">การเปลี่ยนตัวครูผู้สอนทำได้ แต่ต้องมีหนังสือยินยอมจากครูคนเดิมและผู้อำนวยการโรงเรียนลงนามอนุญาต (แสดงเพื่อทราบเท่านั้น ระบบไม่ได้จำกัดการแก้ไข)</div>
        </div>
    </div>
@endif
