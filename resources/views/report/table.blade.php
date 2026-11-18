@extends($level === 'network' ? 'layouts.apparea' : 'layouts.app')

@section('content')
<style>
    .pr-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .pr-table th, .pr-table td { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: top; }
    .pr-table th { background: #e2e8f0; white-space: nowrap; }
    .pr-names div { white-space: nowrap; }
    .pr-tab { padding: 6px 14px; border-radius: 10px; font-weight: 700; font-size: 14px; border: 1px solid #cbd5e1; background: #fff; color: #334155; }
    .pr-tab.active { background: var(--ui-primary); border-color: var(--ui-primary); color: #fff; }
    @media print {
        @page { size: A4 landscape; margin: 10mm; }
        .no-print, nav, header, footer { display: none !important; }
        .pr-table { font-size: 11px; }
        .pr-table tr { page-break-inside: avoid; }
    }
</style>

<div class="max-w-[1600px] mx-auto px-4 py-6">
    <h1 class="text-xl font-black text-slate-800 mb-1">
        {{ $types[$type]['title'] }}
        {{ $level === 'network' ? 'ระดับเครือข่าย '.$networkName : 'ระดับเขตพื้นที่การศึกษา' }}
    </h1>
    <p class="text-sm text-slate-500 mb-3">
        ปีการศึกษา {{ active_year() }} ·
        {{ number_format($rows->count()) }} {{ $type === 'activities' ? 'รายการ' : 'ทีม' }}
        @if($type === 'participants' && $filters['top3']) · เฉพาะอันดับ 1-3 @endif
    </p>

    <div class="no-print flex flex-wrap gap-2 mb-4">
        @foreach($types as $key => $t)
            <a href="{{ $tabUrls[$key] }}" class="pr-tab {{ $key === $type ? 'active' : '' }}">{{ $t['file'] }}</a>
        @endforeach
    </div>

    <form method="get" class="no-print grid grid-cols-1 md:grid-cols-5 gap-3 items-end mb-4">
        <x-form.select label="กลุ่มสาระ" name="subject" onchange="this.form.activity && (this.form.activity.value='');this.form.submit()">
            <option value="">ทั้งหมด</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" @selected($filters['subject'] == $s->id)>{{ $s->subjectgroup_name }}</option>
            @endforeach
        </x-form.select>
        @if($type !== 'activities')
            <x-form.select label="รายการแข่งขัน" name="activity">
                <option value="">ทั้งหมด</option>
                @foreach($activities as $a)
                    <option value="{{ $a->id }}" @selected($filters['activity'] == $a->id)>{{ $a->activity_name }}</option>
                @endforeach
            </x-form.select>
        @endif
        @if($level === 'district')
            <x-form.select label="เครือข่าย" name="schoolgroup">
                <option value="">ทั้งหมด</option>
                @foreach($schoolgroups as $g)
                    <option value="{{ $g->id }}" @selected($filters['schoolgroup'] == $g->id)>{{ $g->schoolgroup_name }}</option>
                @endforeach
            </x-form.select>
        @endif
        @if($type === 'participants')
            <label class="flex items-center gap-2 text-sm font-bold text-slate-700 pb-2">
                <input type="checkbox" name="top3" value="1" @checked($filters['top3'])> เฉพาะอันดับ 1-3
            </label>
        @endif
        <div class="flex flex-wrap gap-2 pb-1">
            <x-form.button type="submit">ค้นหา</x-form.button>
            <a href="{{ $exportUrl }}" class="ui-btn ui-btn-secondary"><i class="ri-file-excel-2-line"></i> Excel</a>
            <x-form.button variant="secondary" onclick="window.print()"><i class="ri-printer-line"></i> พิมพ์</x-form.button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="pr-table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    @foreach($columns as $col)<th>{{ $col['label'] }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        @foreach($columns as $col)
                            @php $v = $row[$col['key']] ?? null; @endphp
                            @if($col['kind'] === 'list')
                                <td class="pr-names">@foreach((array) $v as $n)<div>{{ $n }}</div>@endforeach</td>
                            @elseif($col['kind'] === 'num')
                                <td class="text-center">{{ $v ?? '-' }}</td>
                            @else
                                <td>{{ $v }}</td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) + 1 }}" class="text-center text-slate-400 py-8">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
