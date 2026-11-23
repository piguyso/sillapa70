@extends('layouts.app')

@section('content')
<style>
    .progress-bar-animated {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
        animation: progress-bar-stripes 1s linear infinite;
    }
    @keyframes progress-bar-stripes {
        from { background-position: 1rem 0; }
        to { background-position: 0 0; }
    }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-sarabun text-slate-800">
    
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden border-b-8 border-emerald-500">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                Database Manager
            </h1>
            <p class="text-slate-300 text-lg md:text-xl opacity-90 font-medium italic">
                <i class="ri-shield-flash-line mr-2"></i>ระบบจัดการฐานข้อมูลและสำรองข้อมูลกลาง
            </p>
        </div>
        <i class="ri-database-2-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200 border border-gray-100 p-8 md:p-12">
        
        <div id="progress-container" style="display:none;" class="mb-10 animate-fade-in">
            <div class="flex items-center justify-between mb-2">
                <p id="process-label" class="text-emerald-700 font-black italic uppercase tracking-widest text-sm">
                    กำลังดำเนินการ...
                </p>
                <span id="progress-text" class="text-lg font-black text-emerald-600">0%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-6 p-1 border border-slate-200 shadow-inner overflow-hidden">
                <div id="progress-bar" 
                     class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-300 progress-bar-animated" 
                     style="width: 0%">
                </div>
            </div>
        </div>

        <div id="alert-message" class="mb-6"></div>

        {{-- สถานะการประกาศผลระดับเครือข่าย (ต้องครบทุกเครือข่ายก่อนโอน) --}}
        <div class="mb-10 rounded-3xl border {{ $readiness['ready'] ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }} p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                <h2 class="text-lg font-black {{ $readiness['ready'] ? 'text-emerald-800' : 'text-amber-800' }}">
                    @if($readiness['ready'])
                        <i class="ri-checkbox-circle-line mr-1"></i> ทุกเครือข่ายประกาศผลครบแล้ว พร้อมโอนข้อมูล
                    @elseif(empty($readiness['networks']))
                        <i class="ri-information-line mr-1"></i> ยังไม่มีข้อมูลการแข่งขันระดับเครือข่าย
                    @else
                        <i class="ri-error-warning-line mr-1"></i> ยังโอนไม่ได้ — มี {{ $readiness['pending_networks'] }} เครือข่ายที่ยังประกาศผลไม่ครบ ({{ $readiness['pending_items'] }} รายการ)
                    @endif
                </h2>
                <span class="text-sm text-slate-600">
                    ทีมที่จะโอน {{ number_format($readiness['transferable_teams']) }} ทีม
                    (ไม่มีการแข่งขัน {{ number_format($readiness['no_contest_teams']) }} ทีม)
                </span>
            </div>

            @if(!empty($readiness['networks']))
            <div class="overflow-x-auto rounded-2xl bg-white border border-slate-100">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-2 text-left">เครือข่าย</th>
                            <th class="px-4 py-2 text-center whitespace-nowrap">ประกาศแล้ว / ทั้งหมด</th>
                            <th class="px-4 py-2 text-left">รายการที่ยังไม่ประกาศผล</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($readiness['networks'] as $network)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-2 font-bold">
                                <i class="{{ $network['ready'] ? 'ri-checkbox-circle-fill text-emerald-500' : 'ri-time-line text-amber-500' }} mr-1"></i>{{ $network['name'] }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $network['announced'] }} / {{ $network['activities'] }}</td>
                            <td class="px-4 py-2 text-slate-600">
                                {{ $network['ready'] ? '—' : implode(', ', $network['pending']) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="group">
                <button type="button" id="btn-transfer" @disabled(!$readiness['ready'])
                        class="disabled:opacity-50 disabled:cursor-not-allowed w-full h-full flex flex-col items-center justify-center p-8 bg-blue-50 border-2 border-blue-100 rounded-3xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-xl group">
                    <div class="w-16 h-16 rounded-2xl bg-white text-blue-600 flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform group-hover:bg-white/20 group-hover:text-white">
                        <i class="ri-refresh-line text-3xl"></i>
                    </div>
                    <span class="font-black text-sm uppercase tracking-widest italic text-center">ดึงข้อมูลการแข่งขัน</span>
                    <span class="text-[10px] mt-1 opacity-60">Step 1-3 Data Transfer</span>
                </button>
            </div>

            <div class="group">
                <button type="button" id="btn-backup" 
                        class="w-full h-full flex flex-col items-center justify-center p-8 bg-emerald-50 border-2 border-emerald-100 rounded-3xl hover:bg-emerald-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-xl">
                    <div class="w-16 h-16 rounded-2xl bg-white text-emerald-600 flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform group-hover:bg-white/20 group-hover:text-white">
                        <i class="ri-database-2-line text-3xl"></i>
                    </div>
                    <span class="font-black text-sm uppercase tracking-widest italic text-center">Backup ฐานข้อมูล</span>
                    <span class="text-[10px] mt-1 opacity-60">Full Database Export</span>
                </button>
            </div>

            <div class="group">
                <form id="empty-form" method="POST" action="{{url('dataempty')}}" class="h-full">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="post">
                    <button type="button" id="btn-empty"
                            class="w-full h-full flex flex-col items-center justify-center p-8 bg-red-50 border-2 border-red-100 rounded-3xl hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-xl group">
                        <div class="w-16 h-16 rounded-2xl bg-white text-red-600 flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform group-hover:bg-white/20 group-hover:text-white">
                            <i class="ri-delete-bin-line text-3xl"></i>
                        </div>
                        <span class="font-black text-sm uppercase tracking-widest italic text-center">ลบข้อมูลทั้งหมด</span>
                        <span class="text-[10px] mt-1 opacity-60 text-red-400 group-hover:text-white">Danger Zone / Clear Data</span>
                    </button>
                </form>
            </div>

        </div>

        <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-100 flex items-start gap-4 italic text-xs text-slate-500">
            <i class="ri-information-line text-blue-500 text-lg"></i>
            <div>
                <p class="font-bold text-slate-700 mb-1">คำแนะนำการใช้งาน:</p>
                <ul class="list-disc ml-4 space-y-1">
                    <li>การดึงข้อมูลจะทำการ Update ข้อมูลผู้สมัครล่าสุดจากฐานข้อมูลกลาง</li>
                    <li>ควรทำการ Backup ข้อมูลอย่างน้อยสัปดาห์ละ 1 ครั้ง หรือก่อนดำเนินการลบข้อมูลใดๆ</li>
                    <li>หากเกิดข้อผิดพลาดระหว่างดำเนินการ ให้รอสักครู่แล้วลองใหม่อีกครั้ง</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // --- ปุ่ม Transfer ---
    var readiness = @json($readiness);

    $('#btn-transfer').click(function() {
        if (!readiness.ready) {
            appAlert('ยังโอนข้อมูลไม่ได้ เนื่องจากยังมีเครือข่ายที่ประกาศผลไม่ครบ กรุณาตรวจสอบตารางสถานะ');
            return;
        }

        var message =
            '<div style="text-align:left">' +
            '✅ ทุกเครือข่ายประกาศผลครบแล้ว (' + readiness.networks.length + ' เครือข่าย)<br>' +
            '• จะโอน <b>' + readiness.transferable_teams + ' ทีม</b> (อันดับ 1–3 และรายการที่ไม่มีการแข่งขัน ' + readiness.no_contest_teams + ' ทีม) พร้อมรายชื่อนักเรียนและครู<br>';
        if (readiness.district_teams > 0) {
            message += '<br><span style="color:#b91c1c;font-weight:700">⚠️ ข้อมูลระดับเขตพื้นที่เดิม ' + readiness.district_teams + ' ทีม จะถูกลบและแทนที่ทั้งหมด';
            if (readiness.district_scored_teams > 0) {
                message += ' รวมถึงคะแนน/ผลการแข่งขันระดับเขตที่บันทึกแล้ว ' + readiness.district_scored_teams + ' ทีม';
            }
            message += '</span><br>';
        }
        message += '<br>แนะนำให้กด Backup ฐานข้อมูลก่อนโอน การกระทำนี้ย้อนกลับไม่ได้</div>';

        appConfirm(message, { title: 'ยืนยันการโอนข้อมูลไประดับเขตพื้นที่', confirmText: 'ยืนยันโอนข้อมูล' }).then(function(ok) {
            if (!ok) return;
            runProcess(
                "{{ secure_url(route('datatranfer.store', [], false)) }}",
                "{{ secure_url(route('datatranfer.progress', [], false)) }}",
                'กำลังโอนข้อมูลไประดับเขตพื้นที่...',
                { confirm_replace: 1 },
                true
            );
        });
    });

    // --- ปุ่ม Backup ---
    $('#btn-backup').click(function() {
        showCustomConfirm(
            'ยืนยันการสำรองข้อมูล',
            'ยืนยันการ Backup ฐานข้อมูล? (อาจใช้เวลาสักครู่)',
            function() {
                runProcess(
                    "{{ secure_url(route('databackup', [], false))}}",
                    "{{ secure_url(route('databackup.progress', [], false)) }}",
                    'กำลังสำรองข้อมูล (Database Backup)...'
                );
            }
        );
    });

    // --- ปุ่ม Empty ---
    $('#btn-empty').click(function() {
        showCustomConfirm(
            'ยืนยันการลบข้อมูลทั้งหมด',
            '⚠️ ยืนยันลบข้อมูลผู้เข้าแข่งขันทั้งหมด? การกระทำนี้ไม่สามารถย้อนกลับได้!',
            function() {
                $('#empty-form').submit();
            }
        );
    });

    function runProcess(actionUrl, progressUrl, labelText, extraData, reloadOnSuccess) {
        // Reset UI
        $('#progress-container').fadeIn();
        $('#process-label').html(labelText);
        $('#progress-text').text('0%');
        $('#alert-message').html('');
        $('#progress-bar').css('width', '0%').addClass('progress-bar-animated');
        
        $('button').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

        // 1. ส่งคำสั่งเริ่มทำงาน
        $.ajax({
            url: actionUrl,
            type: "POST",
            data: Object.assign({ _token: "{{ csrf_token() }}" }, extraData || {}),
            success: function(response) {
                $('#progress-bar').css('width', '100%').removeClass('progress-bar-animated');
                $('#progress-text').text('100%');
                $('#alert-message').html('<div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 font-bold animate-bounce"><i class="ri-checkbox-circle-line mr-1"></i> '+response.message+'</div>');
                $('button').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                clearInterval(progressInterval);
                if (reloadOnSuccess) setTimeout(function() { window.location.reload(); }, 2500);
            },
            error: function(xhr) {
                var data = xhr.responseJSON || {};
                var msg = data.message || ('เซิร์ฟเวอร์ตอบกลับ HTTP ' + xhr.status + ' โดยไม่มีรายละเอียดข้อผิดพลาด');
                $('#alert-message').html(
                    '<div class="p-5 mb-4 text-sm text-red-900 rounded-2xl bg-red-50 border border-red-200">' +
                    '<div class="font-black text-base"><i class="ri-error-warning-line mr-1"></i> ' + escapeHtml(msg) + '</div>' +
                    buildErrorDetails(data) +
                    '</div>'
                );
                $('#progress-bar').removeClass('progress-bar-animated').addClass('bg-red-500');
                $('#process-label').text('ดำเนินการไม่สำเร็จ');
                $('button').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                clearInterval(progressInterval);
            }
        });

        // 2. เช็ค Progress
        var progressInterval = setInterval(function() {
            $.ajax({
                url: progressUrl,
                type: "GET",
                success: function(data) {
                    var percent = data.progress;
                    $('#progress-bar').css('width', percent + '%');
                    $('#progress-text').text(percent + '%');
                    if(percent >= 100) clearInterval(progressInterval);
                }
            });
        }, 1000);
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function buildErrorDetails(data) {
        var html = '<dl class="mt-4 grid grid-cols-1 md:grid-cols-[160px_1fr] gap-2">';
        if (data.error_code) html += '<dt class="font-bold">รหัสข้อผิดพลาด</dt><dd>' + escapeHtml(data.error_code) + '</dd>';
        if (data.error_id) html += '<dt class="font-bold">เลขอ้างอิง</dt><dd>' + escapeHtml(data.error_id) + '</dd>';
        if (data.stage) html += '<dt class="font-bold">ขั้นตอนที่ผิดพลาด</dt><dd>' + escapeHtml(data.stage) + '</dd>';
        if (data.technical_message) html += '<dt class="font-bold">รายละเอียดระบบ</dt><dd class="break-all">' + escapeHtml(data.technical_message) + '</dd>';
        html += '</dl>';

        if (data.team) {
            html += '<div class="mt-4 rounded-xl bg-white/70 p-3"><b>ทีมที่กำลังประมวลผล:</b> Team ID ' +
                escapeHtml(data.team.team_id) + ', School ID ' + escapeHtml(data.team.school_id) +
                ', Activity ID ' + escapeHtml(data.team.activity_id) + ', อันดับ ' + escapeHtml(data.team.rank) + '</div>';
        }

        if (Array.isArray(data.errors) && data.errors.length) {
            html += '<div class="mt-4"><b>รายการที่ต้องแก้ไข:</b><ol class="mt-2 list-decimal pl-5 space-y-2">';
            data.errors.forEach(function(item) {
                html += '<li><b>' + escapeHtml(item.school) + '</b> — ' + escapeHtml(item.activity) +
                    ', อันดับ ' + escapeHtml(item.rank) + ': ' + escapeHtml(item.problem) + '</li>';
            });
            html += '</ol></div>';
        }

        return html;
    }
});
</script>
@endsection
