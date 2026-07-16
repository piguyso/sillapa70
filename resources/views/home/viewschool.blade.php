@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-r from-primary to-emerald-500 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ session('app_name').' '.session('app_group') }}</h1>
        <p class="text-green-50 text-lg opacity-90">{{ session('app_area') }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-4">
            <a href="../" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all shadow-sm">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-gray-800">
                เครือข่ายสถานศึกษา{{ $schoolname[0]->getschoolgroup->schoolgroup_name ?? '' }}
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
                            <th class="px-6 py-4 font-bold">ชื่อโรงเรียน</th>
                            <th class="px-6 py-4 font-bold text-center">จำนวนทีมที่สมัคร</th>
                            <th class="px-6 py-4 font-bold text-center w-32">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $x = ($schoolname->currentPage() - 1) * $schoolname->perPage();
                        @endphp
                        
                        @forelse($schoolname as $l)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">
                                @php $x++; echo $x; @endphp.
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm">
                                        <i class="ri-school-line text-xl"></i>
                                    </div>
                                    <a href="../showschooldetail/{{$l->id}}/{{$l->school_group}}" class="font-bold text-gray-800 hover:text-secondary transition-colors text-lg">
                                        {{$l->school_name}}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $count = isset($schoolcount[$l->id]) ? $schoolcount[$l->id] : 0;
                                    $bgClass = $count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400';
                                @endphp
                                <span class="{{ $bgClass }} px-4 py-1 rounded-full text-sm font-bold inline-flex items-center gap-1">
                                    <i class="ri-team-line"></i> {{ $count }} ทีม
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="../showschooldetail/{{$l->id}}/{{$l->school_group}}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-secondary hover:text-white hover:border-secondary transition-all shadow-sm">
                                    ดูข้อมูล
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/50">
                                <i class="ri-inbox-line text-4xl block mb-2"></i> ไม่พบข้อมูลโรงเรียนในเครือข่ายนี้
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $schoolname->links() }}
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