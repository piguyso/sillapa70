@extends('layouts.apparea')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-black text-slate-800"><i class="ri-printer-line text-primary mr-2"></i>พิมพ์เอกสาร</h1>
                <p class="text-sm text-slate-500">เครือข่าย {{ $network->schoolgroup_name ?? '' }} · ปีการศึกษา {{ active_year() }} · เฉพาะรายการที่มีทีมลงทะเบียน</p>
            </div>
            <a href="{{ url('area/'.session('area')) }}" class="ui-btn ui-btn-secondary"><i class="ri-home-4-line"></i> หน้าหลัก</a>
        </div>

        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm" id="table_printdocs">
                <thead>
                    <tr class="bg-slate-50 text-left text-slate-600">
                        <th class="px-3 py-3">#</th>
                        <th class="px-3 py-3">กลุ่มสาระ</th>
                        <th class="px-3 py-3">รายการแข่งขัน</th>
                        <th class="px-3 py-3">ระดับชั้น</th>
                        <th class="px-3 py-3 text-center">ทีม</th>
                        <th class="px-3 py-3">เอกสาร</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $i => $a)
                        <tr class="border-b border-slate-100 align-top">
                            <td class="px-3 py-3">{{ $i + 1 }}</td>
                            <td class="px-3 py-3">{{ $a->getactivitysubject->subjectgroup_name ?? '-' }}</td>
                            <td class="px-3 py-3 font-bold">{{ $a->activity_name }}</td>
                            <td class="px-3 py-3">{{ $a->getclass->class_name ?? '-' }}</td>
                            <td class="px-3 py-3 text-center">{{ $teams[$a->id] ?? 0 }}</td>
                            <td class="px-3 py-3">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach([
                                        ['area/printpointdoc/', 'ri-edit-box-line', 'ใบบันทึกคะแนน'],
                                        ['area/studentregisterform/', 'ri-user-follow-line', 'ใบรายงานตัวนักเรียน'],
                                        ['area/teacherregisterform/', 'ri-time-line', 'ใบลงเวลาครู'],
                                        ['area/jowregisterform/', 'ri-shield-user-line', 'ใบลงเวลากรรมการ'],
                                    ] as [$path, $icon, $label])
                                        <a href="{{ url($path.$a->id) }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold whitespace-nowrap hover:bg-primary hover:text-white transition">
                                            <i class="{{ $icon }}"></i> {{ $label }}
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        if ($.fn.DataTable) {
            $('#table_printdocs').DataTable({ pageLength: 50, autoWidth: false, order: [] });
        }
    });
</script>
@endsection
