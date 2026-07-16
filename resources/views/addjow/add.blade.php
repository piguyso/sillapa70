@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800">

    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="../jowviewactivity/{{$activity->activity_subject}}" class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center hover:bg-white/40 transition-all border border-white/30 shadow-sm">
                    <i class="ri-arrow-left-line text-2xl text-white"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight uppercase tracking-tight">จัดการรายชื่อกรรมการ</h1>
                    <p class="text-green-50 text-lg opacity-90 font-medium italic">กิจกรรม: {{$activity->activity_name}}</p>
                </div>
            </div>
        </div>
    </div>

    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-r-lg shadow-sm">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 order-2 lg:order-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                        <i class="ri-group-line text-primary"></i> รายชื่อกรรมการในระบบ
                    </h2>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="table1">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">
                                    <th class="px-6 py-4 text-center w-16">#</th>
                                    <th class="px-6 py-4">คำนำหน้า</th>
                                    <th class="px-6 py-4">ชื่อ-นามสกุล</th>
                                    <th class="px-6 py-4">ตำแหน่ง</th>
                                    <th class="px-6 py-4 text-center w-32">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @php $index = 0 @endphp
                                @forelse($jow as $l)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-400 font-bold">
                                        {{ ++$index }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium">{{$l->getjowtitle->title}}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 leading-tight">{{$l->jow_name}} {{$l->jow_lastname}}</div>
                                        <div class="text-[10px] text-primary uppercase font-bold tracking-tighter">กรรมการตัดสิน</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-bold border border-blue-100">
                                            {{$l->jowclass}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{'../jow/'.$l->id.'/edit'}}" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form id="delform{{$l->id}}" method="post" action="{{ route('jow.destroy',$l->id)}}" class="inline">
                                                <input type="hidden" name="_method" value="DELETE"/>
                                                {!!csrf_field()!!}
                                                <button type="button" onclick="DelConfirm({{$l->id}})" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                        <i class="ri-inbox-line text-4xl block mb-2"></i> ยังไม่มีรายชื่อกรรมการสำหรับกิจกรรมนี้
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 order-1 lg:order-2">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden sticky top-8">
                <div class="bg-primary px-8 py-5 text-white">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="ri-user-add-line"></i> เพิ่มกรรมการใหม่
                    </h2>
                </div>
                <div class="p-8">
                    <form method="POST" action="{{ Route('jow.store') }}" class="space-y-5">
                        {{ csrf_field() }}
                        <input type="hidden" name="activity" value="{{$id}}"/>
                        <input type="hidden" name="school" value=""/>
                        <input type="hidden" name="jow_type" value="0"/>

                        <div>
                            <label for="jowclass" class="block text-sm font-bold text-gray-700 mb-2">ตำแหน่งในทีมกรรมการ</label>
                            <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm font-medium" id="jowclass" name="jowclass">
                                <option value="ประธานกรรมการ">ประธานกรรมการ</option>
                                <option value="กรรมการ" selected>กรรมการ</option>
                                <option value="กรรมการและเลขานุการ">กรรมการและเลขานุการ</option>
                            </select>
                            @if ($errors->has('jowclass'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('jowclass') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">คำนำหน้าชื่อ</label>
                            <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm font-medium" id="title" name="title">
                                @foreach($title as $l)
                                    <option value="{{$l->title_id}}">{{$l->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('title'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('title') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">ชื่อ (ไม่ต้องระบุคำนำหน้า)</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm font-bold"
                                placeholder="เช่น สมชาย">
                            @if ($errors->has('name'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="lastname" class="block text-sm font-bold text-gray-700 mb-2">นามสกุล</label>
                            <input id="lastname" type="text" name="lastname" value="{{ old('lastname') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm font-bold"
                                placeholder="เช่น รักเรียน">
                            @if ($errors->has('lastname'))
                                <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $errors->first('lastname') }}</p>
                            @endif
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-primary text-white font-black py-4 rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-sm">
                                <i class="ri-save-line text-lg"></i>
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function DelConfirm(id) {
        if (confirm('คุณต้องการลบรายชื่อกรรมการท่านนี้ใช่หรือไม่?')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection