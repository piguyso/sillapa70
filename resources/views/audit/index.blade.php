@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 flex items-center gap-2"><i class="ri-history-line" style="color: var(--ui-primary)"></i> บันทึกกิจกรรมของระบบ</h1>
        <p class="text-sm text-slate-500 mt-1">ดูว่าใครทำอะไร เมื่อไร จากที่ไหน ทั้งการเข้าสู่ระบบ การเพิ่ม/แก้/ลบข้อมูล และการส่งออก/พิมพ์เอกสาร (ทั้งหมด {{ number_format($total) }} รายการ)</p>
    </div>

    <form method="get" class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <x-form.input label="ผู้ใช้ (ชื่อบัญชีหรือรหัส)" name="user" :value="$filters['user']" />
        <x-form.select label="การกระทำ" name="action">
            <option value="">ทั้งหมด</option>
            @foreach($actions as $key => $label)
                <option value="{{ $key }}" @selected($filters['action'] === $key)>{{ $label }}</option>
            @endforeach
        </x-form.select>
        <x-form.select label="ข้อมูล" name="table">
            <option value="">ทั้งหมด</option>
            @foreach($tables as $key => $label)
                <option value="{{ $key }}" @selected($filters['table'] === $key)>{{ $label }}</option>
            @endforeach
        </x-form.select>
        <x-form.input label="ค้นหา (ชื่อ/รหัส/URL)" name="q" :value="$filters['q']" />
        <x-form.input label="ตั้งแต่วันที่" name="from" type="date" :value="$filters['from']" />
        <x-form.input label="ถึงวันที่" name="to" type="date" :value="$filters['to']" />
        <x-form.select label="ขอบเขต" name="scope">
            <option value="event" @selected($filters['scope'] === 'event')>เฉพาะงานแข่งขันที่เปิดอยู่</option>
            <option value="all" @selected($filters['scope'] === 'all')>ทุกงาน</option>
        </x-form.select>
        <div class="flex items-center gap-2 pb-1">
            <x-form.button type="submit"><i class="ri-search-line"></i> ค้นหา</x-form.button>
            <a href="{{ url('audit-logs') }}" class="ui-btn ui-btn-secondary">ล้าง</a>
        </div>
    </form>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <th class="p-3">เวลา</th>
                    <th class="p-3">ผู้ใช้</th>
                    <th class="p-3">การกระทำ</th>
                    <th class="p-3">ข้อมูล</th>
                    <th class="p-3">รายละเอียด</th>
                    <th class="p-3">IP</th>
                </tr>
            </thead>
            @forelse($logs as $log)
                @php $detail = $log->changes ? json_decode($log->changes, true) : null; @endphp
                <tbody x-data="{ open: false }" class="border-t border-slate-100">
                    <tr class="hover:bg-slate-50 align-top">
                        <td class="p-3 whitespace-nowrap">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                        <td class="p-3">
                            <div class="font-bold">{{ $log->username ?: '-' }}</div>
                            <div class="text-xs text-slate-400">{{ $positions[$log->user_position] ?? 'ไม่ได้เข้าสู่ระบบ' }}</div>
                        </td>
                        <td class="p-3 whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded-lg text-xs font-bold
                                {{ match($log->action) { 'deleted', 'login_failed' => 'bg-red-100 text-red-700', 'created' => 'bg-emerald-100 text-emerald-700', 'updated' => 'bg-amber-100 text-amber-700', 'request' => 'bg-sky-100 text-sky-700', default => 'bg-slate-100 text-slate-700' } }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td class="p-3">
                            {{ $log->table_label }}
                            @if($log->subject_id)<span class="text-xs text-slate-400">#{{ $log->subject_id }}</span>@endif
                        </td>
                        <td class="p-3">
                            <div>{{ $log->description }}</div>
                            @if($detail)
                                <button type="button" @click="open = !open" class="text-xs font-bold" style="color: var(--ui-primary)" x-text="open ? 'ซ่อนรายละเอียด' : 'ดูรายละเอียด'"></button>
                            @endif
                        </td>
                        <td class="p-3 whitespace-nowrap text-xs text-slate-500">{{ $log->ip_address }}</td>
                    </tr>
                    @if($detail)
                        <tr x-show="open" x-cloak>
                            <td colspan="6" class="px-6 pb-4 bg-slate-50">
                                @if(isset($detail['before']) || isset($detail['after']))
                                    @php $keys = array_unique(array_merge(array_keys($detail['before'] ?? []), array_keys($detail['after'] ?? []))); @endphp
                                    <table class="text-xs w-full max-w-4xl">
                                        <tr class="text-slate-500"><th class="text-left p-1">ฟิลด์</th><th class="text-left p-1">ก่อน</th><th class="text-left p-1">หลัง</th></tr>
                                        @foreach($keys as $k)
                                            <tr class="border-t border-slate-200 align-top">
                                                <td class="p-1 font-bold">{{ $k }}</td>
                                                <td class="p-1 text-red-700 break-all">{{ is_scalar($detail['before'][$k] ?? null) ? ($detail['before'][$k] ?? '') : json_encode($detail['before'][$k] ?? null, JSON_UNESCAPED_UNICODE) }}</td>
                                                <td class="p-1 text-emerald-700 break-all">{{ is_scalar($detail['after'][$k] ?? null) ? ($detail['after'][$k] ?? '') : json_encode($detail['after'][$k] ?? null, JSON_UNESCAPED_UNICODE) }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @else
                                    <pre class="text-xs whitespace-pre-wrap break-all">{{ json_encode($detail, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                    <div class="text-[11px] text-slate-400 mt-1">{{ $log->method }} {{ $log->url }} @if($log->status) &middot; สถานะ {{ $log->status }} @endif</div>
                                @endif
                            </td>
                        </tr>
                    @endif
                </tbody>
            @empty
                <tbody><tr><td colspan="6" class="p-10 text-center text-slate-400">ไม่พบรายการ</td></tr></tbody>
            @endforelse
        </table>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
</div>
@endsection
