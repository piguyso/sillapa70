@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12 font-sarabun">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8 text-white" style="background:linear-gradient(135deg,var(--ui-primary),var(--ui-secondary))">
            <h1 class="text-2xl font-black">ตั้งรหัสผ่านใหม่</h1>
            <p class="mt-2 text-white/80">เพื่อความปลอดภัย กรุณาเปลี่ยนรหัสผ่านชั่วคราวก่อนใช้งานระบบ</p>
        </div>
        <form method="POST" action="{{ event_url('password/change-required') }}" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="username" value="{{ Auth::user()->email }}">
            <div class="rounded-2xl bg-slate-50 p-4">
                <span class="text-xs font-bold text-slate-400">USERNAME</span>
                <div class="font-black text-slate-800">{{ Auth::user()->email }}</div>
            </div>
            <x-form.input name="password" type="password" label="รหัสผ่านใหม่" help="อย่างน้อย 8 ตัว และต้องไม่เหมือน Username" required autocomplete="new-password" />
            <x-form.input name="password_confirmation" type="password" label="ยืนยันรหัสผ่านใหม่" required autocomplete="new-password" />
            <x-form.button type="submit" class="w-full">บันทึกรหัสผ่านใหม่</x-form.button>
        </form>
    </div>
</div>
@endsection
