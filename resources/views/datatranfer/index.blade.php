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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="group">
                <button type="button" id="btn-transfer" 
                        class="w-full h-full flex flex-col items-center justify-center p-8 bg-blue-50 border-2 border-blue-100 rounded-3xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-xl group">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // --- ปุ่ม Transfer ---
    $('#btn-transfer').click(function() {
        showCustomConfirm(
            'ยืนยันการดึงข้อมูล',
            'คุณต้องการดึงข้อมูลล่าสุดใช่หรือไม่?',
            function() {
                runProcess(
                    "{{ secure_url(route('datatranfer.store', [], false)) }}",
                    "{{ secure_url(route('datatranfer.progress', [], false)) }}",
                    'กำลังดึงข้อมูลเข้าระบบ...'
                );
            }
        );
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

    function runProcess(actionUrl, progressUrl, labelText) {
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
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#progress-bar').css('width', '100%').removeClass('progress-bar-animated');
                $('#progress-text').text('100%');
                $('#alert-message').html('<div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 font-bold animate-bounce"><i class="ri-checkbox-circle-line mr-1"></i> '+response.message+'</div>');
                $('button').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                clearInterval(progressInterval);
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'เกิดข้อผิดพลาดในการประมวลผล';
                $('#alert-message').html('<div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100 font-bold"><i class="ri-error-warning-line mr-1"></i> '+msg+'</div>');
                $('#progress-bar').removeClass('progress-bar-animated').addClass('bg-red-500');
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
});
</script>
@endsection