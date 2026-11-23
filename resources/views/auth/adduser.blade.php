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

        <div class="bg-white rounded-3xl shadow-sm border border-red-100 overflow-hidden">
            <div class="bg-red-600 px-8 py-6 text-white">
                <h2 class="text-xl font-black flex items-center gap-2"><i class="ri-lock-password-line"></i> Reset รหัสผ่านผู้ใช้งาน</h2>
                <p class="text-red-100 text-sm mt-1">รหัสผ่านชั่วคราวจะเหมือน Username และผู้ใช้ต้องเปลี่ยนรหัสเมื่อเข้าสู่ระบบ</p>
            </div>
            <form id="password-reset-form" method="POST" action="{{ event_url('myuser/password-reset') }}" class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <x-form.select name="target_type" id="reset-target-type" label="ประเภทบัญชี" required>
                    <option value="">เลือกประเภทบัญชี</option>
                    <option value="school_all">โรงเรียนทั้งหมด</option>
                    <option value="school">โรงเรียนเฉพาะแห่ง</option>
                    <option value="network">เครือข่าย</option>
                    <option value="center">ศูนย์การแข่งขัน</option>
                </x-form.select>
                <x-form.select name="target_id" id="reset-target-id" label="หน่วยงาน" disabled>
                    <option value="">เลือกประเภทบัญชีก่อน</option>
                </x-form.select>
                <x-form.button type="submit" variant="danger" class="w-full" onclick="return confirmPasswordReset(this)">
                    <i class="ri-refresh-line"></i> Reset รหัสผ่าน
                </x-form.button>
            </form>
        </div>
        
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
                                  <div class="flex items-center gap-3">
                                    <x-school-logo :school="$l->getschool" :icon="['0' => 'ri-school-line', '1' => 'ri-map-pin-2-line', '5' => 'ri-share-circle-line', '10' => 'ri-shield-user-line'][(string) $l->position] ?? 'ri-user-line'" />
                                    <div>
                                    <div class="font-black text-gray-800 text-base leading-tight">{{$l->name.' '.$l->lastname}}</div>
                                    <div class="text-xs text-primary font-bold italic mt-1"><i class="ri-mail-line"></i> {{$l->email}}</div>
                                    <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase"><i class="ri-phone-line"></i> {{$l->phonenumber ?: '-'}}</div>
                                    </div>
                                  </div>
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
                                        <a href="{{ url('myuser/'.$l->id.'/edit?action=edit') }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
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

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" x-data="userRegister(@js($registerData))">
            <div class="px-8 py-6 text-white" style="background: var(--ui-primary)">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="ri-user-add-line"></i> ลงทะเบียนผู้ใช้งานใหม่ (Register)
                </h2>
                <p class="text-sm opacity-90 mt-1">เลือกประเภทบัญชีและหน่วยงาน ระบบกำหนดสิทธิ์และข้อมูลที่เกี่ยวข้องให้อัตโนมัติ</p>
            </div>
            <div class="p-8 md:p-12">
                @if($errors->any())
                    <div class="mb-6 rounded-2xl bg-red-50 border border-red-100 text-red-700 p-4 text-sm font-bold">
                        @foreach($errors->all() as $error)<div>* {{ $error }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ Route('myuser.store') }}" class="space-y-8">
                    {{ csrf_field() }}

                    {{-- 1) ประเภทบัญชี --}}
                    <div>
                        <label class="ui-label">1. ประเภทบัญชี (สิทธิ์ที่จะได้รับ)</label>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-2">
                            <template x-for="t in types" :key="t.key">
                                <label class="cursor-pointer rounded-2xl border-2 p-4 flex items-center gap-3 transition"
                                       :class="type === t.key ? 'ring-4' : 'border-gray-200 hover:border-gray-300'"
                                       :style="type === t.key ? 'border-color: var(--ui-primary); background: color-mix(in srgb, var(--ui-primary) 8%, white); --tw-ring-color: color-mix(in srgb, var(--ui-primary) 18%, transparent)' : ''">
                                    <input type="radio" name="account_type" :value="t.key" x-model="type" @change="reset()" class="sr-only">
                                    <i :class="t.icon" class="text-2xl" :style="type === t.key ? 'color: var(--ui-primary)' : 'color:#94a3b8'"></i>
                                    <span><span class="block font-black text-gray-800 text-sm" x-text="t.label"></span><span class="block text-[11px] text-gray-400 font-bold" x-text="t.hint"></span></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- 2) หน่วยงาน --}}
                    <div x-show="type !== 'admin'" x-cloak>
                        <label class="ui-label" x-text="'2. เลือก' + current().label"></label>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="w-12 h-12 rounded-full bg-gray-100 text-gray-300 flex items-center justify-center shrink-0 overflow-hidden border border-gray-100">
                                <template x-if="selected && selected.logo"><img :src="selected.logo" alt="" class="w-12 h-12 object-contain bg-white"></template>
                                <template x-if="!(selected && selected.logo)"><i :class="current().icon" class="text-xl"></i></template>
                            </span>
                            <select name="target_id" x-model="targetId" @change="pick()" class="ui-select flex-1" :required="type !== 'admin'">
                                <option value="">— เลือก —</option>
                                <template x-for="o in options()" :key="o.id">
                                    <option :value="o.id" :disabled="o.hasUser" x-text="o.label + (o.hasUser ? '  (มีบัญชีแล้ว)' : '')"></option>
                                </template>
                            </select>
                        </div>
                        <p class="ui-help" x-show="type !== 'admin'">รายการที่ขึ้นว่า "มีบัญชีแล้ว" เลือกไม่ได้ (1 หน่วยงานมี 1 บัญชี) ถ้าต้องการแก้ไข ให้ใช้ปุ่มแก้ไขในตารางด้านบน</p>
                    </div>

                    {{-- 3) ข้อมูลบัญชี --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <x-form.input label="ชื่อ" name="name" x-model="name" required />
                        <x-form.input label="นามสกุล / ชื่อหน่วยงาน" name="lastname" x-model="lastname" required />
                        <x-form.input label="เบอร์โทร (ไม่บังคับ)" name="phonenumber" x-model="phone" inputmode="tel" />
                        <x-form.input label="Username" name="email" x-model="email" required autocomplete="off" help="โรงเรียนใช้รหัสโรงเรียน 8 หลักอัตโนมัติ แก้ไขได้" />
                        <x-form.input label="รหัสผ่าน" name="password" x-model="password" autocomplete="new-password" placeholder="เว้นว่าง = เท่ากับ Username" help="ปล่อยว่างเพื่อใช้ Username เป็นรหัสผ่านเริ่มต้น" />
                        <div class="ui-form-group flex items-end">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 pb-3">
                                <input type="hidden" name="must_change_password" value="0">
                                <input type="checkbox" name="must_change_password" value="1" checked class="rounded border-gray-300"> บังคับเปลี่ยนรหัสผ่านเมื่อเข้าใช้ครั้งแรก
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <x-form.button type="submit"><i class="ri-user-add-line"></i> ลงทะเบียนผู้ใช้</x-form.button>
                        <span class="text-xs text-gray-400 font-bold" x-show="summary()" x-text="summary()"></span>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function userRegister(d) {
                return {
                    types: [
                        { key: 'school', label: 'โรงเรียน', hint: 'ได้สิทธิ์โรงเรียน', icon: 'ri-school-line' },
                        { key: 'network', label: 'เครือข่าย', hint: 'ได้สิทธิ์เครือข่าย', icon: 'ri-share-circle-line' },
                        { key: 'center', label: 'ศูนย์การแข่งขัน', hint: 'ได้สิทธิ์ศูนย์', icon: 'ri-map-pin-2-line' },
                        { key: 'admin', label: 'ผู้ดูแลระบบ', hint: 'ได้สิทธิ์สูงสุด', icon: 'ri-shield-user-line' },
                    ],
                    type: @js(old('account_type', 'school')),
                    targetId: @js(old('target_id', '')),
                    name: @js(old('name', '')), lastname: @js(old('lastname', '')), phone: @js(old('phonenumber', '')),
                    email: @js(old('email', '')), password: '', selected: null,
                    current() { return this.types.find(t => t.key === this.type); },
                    options() { return d[this.type] || []; },
                    reset() { this.targetId = ''; this.selected = null; this.name = ''; this.lastname = ''; this.email = ''; if (this.type === 'admin') { this.name = 'ผู้ดูแลระบบ'; } },
                    pick() {
                        const o = this.options().find(x => String(x.id) === String(this.targetId));
                        this.selected = o || null;
                        if (!o) { return; }
                        this.name = o.name; this.lastname = o.lastname; this.email = o.username;
                    },
                    summary() {
                        const o = this.selected;
                        if (this.type === 'admin') { return 'จะสร้างบัญชีผู้ดูแลระบบ (สิทธิ์สูงสุด)'; }
                        return o ? 'สิทธิ์ที่จะได้รับ: ' + this.current().hint.replace('ได้สิทธิ์', '') + ' — ' + o.label : '';
                    },
                    init() { if (this.targetId) { this.pick(); } else if (this.type === 'admin') { this.name = 'ผู้ดูแลระบบ'; } },
                };
            }
        </script>
    </div>
</div>

<script>
    const resetTargets = {
        school: @json($resetSchools->map(fn($item) => ['id' => $item->id, 'name' => $item->school_name])->values()),
        network: @json($resetNetworks->map(fn($item) => ['id' => $item->id, 'name' => $item->schoolgroup_name])->values()),
        center: @json($resetCenters->map(fn($item) => ['id' => $item->id, 'name' => $item->branch_name])->values())
    };
    const resetType = document.getElementById('reset-target-type');
    const resetTarget = document.getElementById('reset-target-id');

    resetType.addEventListener('change', function () {
        const type = this.value;
        resetTarget.innerHTML = '';
        if (type === 'school_all') {
            resetTarget.disabled = true;
            resetTarget.append(new Option('ทุกโรงเรียน ({{ $resetSchools->count() }} บัญชี)', ''));
            return;
        }
        const items = resetTargets[type] || [];
        resetTarget.disabled = false;
        resetTarget.append(new Option('เลือกหน่วยงาน', ''));
        items.forEach(item => resetTarget.append(new Option(item.name, item.id)));
    });

    function confirmPasswordReset(button) {
        if (!resetType.value) {
            appAlert('กรุณาเลือกประเภทบัญชี');
            return false;
        }
        if (resetType.value !== 'school_all' && !resetTarget.value) {
            appAlert('กรุณาเลือกหน่วยงาน');
            return false;
        }
        const label = resetType.value === 'school_all'
            ? 'โรงเรียนทั้งหมด {{ $resetSchools->count() }} บัญชี'
            : resetTarget.options[resetTarget.selectedIndex].text;
        return appConfirmSubmit(button,
            'ต้องการ Reset รหัสผ่านของ ' + label + ' ใช่หรือไม่? รหัสผ่านเดิมจะใช้ไม่ได้ทันที',
            { title: 'ยืนยันการ Reset รหัสผ่าน', confirmText: 'Reset รหัสผ่าน' }
        );
    }

    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#table_users').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "dom": '<"flex justify-end mb-4"f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"ip>',
                "language": {
                   "search": "",
                   "searchPlaceholder": "ค้นหาชื่อ, อีเมล, ตำแหน่ง หรือศูนย์...",
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
        return appConfirmForm('delform' + id, 'คุณต้องการลบผู้ใช้งานรายนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนคืนได้');
    }
</script>
@endsection
