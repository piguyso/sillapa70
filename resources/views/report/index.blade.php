@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-secondary to-pink-500 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 text-center md:text-left">
            <h1 class="text-2xl md:text-4xl font-black mb-4 tracking-tight uppercase leading-tight">
                <i class="ri-medal-fill mr-2"></i>ระบบพิมพ์เกียรติบัตรออนไลน์
            </h1>
            <p class="text-pink-50 text-lg md:text-xl opacity-90 font-medium">
                {{ session('app_name')}} ระดับเครือข่ายสถานศึกษา {{session()->get('schoolgroup')}} ปีการศึกษา {{ session('app_year') }}
            </p>
        </div>
        <i class="ri-award-fill absolute -right-10 -bottom-10 text-[18rem] opacity-10 rotate-12"></i>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-emerald-50 px-8 py-5 border-b border-emerald-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ri-user-heart-fill text-xl"></i>
                </div>
                <h3 class="text-lg font-black text-emerald-800">เกียรติบัตรนักเรียน / ครูผู้ควบคุม</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-left datatable-simple">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-400 tracking-widest border-b">
                            <th class="pb-3 w-12 text-center">#</th>
                            <th class="pb-3">กลุ่มสาระการเรียนรู้</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $n1 = 1; @endphp
                        @forelse($subjectgroup as $l)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 text-center font-bold text-gray-400">{{$n1++}}</td>
                            <td class="py-4 font-bold text-gray-700">
                                <a href="./viewactivity/{{$l->id}}" class="hover:text-primary flex items-center justify-between group">
                                    {{$l->subjectgroup_name}}
                                    <i class="ri-arrow-right-line opacity-0 group-hover:opacity-100 transition-all text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-400 italic">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-blue-50 px-8 py-5 border-b border-blue-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ri-judge-fill text-xl"></i>
                </div>
                <h3 class="text-lg font-black text-blue-800">เกียรติบัตรกรรมการตัดสิน</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-left datatable-simple">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-400 tracking-widest border-b">
                            <th class="pb-3 w-12 text-center">#</th>
                            <th class="pb-3">กลุ่มสาระการเรียนรู้</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $n2 = 1; @endphp
                        @forelse($subjectgroup as $l)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 text-center font-bold text-gray-400">{{$n2++}}</td>
                            <td class="py-4 font-bold text-gray-700">
                                <a href="./reportviewactivity/{{$l->id}}" class="hover:text-blue-600 flex items-center justify-between group">
                                    {{$l->subjectgroup_name}}
                                    <i class="ri-arrow-right-line opacity-0 group-hover:opacity-100 transition-all text-blue-600"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-400 italic">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-orange-50 px-8 py-5 border-b border-orange-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ri-settings-4-fill text-xl"></i>
                </div>
                <h3 class="text-lg font-black text-orange-800">เกียรติบัตรกรรมการดำเนินงาน</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-left datatable-simple">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-400 tracking-widest border-b">
                            <th class="pb-3 w-12 text-center">#</th>
                            <th class="pb-3">ศูนย์การแข่งขัน / เครือข่าย</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $n3 = 1; @endphp
                        @forelse($branch as $l)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 text-center font-bold text-gray-400">{{$n3++}}</td>
                            <td class="py-4 font-bold text-gray-700">
                                <a href="./reportviewjow/{{$l->id}}" class="hover:text-orange-600 flex items-center justify-between group">
                                    {{$l->branch_name}}
                                    <i class="ri-arrow-right-line opacity-0 group-hover:opacity-100 transition-all text-orange-600"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-10 text-center text-gray-400 italic">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-indigo-900 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-black mb-4 flex items-center gap-2">
                    <i class="ri-information-fill text-yellow-400"></i> คำแนะนำการใช้งาน
                </h3>
                <ul class="space-y-3 text-indigo-100 text-sm">
                    <li class="flex gap-2"><i class="ri-checkbox-circle-line text-yellow-400"></i> เลือกกลุ่มสาระที่ต้องการเพื่อค้นหารายชื่อ</li>
                    <li class="flex gap-2"><i class="ri-checkbox-circle-line text-yellow-400"></i> ระบบจะเจนไฟล์เกียรติบัตรเป็นรูปแบบ PDF</li>
                    <li class="flex gap-2"><i class="ri-checkbox-circle-line text-yellow-400"></i> หากไม่พบรายชื่อ กรุณาติดต่อผู้ดูแลระบบเครือข่าย</li>
                </ul>
            </div>
            <i class="ri-questionnaire-line absolute -right-5 -bottom-5 text-9xl opacity-10 rotate-12"></i>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // ใช้ DataTable แบบเรียบง่ายสำหรับค้นหาภายใน Card
        $('.datatable-simple').DataTable({
            "pageLength": 10,
            "dom": 'f<"my-4">rtp', // โชว์แค่ Search (f), Table (t), Pagination (p)
            "language": {
                "search": "",
                "searchPlaceholder": "🔍 ค้นหากลุ่มสาระ...",
                "paginate": { "next": "→", "previous": "←" }
            }
        });
    });
</script>
@endsection