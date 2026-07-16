@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">
            <i class="ri-edit-box-line mr-2"></i>แก้ไขข้อมูลการแข่งขัน
        </h1>
        <p class="text-green-50 opacity-90">จัดการรายละเอียดสถานที่และวันเวลาสำหรับการแข่งขัน</p>
    </div>

    @if(Session::has('massage'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3 rounded-r-lg shadow-sm">
            <i class="ri-checkbox-circle-fill text-xl"></i>
            <span class="font-bold">{{ Session::get('massage') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">รายละเอียดกิจกรรม</h2>
        </div>

        <form action="{{ Route('centersillapa.update', $activity->id) }}" method="POST" class="p-8 space-y-6">
            {{ csrf_field() }}
            <input type="hidden" name="subjectgroupid" value="{{$activity->activity_subject}}"/>
            <input type="hidden" name="_method" value="put">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อกิจกรรมแข่งขัน</label>
                <input type="text" value="{{$activity->activity_name}}" readonly 
                    class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ช่วงชั้น / ระดับชั้น</label>
                    <select disabled class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed appearance-none">
                        @foreach($class as $i)
                            <option value="{{$i->id}}" @if($activity->activity_class == $i->id) selected @endif >{{$i->class_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">จำนวนนักเรียนที่เข้าร่วม (คน)</label>
                    <select disabled class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed appearance-none">
                        <option value="999">ไม่จำกัดจำนวน</option>
                        @for($i=1;$i<=50;$i++)
                            <option value="{{$i}}" @if($activity->activity_studennumber == $i) selected @endif>{{$i}}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">จำนวนครูต่อทีม</label>
                    <select disabled class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed appearance-none">
                        <option value="999">ไม่จำกัดจำนวน</option>
                        @for($i=1;$i<=10;$i++)
                            <option value="{{$i}}" @if($activity->activity_teachernumber == $i) selected @endif>{{$i}}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">จำนวนทีมที่เข้ารอบ</label>
                    <select disabled class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed appearance-none">
                        <option value="999">ไม่จำกัดจำนวน</option>
                        @for($i=1;$i<=5;$i++)
                            <option value="{{$i}}" @if($activity->activity_teamnumber == $i) selected @endif>{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                <div class="md:col-span-2">
                    <h3 class="text-blue-700 font-bold flex items-center gap-2 mb-2">
                        <i class="ri-map-pin-2-fill"></i> ข้อมูลสถานที่และวันเวลาแข่งขัน
                    </h3>
                </div>

                <div class="md:col-span-2">
                    <label for="activity_branchroom" class="block text-sm font-bold text-gray-700 mb-2">สถานที่ / อาคาร / ห้องแข่งขัน</label>
                    <input id="activity_branchroom" name="activity_branchroom" type="text" 
                        value="{{$activity->activity_branchroom}}"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                        placeholder="ระบุสถานที่จัดการแข่งขัน">
                    @if ($errors->has('activity_branchroom'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('activity_branchroom') }}</p>
                    @endif
                </div>

                <div>
                    <label for="activity_day" class="block text-sm font-bold text-gray-700 mb-2">วันที่แข่งขัน</label>
                    <select id="activity_day" name="activity_day" 
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none appearance-none">
                        @foreach($dayofsillapa as $i)
                            <option value="{{$i->dayofsillapa}}" @if($i->dayofsillapa == $activity->activity_day) selected @endif>{{datethai($i->dayofsillapa)}}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="activity_time" class="block text-sm font-bold text-gray-700 mb-2">เวลาแข่งขัน</label>
                    <input id="activity_time" name="activity_time" type="text" 
                        value="{{$activity->activity_time}}"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                        placeholder="เช่น 09:00 - 12:00 น.">
                    @if ($errors->has('activity_time'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->first('activity_time') }}</p>
                    @endif
                </div>
            </div>

            <div class="max-w-xs">
                <label class="block text-sm font-bold text-gray-700 mb-2">สถานะกิจกรรม</label>
                <select disabled class="w-full bg-gray-50 border border-gray-200 text-gray-500 rounded-xl px-4 py-3 cursor-not-allowed appearance-none">
                    <option value="1" @if($activity->activity_status==1) selected @endif>ประกาศผลแล้ว</option>
                    <option value="0" @if($activity->activity_status==0) selected @endif>ยังไม่ได้ประกาศผล</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="../centersillapa" class="px-6 py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                    ยกเลิก
                </a>
                <button type="submit" class="px-10 py-3 bg-primary text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-200">
                    บันทึกข้อมูล (Update)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection