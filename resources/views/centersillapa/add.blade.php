@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">
            <i class="ri-book-read-line mr-2"></i>รายการแข่งขันตามสาระการเรียนรู้
        </h1>
        <p class="text-green-50 text-lg opacity-90">{{ session('app_name') }} | {{ session('app_area') }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-slate-800">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="ri-list-check-2 text-primary"></i>
                ข้อมูลสรุปแยกตามกลุ่มสาระการเรียนรู้
            </h2>
        </div>

        <div class="p-6">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-lg">
                    <i class="ri-checkbox-circle-line text-xl"></i>
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="px-6 py-4 font-bold text-center w-20">#</th>
                            <th class="px-6 py-4 font-bold">สาระการเรียนรู้ / กลุ่มสาระ</th>
                            <th class="px-6 py-4 font-bold text-center">รายการทั้งหมด</th>
                            <th class="px-6 py-4 font-bold text-center text-emerald-600">ประกาศผลแล้ว</th>
                            <th class="px-6 py-4 font-bold text-center w-32">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $x = 1; @endphp
                        @forelse($activityinbranch as $l)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                {{ $x++ }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="centerviewactivity/{{$l[0]->activity_subject}}" class="font-bold text-gray-800 hover:text-secondary transition-colors text-lg flex items-center gap-2">
                                    <i class="ri-folder-open-line text-primary opacity-50 group-hover:opacity-100 transition-opacity"></i>
                                    {{$l[0]->getactivitysubject->subjectgroup_name}}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-700 px-4 py-1.5 rounded-xl text-sm font-bold border border-blue-100">
                                    {{ isset($activitycount[$l[0]->activity_subject]) ? $activitycount[$l[0]->activity_subject] : 0 }} รายการ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $activeCount = isset($activityactivecount[$l[0]->activity_subject]) ? $activityactivecount[$l[0]->activity_subject] : 0;
                                    $totalCount = isset($activitycount[$l[0]->activity_subject]) ? $activitycount[$l[0]->activity_subject] : 0;
                                    $isComplete = ($activeCount > 0 && $activeCount == $totalCount);
                                @endphp
                                <span class="{{ $isComplete ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-orange-50 text-orange-600 border-orange-100' }} px-4 py-1.5 rounded-xl text-sm font-bold border">
                                    <i class="{{ $isComplete ? 'ri-checkbox-circle-fill' : 'ri-time-line' }} mr-1"></i>
                                    {{ $activeCount }} รายการ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="centerviewactivity/{{$l[0]->activity_subject}}" 
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-gray-200 text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <i class="ri-search-eye-line text-xl"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/30">
                                <i class="ri-inbox-line text-4xl block mb-2"></i>
                                ไม่พบข้อมูลกลุ่มสาระการเรียนรู้
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm flex items-center gap-3 animate-pulse">
            <i class="ri-information-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif
</div>
@endsection