@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(10deg); }
    }
    @keyframes shine {
        0% { left: -100%; transition: all 0.5s ease-in; }
        100% { left: 100%; transition: all 0.5s ease-in; }
    }
    @keyframes particle-up {
        0% { transform: translateY(100%) scale(0); opacity: 0; }
        50% { opacity: 0.5; }
        100% { transform: translateY(-100vh) scale(1); opacity: 0; }
    }
    .animate-float { animation: float 8s ease-in-out infinite; }
    .animate-float-delayed { animation: float 10s ease-in-out infinite; animation-delay: 3s; }
    .particle {
        position: absolute;
        background: white;
        border-radius: 50%;
        pointer-events: none;
        animation: particle-up 15s linear infinite;
    }
</style>


 <div class="relative bg-gradient-to-r from-primary via-emerald-600 to-primary bg-[length:200%_200%] animate-[gradient_8s_ease_infinite] rounded-3xl shadow-2xl mb-12 overflow-hidden transform transition-all duration-700 hover:shadow-emerald-500/20">
    
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-80 h-80 rounded-full bg-white opacity-10 animate-float"></div>
    <div class="absolute bottom-0 left-0 -ml-24 -mb-24 w-96 h-96 rounded-full bg-yellow-300 opacity-10 animate-float-delayed"></div>
    <div class="absolute top-1/2 left-1/4 w-4 h-4 rounded-full bg-white opacity-20 animate-ping"></div>

    <div class="relative z-10 px-6 py-12 sm:px-12 sm:py-20 text-center text-white">
        
        <?php if($config->first() && $config->first()->app_logo): ?>
        <div class="mb-6 transform transition duration-500 hover:scale-110">
            <img src="upload/{{ $config->first()->app_logo }}" alt="Logo" class="h-24 sm:h-32 mx-auto drop-shadow-xl">
        </div>
        <?php endif; ?>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 drop-shadow-lg transform transition duration-500 hover:scale-105">
            <span class="bg-clip-text text-transparent bg-gradient-to-b from-white to-green-100">
                งานศิลปหัตถกรรมนักเรียน
            </span>
        </h1>
        
        <div class="w-24 h-1 bg-yellow-400 mx-auto mb-6 rounded-full shadow-sm"></div>

        <p class="text-lg sm:text-2xl text-emerald-50 font-light max-w-4xl mx-auto leading-relaxed drop-shadow-md">
            "วิจิตรเมืองชุมพร อารยธรรมท่าแซะ พุทธศิลป์ปะทิว ถิ่นขวัญสืบศาสตร์ <br class="hidden md:inline"> 
            <span class="font-normal text-yellow-200">สร้างสรรค์คู่คุณธรรม ล้ำหน้าเทคโนโลยี</span> วิถีคุณภาพชุมพรหนึ่ง"
        </p>

        <div class="mt-8 flex justify-center gap-4 opacity-80">
            <div class="text-xs sm:text-sm border border-white/30 rounded-full px-4 py-1 flex items-center bg-white/10 backdrop-blur-sm">
                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                สพป.ชุมพร เขต 1
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
        <i class="ri-links-line text-secondary mr-2"></i> ลิงก์และเอกสารสำคัญ
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="https://drive.google.com/drive/folders/1xP0lMwMDzPHG3KEML2wmo2PT9MJx4gEU" target="_blank" 
            class="flex flex-col items-center justify-center p-8 bg-gray-50 border border-gray-200 rounded-xl hover:bg-primary hover:text-white transition-all duration-300 group shadow-sm hover:shadow-md min-h-[200px]">
            <i class="ri-folder-open-fill text-yellow-500 group-hover:text-white text-6xl mb-4 transition-colors"></i>
            <span class="font-bold text-center text-lg">เกณฑ์การแข่งขัน</span>
        </a>

        <a href="https://notebooklm.google.com/notebook/4fa8bf0a-65f9-497f-97f6-ad382590d334" target="_blank" 
            class="flex flex-col items-center justify-center p-8 bg-gray-50 border border-gray-200 rounded-xl hover:bg-secondary hover:text-white transition-all duration-300 group shadow-sm hover:shadow-md min-h-[200px]">
            <i class="ri-robot-fill text-blue-500 group-hover:text-white text-6xl mb-4 transition-colors"></i>
            <span class="font-bold text-center text-lg">สอบถาม AI เรื่องเกณฑ์</span>
        </a>

        <a href="https://docs.google.com/forms/d/e/1FAIpQLSem-nWKQ3a9FjvS_8oklPsDs4VGZ3bIe6dPEOBTNtYqnu9qrg/viewform" target="_blank" 
            class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-100 transition-all duration-300 min-h-[200px] group cursor-pointer">
            <span class="font-bold text-gray-700 mb-4 text-sm flex items-center text-center group-hover:text-gray-900 transition-colors">
                <i class="ri-qr-scan-2-line mr-2"></i> สแกนหรือคลิกทำแบบสอบถามความพึงพอใจ
            </span>
            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 transition-transform group-hover:scale-105 duration-300">
                <img src="imgs/qr-code.png" alt="QR Code แบบสอบถาม" class="w-28 h-28 object-contain">
            </div>
        </a>
        
    </div>
