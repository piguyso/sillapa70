@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-gradient-to-r from-primary to-emerald-500 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ session('app_name').' '.session('app_group') }}</h1>
        <p class="text-green-50 text-lg opacity-90">{{ session('app_area') }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="../" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-colors shadow-sm">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <h2 class="text-xl font-bold text-gray-800">
                    {{$activity[0]->getactivitybranch->branch_name ??''}} 
                    <span class="text-gray-400 mx-2 text-sm"><i class="ri-arrow-right-s-line"></i></span> 
                    {{$subjectgroup->subjectgroup_name}}
                </h2>
            </div>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-lg">
                    <i class="ri-checkbox-circle-line text-xl"></i>
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="table1">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider border-b border-gray-200">
                            <th class="px-4 py-4 font-semibold text-center w-16">#</th>
                            <th class="px-4 py-4 font-semibold">ชื่อกิจกรรม</th>
                            <th class="px-4 py-4 font-semibold">ช่วงชั้น</th>
                            <th class="px-4 py-4 font-semibold text-center">จำนวนทีม</th>
                            <th class="px-4 py-4 font-semibold">{{$activity[0]->getactivitybranch->branch_name ??'ศูนย์การแข่งขัน'}}</th>
                            <th class="px-4 py-4 font-semibold text-center">สถานะ</th>
                            <th class="px-4 py-4 font-semibold text-center w-20">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $x = ($activity->currentPage() - 1) * $activity->perPage();
                        @endphp
                        
                        @forelse($activity as $l)
                        <tr class="hover:bg-gray-50 transition-colors group" x-data="{ openModal: false }">
                            <td class="px-4 py-4 text-center text-gray-400">@php $x++; echo $x; @endphp.</td>
                          <td class="px-4 py-4">
                                <a href="../viewactivitydetail/{{$l->id}}" class="flex items-center gap-2 font-bold text-gray-800 hover:text-secondary transition-colors">
                                    <span class="line-clamp-2">{{$l->activity_name}}</span>
                                    
                                    @if($l->activity_status == 1)
                                       
                                            <i class="ri-checkbox-circle-fill text-green-500"></i>
                                       
                                    @endif
                                </a>
                            </td>
                            <td class="px-4 py-4">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                    {{$l->getclass->class_name}}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-gray-600">
                                {{ isset($schoolcountactivity[$l->id]) ? $schoolcountactivity[$l->id]." ทีม" : "0 ทีม" }}
                            </td>
                            <td class="px-4 py-4 text-sm leading-relaxed text-gray-500">
                                <div class="flex items-center gap-1"><i class="ri-map-pin-2-line text-primary"></i> {{$l->activity_branchroom}}</div>
                                <div class="flex items-center gap-1"><i class="ri-calendar-line text-primary"></i> {{Datethai($l->activity_day)}}</div>
                                <div class="flex items-center gap-1"><i class="ri-time-line text-primary"></i> {{$l->activity_time}}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $status = getactivitystatus($l->activity_status);
                                    $statusClass = $l->activity_status == 1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                                @endphp
                               
                                    {{ $status }} 
                               
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="openModal = true" class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="ri-information-line text-xl"></i>
                                </button>

                                <div x-show="openModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="openModal = false"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                                        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-primary">
                                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                                <h3 class="text-xl font-bold text-gray-800">{{$l->activity_name}}</h3>
                                                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600"><i class="ri-close-line text-2xl"></i></button>
                                            </div>
                                            <div class="px-6 py-6">
                                                <div class="space-y-4">
                                                    <div class="flex justify-between border-b border-gray-50 pb-2">
                                                        <span class="text-gray-500">กลุ่มสาระการเรียนรู้</span>
                                                        <span class="font-bold text-gray-800">{{$l->getactivitysubject->subjectgroup_name}}</span>
                                                    </div>
                                                    <div class="flex justify-between border-b border-gray-50 pb-2">
                                                        <span class="text-gray-500">ระดับชั้น</span>
                                                        <span class="font-bold text-secondary">{{$l->getclass->class_name}}</span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4 pt-2">
                                                        <div class="bg-gray-50 p-3 rounded-xl">
                                                            <div class="text-xs text-gray-400 uppercase tracking-wider">นักเรียน/ทีม</div>
                                                            <div class="font-bold text-lg text-primary">{{$l->activity_studennumber}} คน</div>
                                                        </div>
                                                        <div class="bg-gray-50 p-3 rounded-xl">
                                                            <div class="text-xs text-gray-400 uppercase tracking-wider">ครู/ทีม</div>
                                                            <div class="font-bold text-lg text-primary">{{$l->activity_teachernumber}} คน</div>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2 text-sm text-gray-600 bg-blue-50 p-4 rounded-xl">
                                                        <div class="flex items-center gap-2 font-medium"><i class="ri-map-pin-2-fill text-blue-500"></i> {{$l->activity_branchroom}}</div>
                                                        <div class="flex items-center gap-2 font-medium"><i class="ri-calendar-event-fill text-blue-500"></i> {{Datethai($l->activity_day)}} | {{$l->activity_time}} น.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 bg-gray-50 text-right">
                                                <button @click="openModal = false" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition">ปิดหน้าต่าง</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400 italic">ไม่พบข้อมูลกิจกรรม</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $activity->links() }}
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm animate-bounce">
            <i class="ri-information-fill mr-2"></i>
            <strong>{{Session::get('massage')}}</strong>
        </div>
    @endif
</div>
@endsection