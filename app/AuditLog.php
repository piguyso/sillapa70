<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['created_at' => 'datetime'];

    /** ชื่อภาษาไทยของการกระทำ */
    public const ACTIONS = [
        'login' => 'เข้าสู่ระบบ',
        'logout' => 'ออกจากระบบ',
        'login_failed' => 'เข้าสู่ระบบไม่สำเร็จ',
        'created' => 'เพิ่มข้อมูล',
        'updated' => 'แก้ไขข้อมูล',
        'deleted' => 'ลบข้อมูล',
        'request' => 'ทำรายการ/ส่งออก',
    ];

    /** ชื่อภาษาไทยของตารางหลัก */
    public const TABLES = [
        'users' => 'ผู้ใช้', 'schools' => 'โรงเรียน', 'schoolgroups' => 'เครือข่าย', 'branches' => 'ศูนย์การแข่งขัน',
        'activities' => 'รายการแข่งขัน', 'activities_areas' => 'รายการแข่งขัน (เครือข่าย)',
        'addschools' => 'ทีมที่ลงทะเบียน (เขต)', 'addschools_areas' => 'ทีมที่ลงทะเบียน (เครือข่าย)',
        'addstudents' => 'นักเรียน/ครู (เขต)', 'addstudents_areas' => 'นักเรียน/ครู (เครือข่าย)',
        'jows' => 'กรรมการ (เขต)', 'jows_areas' => 'กรรมการ (เครือข่าย)',
        'notices' => 'ประกาศ', 'configs' => 'ตั้งค่าระบบ', 'events' => 'งานแข่งขัน',
        'competition_access_rules' => 'กฎเวลาเปิด/ปิด',
    ];

    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    public function getTableLabelAttribute(): string
    {
        return $this->subject_table ? (self::TABLES[$this->subject_table] ?? $this->subject_table) : '-';
    }
}