</div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 font-bold text-gray-700">
            <i class="ri-global-line text-primary mr-2"></i> เว็บไซต์การแข่งขันระดับเครือข่าย
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($schoolgroup as $s)
                    <a href="{{url('area/'.$s->id)}}" target="_self" class="flex items-center p-3 rounded-lg hover:bg-green-50 transition border border-transparent hover:border-green-100">
                        <span class="w-8 h-8 rounded-full bg-green-100 text-primary flex items-center justify-center mr-3">
                            <i class="ri-arrow-right-line"></i>
                        </span>
                        <span class="text-gray-700 font-medium group-hover:text-primary">เครือข่าย {{$s->schoolgroup_name}}</span>
                    </a>
                @empty
                    <div class="col-span-3 text-center text-gray-400 py-4">- ไม่พบข้อมูล -</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-md border-t-4 border-primary overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-white font-bold text-primary flex items-center justify-between">
                <span><i class="fas fa-bullhorn mr-2"></i> ประกาศจากศูนย์อำนวยการ</span>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Official</span>
            </div>
            <div class="p-4 overflow-x-auto">
                <table id="noticeTable1" class="w-full text-left border-collapse" style="width:100%">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm">
                            <th class="p-3 border-b border-gray-200">ผู้ประกาศ</th>
                            <th class="p-3 border-b border-gray-200">หัวข้อประกาศ</th>
                            <th class="p-3 border-b border-gray-200">เวลาที่ประกาศ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notice_1 as $b)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                <td class="p-3 align-top whitespace-nowrap">
                                    <span class="bg-primary text-white text-xs px-2 py-1 rounded">{{$b->notice_owner}}</span>
                                </td>
                                <td class="p-3 align-top">
                                    <a href="notice_detail/{{$b->id}}" class="text-gray-800 hover:text-secondary font-medium">
                                        {{$b->notice_text}}
                                    </a>
                                    @php
                                        $to_time = strtotime(now());
                                        $from_time = strtotime($b->created_at);
                                        if(round(abs($to_time - $from_time) / 60,2)<= 180) {
                                            echo '<span class="ml-2 bg-red-500 text-white px-1.5 py-0.5 rounded text-[10px] font-bold animate-pulse inline-block">NEW</span>';
                                        }
                                    @endphp
                                </td>
                                <td class="p-3 align-top text-xs text-gray-500 whitespace-nowrap">
                                    <i class="far fa-clock mr-1"></i> {{Datethai6($b->created_at)}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border-t-4 border-secondary overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-white font-bold text-secondary flex items-center justify-between">
                <span><i class="fas fa-broadcast-tower mr-2"></i> ประกาศจากศูนย์การแข่งขัน</span>
                <span class="text-xs bg-pink-100 text-pink-800 px-2 py-1 rounded-full">Centers</span>
            </div>
            <div class="p-4 overflow-x-auto">
                <table id="noticeTable2" class="w-full text-left border-collapse" style="width:100%">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm">
                            <th class="p-3 border-b border-gray-200">ผู้ประกาศ</th>
                            <th class="p-3 border-b border-gray-200">หัวข้อประกาศ</th>
                            <th class="p-3 border-b border-gray-200">เวลาที่ประกาศ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notice_2 as $b)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                <td class="p-3 align-top whitespace-nowrap">
                                    <span class="bg-secondary text-white text-xs px-2 py-1 rounded">{{$b->notice_owner}}</span>
                                </td>
                                <td class="p-3 align-top">
                                    <a href="notice_detail/{{$b->id}}" class="text-gray-800 hover:text-primary font-medium">
                                        {{$b->notice_text}}
                                    </a>
                                    @php
                                        $to_time = strtotime(now());
                                        $from_time = strtotime($b->created_at);
                                        if(round(abs($to_time - $from_time) / 60,2)<= 180) {
                                            echo '<span class="ml-2 bg-red-500 text-white px-1.5 py-0.5 rounded text-[10px] font-bold animate-pulse inline-block">NEW</span>';
                                        }
                                    @endphp
                                </td>
                                <td class="p-3 align-top text-xs text-gray-500 whitespace-nowrap">
                                    <i class="far fa-clock mr-1"></i> {{Datethai6($b->created_at)}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-100 font-bold text-gray-800">
            <i class="fas fa-poll text-blue-500 mr-2"></i> ผลการแข่งขันล่าสุด
        </div>
        <div class="p-4">
            <div class="space-y-2">
                @forelse($firstactivity as $b)
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <a href="viewactivitydetail/{{$b->id}}" class="text-gray-700 hover:text-secondary hover:underline truncate">
                            {{$b->activity_name??''}} {{$b->getclass->class_name ??''}} 
                            <span class="text-gray-400 mx-1">|</span> 
                            <span class="text-gray-500">{{$b->getactivitysubject->subjectgroup_name ?? ''}} </span>
                            <i class="fas fa-arrow-right text-xs text-gray-300 mx-2"></i>
                            <span class="text-blue-600">{{$b->getactivitybranch->branch_name ??''}}</span>
                        </a>
                        <?php 
                            $to_time = strtotime(now());
                            $from_time = strtotime($b->updated_at);
                            if(round(abs($to_time - $from_time) / 60,2)<= 10){
                                echo '<span class="ml-2 bg-red-500 text-white px-1.5 rounded text-[10px] font-bold">New</span>';
                            };
                        ?>
                    </div>
                @empty
                    <div class="text-gray-400 text-center">- ไม่พบข้อมูล -</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
        <div class="px-6 py-5 bg-gray-800 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fas fa-medal text-yellow-400 mr-2"></i> สรุปเหรียญรางวัล TOP 10</h3>
            <a href="./viewall" class="text-xs bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded text-white transition">ดูทั้งหมด</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="table1">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-center">อันดับ</th>
                        <th class="px-6 py-3">โรงเรียน</th>
                        <th class="px-6 py-3 text-center text-yellow-600"><i class="fas fa-medal"></i> ทอง</th>
                        <th class="px-6 py-3 text-center text-gray-500"><i class="fas fa-medal"></i> เงิน</th>
                        <th class="px-6 py-3 text-center text-yellow-800"><i class="fas fa-medal"></i> ทองแดง</th>
                        <th class="px-6 py-3 text-center">รวม</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $num1 = 1; ?>
                    @forelse($dbrank as $l)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-center font-bold text-gray-500">{{$num1}}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <a href="showschooldetail/{{$l->school_name}}/0" class="hover:text-primary hover:underline">
                                    {{$selectschool[$l->school_name]}}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-yellow-600 bg-yellow-50/50">{{$l->Gold}}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-500">{{$l->Silver}}</td>
                            <td class="px-6 py-4 text-center font-bold text-yellow-800">{{$l->Bronce}}</td>
                            <td class="px-6 py-4 text-center font-bold text-black ">{{$l->Gold+$l->Silver+$l->Bronce}}</td>
                        </tr>
                        <?php $num1++; ?>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-400">- ไม่พบข้อมูล -</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700">
                <i class="fas fa-book text-primary mr-2"></i> ผลการแข่งขันตามกลุ่มสาระ
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white border-b border-gray-100 text-gray-500">
                        <tr>
                            <th class="px-4 py-2 text-left">กลุ่มสาระ</th>
                            <th class="px-4 py-2 text-center">รายการ</th>
                            <th class="px-4 py-2 text-center">ทีม</th>
                            <th class="px-4 py-2 text-center text-green-600">เสร็จสิ้น</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($subjectgroup as $l)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><a href="./viewactivity/{{$l->id}}" class="text-gray-700 hover:text-primary">{{$l->subjectgroup_name}}</a></td>
                            <td class="px-4 py-2 text-center text-gray-400">{{ isset($activitycount[$l->id]) ? $activitycount[$l->id] : 0 }}</td>
                            <td class="px-4 py-2 text-center text-gray-400">{{ $subjectTeamCount[$l->id] ?? 0 }} ({{ $studentBySubject[$l->id] ?? 0 }} คน)</td>
                            <td class="px-4 py-2 text-center font-bold text-green-600">{{ isset($activityfinish[$l->id]) ? $activityfinish[$l->id] : 0 }}</td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <div class="p-2">{{ $subjectgroup->links()}}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700">
                <i class="fas fa-school text-secondary mr-2"></i> จำนวนโรงเรียนในเครือข่าย
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white border-b border-gray-100 text-gray-500">
                        <tr>
                            <th class="px-4 py-2 text-left">เครือข่ายสถานศึกษา</th>
                            <th class="px-4 py-2 text-center">จำนวน</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($schoolgroup as $l)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><a href="showschool/{{$l->id}}" class="text-gray-700 hover:text-secondary">เครือข่าย{{$l->schoolgroup_name}}</a></td>
                            <td class="px-4 py-2 text-center font-bold text-gray-600">{{ isset($schoolgroupcount[$l->id]) ? $schoolgroupcount[$l->id] : 0 }} โรงเรียน ({{ $activityBySchoolGroup[$l->id] ?? 0 }} กิจกรรม)</td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <div class="p-2">{{ $schoolgroup->links()}}</div>
            </div>
        </div>
    </div>

   <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-red-500"></i> ศูนย์การแข่งขัน
        </h3>
    </div>

    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @forelse($Branch as $l)
            <div class="flex flex-col bg-white border border-gray-200 rounded-lg p-3 hover:border-blue-400 hover:shadow-md transition-all group">
                <a href="viewbranch/{{$l->id}}" class="text-sm font-bold text-gray-800 mb-2 truncate group-hover:text-blue-600 transition-colors">
                    {{$l->branch_name ?? 'N/A'}}
                </a>

                <div class="flex flex-wrap items-center gap-y-2 gap-x-3">
                    <div class="flex items-center gap-1.5" title="กิจกรรม">
                        <i class="fas fa-cubes text-blue-500 text-[10px]"></i>
                        <span class="text-xs font-semibold text-gray-600">กิจกรรม {{ number_format($branchactivitycount[$l->id] ?? 0) }}</span>
                    </div>

                    <div class="flex items-center gap-1.5" title="นักเรียน">
                        <i class="fas fa-user-graduate text-emerald-500 text-[10px]"></i>
                        <span class="text-xs font-semibold text-gray-600">นักเรียน {{ number_format($studentByBranch[$l->id] ?? 0) }}</span>
                    </div>

                    <div class="flex items-center gap-1.5" title="ครูผู้ควบคุม">
                        <i class="fas fa-chalkboard-teacher text-indigo-500 text-[10px]"></i>
                        <span class="text-xs font-semibold text-gray-600">ครู {{ number_format($teacherByBranch[$l->id] ?? 0) }}</span>
                    </div>

                    <div class="flex items-center gap-1.5" title="กรรมการตัดสิน">
                        <i class="fas fa-gavel text-amber-500 text-[10px]"></i>
                        <span class="text-xs font-semibold text-gray-600">กรรมการตัดสิน {{ number_format($judgeCountByBranch[$l->id] ?? 0) }}</span>
                    </div>

                    <div class="flex items-center gap-1.5" title="กรรมการดำเนินงาน">
                        <i class="fas fa-cog text-purple-500 text-[10px]"></i>
                        <span class="text-xs font-semibold text-gray-600">กรรมการดำเนินงาน {{ number_format($staffCountByBranch[$l->id] ?? 0) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-6 text-center text-gray-400 text-xs italic">
                - ไม่พบข้อมูล -
            </div>
        @endforelse
    </div>
</div>

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-green-600 mb-12">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fas fa-chart-pie mr-2"></i> สถิติการแข่งขัน</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-blue-50 rounded-lg text-center border border-blue-100">
                    <div class="text-blue-500 text-sm font-bold mb-1">นักเรียน</div>
                    <div class="text-2xl font-extrabold text-blue-700">{{number_format($countstudent)}}</div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg text-center border border-green-100">
                    <div class="text-green-500 text-sm font-bold mb-1">ครู</div>
                    <div class="text-2xl font-extrabold text-green-700">{{number_format($countteacher)}}</div>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg text-center border border-yellow-100">
                    <div class="text-yellow-600 text-sm font-bold mb-1">กรรมการตัดสิน</div>
                    <div class="text-2xl font-extrabold text-yellow-700">{{number_format($countjow)}}</div>
                </div>
                <div class="p-4 bg-red-50 rounded-lg text-center border border-red-100">
                    <div class="text-red-500 text-sm font-bold mb-1">กรรมการดำเนินงาน</div>
                    <div class="text-2xl font-extrabold text-red-700">{{number_format($countjow2)}}</div>
                </div>
            </div>

            <div class="relative h-64 w-full">
                <canvas id="statChart"></canvas>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>   

<script>
$(function () {
    // ----------------------------------------------------
    // ตั้งค่า DataTables สำหรับประกาศทั้ง 2 ตาราง
    // ----------------------------------------------------
    var dtConfig = {
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        },
        "order": [[ 2, "desc" ]], // เรียงตามเวลาล่าสุด (คอลัมน์ที่ 3)
        "pageLength": 10, // กำหนดให้แสดงแค่ 5 รายการต่อหน้า จะได้ไม่เปลืองพื้นที่
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "ทั้งหมด"]],
        "responsive": true
    };
    
    $('#noticeTable1').DataTable(dtConfig);
    $('#noticeTable2').DataTable(dtConfig);


    // ----------------------------------------------------
    // ส่วน Chart.js ของเดิม
    // ----------------------------------------------------
    var areaChartData = {
      labels  : ['นักเรียน', 'ครู', 'กรรมการตัดสิน', 'กรรมการดำเนินงาน'],
      datasets: [
        {
          label               : 'จำนวนคน',
          backgroundColor     : ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'], // Blue, Green, Yellow, Red
          data                : [{{$countstudent}}, {{$countteacher}}, {{$countjow}}, {{$countjow2}}], 
          borderWidth: 5,
          hoverOffset: 15
        },
      ]
    }

    var barChartCanvas = $('#statChart').get(0).getContext('2d')
    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      cutoutPercentage        : 40,
      legend: { 
        display: true,
        position: 'right',
        labels: {
            fontFamily: 'Sarabun',
            fontStyle: 'bold',
            padding: 20
        }
      },
      animation: {
        animateScale: true,
        animateRotate: true
      }
    }

    new Chart(barChartCanvas, {
      type: 'doughnut',
      data: areaChartData,
      options: barChartOptions
    })
})
</script>
</div>
@endsection