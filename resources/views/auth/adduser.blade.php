@extends('layouts.app')

@section('content')
<style>
    #loading-overlay {
        @apply fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 backdrop-blur-sm transition-opacity duration-500;
    }
    .spinner {
        @apply w-16 h-16 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin;
    }
</style>

<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner mb-4"></div>
        <p class="text-emerald-700 font-bold animate-pulse text-lg">กำลังโหลดข้อมูลผู้ใช้ระบบ...</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-slate-800 font-sarabun">
    
    <div class="bg-gradient-to-r from-primary to-emerald-600 rounded-3xl shadow-xl p-8 md:p-12 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight italic uppercase">
                User Management
            </h1>
            <p class="text-green-50 text-lg md:text-xl opacity-90 font-medium">
                <i class="ri-user-settings-line mr-2"></i>จัดการข้อมูลผู้ใช้งานและสิทธิ์การเข้าถึง
            </p>
        </div>
        <i class="ri-shield-user-line absolute -right-10 -bottom-10 text-[15rem] opacity-10 rotate-12"></i>
    </div>

    @if (session('status') || Session::has('massage'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 flex items-center gap-3 rounded-xl shadow-sm font-bold">
            <i class="ri-checkbox-circle-fill text-xl"></i> {{ session('status') ?: Session::get('massage') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-team-line text-primary"></i> รายชื่อผู้ใช้งานในระบบ
                </h2>
            </div>
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3" id="table_users">
                        <thead>
                            <tr class="text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                                <th class="px-6 py-2 text-center w-16">ID</th>
                                <th class="px-6 py-2">ชื่อ - นามสกุล / User</th>
                                <th class="px-6 py-2 text-center">ตำแหน่ง</th>
                                <th class="px-6 py-2">ศูนย์การแข่งขัน / เครือข่าย</th>
                                <th class="px-6 py-2 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($data as $l)
                            <tr class="group bg-white hover:bg-gray-50/50 transition-all border border-gray-100 shadow-sm rounded-2xl">
                                <td class="px-6 py-6 text-center font-bold text-gray-300">{{$l->id}}</td>
                                <td class="px-6 py-6">
                                    <div class="font-black text-gray-800 text-base leading-tight">{{$l->name.' '.$l->lastname}}</div>
                                    <div class="text-xs text-primary font-bold italic mt-1"><i class="ri-mail-line"></i> {{$l->email}}</div>
                                    <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase"><i class="ri-phone-line"></i> {{$l->phonenumber ?: '-'}}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black border border-emerald-100 uppercase italic">
                                        {{position($l->position) ?: 'ไม่พบข้อมูล'}}
                                    </span>
                                </td>
                                <td class="px-6 py-6 font-bold text-gray-500 italic uppercase leading-relaxed">
                                    <div class="flex items-center gap-1"><i class="ri-map-pin-2-line text-emerald-400"></i> {{$l->getbranch->branch_name ?? 'โรงเรียน'}}</div>
                                    <div class="flex items-center gap-1 text-[10px] text-gray-400"><i class="ri-links-line"></i> {{$l->getschoolgroup->schoolgroup_name ?? 'ไม่พบข้อมูล'}}</div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    @if(officerchk())
                                    <div class="flex justify-center gap-2">
                                        <a href="{{'myuser/'.$l->id.'/edit?action=edit'}}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="ri-pencil-line text-lg"></i>
                                        </a>
                                        <form id="delform{{$l->id}}" method="post" action="{{ route('myuser.destroy',$l->id)}}" class="hidden">
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            {!!csrf_field()!!}
                                        </form>
                                        <button onclick="DelConfirm({{$l->id}})" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-emerald-600 px-8 py-6 text-white">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="ri-user-add-line"></i> ลงทะเบียนผู้ใช้งานใหม่ (Register)
                </h2>
            </div>
            <div class="p-8 md:p-12">
                <form method="POST" action="{{ Route('myuser.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{ csrf_field() }}

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">ชื่อ</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">นามสกุล</label>
                        <input type="text" name="lastname" value="{{ old('lastname') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">เบอร์โทร</label>
                        <input type="text" name="phonenumber" value="{{ old('phonenumber') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">สิทธิ์/ตำแหน่ง</label>
                        <select name="position" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700">
                            <option value="10">Admin (Super)</option>
                            <option value="0">โรงเรียน (School)</option>
                            <option value="5">เครือข่ายสถานศึกษา</option>
                            <option value="1">ศูนย์การแข่งขัน</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">ศูนย์การแข่งขัน</label>
                        <select name="branch" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700">
                            <option value="0">ไม่ระบุ / เลือกข้อมูล</option>
                            @foreach($branch as $i)
                                <option value="{{$i->id}}">{{$i->branch_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">เครือข่าย</label>
                        <select name="schoolgroup" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700">
                            <option value="0">ไม่ระบุ / เลือกข้อมูล</option>
                            @foreach($schoolgroup as $i)
                                <option value="{{$i->id}}">{{$i->schoolgroup_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Username / Email</label>
                        <input type="text" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-400 uppercase tracking-widest italic">Password</label>
                        <input type="text" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-gray-700 shadow-inner">
                    </div>

                    <div class="md:col-span-2 lg:col-span-1 flex items-end">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-100 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-user-add-line text-xl"></i> ลงทะเบียนผู้ใช้
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_users').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "🔍 ค้นหาชื่อ, อีเมล, ตำแหน่ง หรือศูนย์...",
                },
                "initComplete": function(settings, json) {
                    $('#loading-overlay').addClass('opacity-0');
                    setTimeout(function() {
                        $('#loading-overlay').remove();
                    }, 500);
                }
            });
        }
    });

    function DelConfirm(id) {
        if (confirm('คุณต้องการลบผู้ใช้งานรายนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนคืนได้')) {
            document.getElementById('delform' + id).submit();
        }
    }
</script>
@endsection