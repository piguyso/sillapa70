<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RequiredPasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.change-required-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8), 'different:username'],
            'username' => ['required', 'string'],
        ]);

        $user = $request->user();
        if (! hash_equals((string) $user->email, (string) $request->username)) {
            return back()->withErrors(['password' => 'ข้อมูล Username ไม่ถูกต้อง']);
        }
        if (hash_equals((string) $user->email, (string) $request->password)) {
            return back()->withErrors(['password' => 'รหัสผ่านใหม่ต้องไม่เหมือน Username']);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->password_changed_at = now();
        $user->password_version = ((int) $user->password_version) + 1;
        $user->remember_token = null;
        $user->save();
        $request->session()->put('auth_password_version', (int) $user->password_version);

        Log::notice('User completed required password change', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return redirect()->to(event_url(''))->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}

