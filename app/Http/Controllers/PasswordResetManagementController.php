<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PasswordResetManagementController extends Controller
{
    public function reset(Request $request)
    {
        $data = $request->validate([
            'target_type' => ['required', Rule::in(['school_all', 'school', 'network', 'center'])],
            'target_id' => ['nullable', 'integer'],
        ]);

        if ($data['target_type'] !== 'school_all' && empty($data['target_id'])) {
            return back()->with('error', 'กรุณาเลือกหน่วยงานที่ต้องการรีเซ็ตรหัสผ่าน');
        }

        $query = User::query();
        match ($data['target_type']) {
            'school_all' => $query->where('position', 0),
            'school' => $query->where('position', 0)->where('school_id', $data['target_id']),
            'network' => $query->where('position', 5)->where('schoolgroup', $data['target_id']),
            'center' => $query->where('position', 1)->where('branch', $data['target_id']),
        };

        $users = $query->get();
        if ($users->isEmpty()) {
            return back()->with('error', 'ไม่พบบัญชีผู้ใช้สำหรับรายการที่เลือก');
        }

        DB::transaction(function () use ($users, $data, $request) {
            foreach ($users as $user) {
                $user->password = Hash::make($user->email);
                $user->must_change_password = true;
                $user->password_version = ((int) $user->password_version) + 1;
                $user->password_reset_by = $request->user()->id;
                $user->password_reset_at = now();
                $user->remember_token = null;
                $user->save();
            }

            DB::table('user_password_reset_logs')->insert([
                'admin_user_id' => $request->user()->id,
                'target_type' => $data['target_type'],
                'target_id' => $data['target_id'] ?? null,
                'affected_count' => $users->count(),
                'affected_user_ids' => json_encode($users->pluck('id')->values()),
                'event_id' => active_event_id(),
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Log::notice('Administrator reset user passwords', [
            'admin_user_id' => $request->user()->id,
            'target_type' => $data['target_type'],
            'target_id' => $data['target_id'] ?? null,
            'affected_count' => $users->count(),
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'รีเซ็ตรหัสผ่านสำเร็จ '.$users->count().' บัญชี รหัสชั่วคราวเหมือน Username');
    }
}

