@extends('layouts.my')

@section('title', 'ทดสอบดีไซน์ใหม่')
@section('header_title', 'ภาพรวมระบบ (Design ใหม่)')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <h3 class="text-gray-500 text-sm font-medium">นักเรียนทั้งหมด</h3>
            <div class="flex items-end gap-2 mt-2">
                <span class="text-3xl font-bold text-slate-800">1,240</span>
                <span class="text-green-500 text-sm mb-1">คน</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <h3 class="text-gray-500 text-sm font-medium">บุคลากร</h3>
            <div class="flex items-end gap-2 mt-2">
                <span class="text-3xl font-bold text-slate-800">85</span>
                <span class="text-blue-500 text-sm mb-1">คน</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <h3 class="text-gray-500 text-sm font-medium">งบประมาณ</h3>
            <div class="flex items-end gap-2 mt-2">
                <span class="text-3xl font-bold text-slate-800">50,000</span>
                <span class="text-gray-400 text-sm mb-1">บาท</span>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-semibold text-gray-800">รายการล่าสุด</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium">ชื่อรายการ</th>
                        <th class="px-6 py-3 font-medium">วันที่</th>
                        <th class="px-6 py-3 font-medium">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">สมัครเรียนใหม่</td>
                        <td class="px-6 py-4 text-gray-500">17 ก.พ. 2569</td>
                        <td class="px-6 py-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">อนุมัติแล้ว</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">แจ้งซ่อมคอมพิวเตอร์</td>
                        <td class="px-6 py-4 text-gray-500">16 ก.พ. 2569</td>
                        <td class="px-6 py-4"><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">รอดำเนินการ</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection