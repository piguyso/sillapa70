<?php

namespace App\Services;

/**
 * กติกาการเปลี่ยนตัวผู้เข้าแข่งขันประเภททีม ตามประกาศ สพป.ชุมพร เขต 1 ครั้งที่ 74 ข้อ 5.1
 * ใช้แสดงผลอย่างเดียว ไม่ได้บังคับในระบบ
 */
class TeamSubstitutionRule
{
    /** ครั้งแรกที่ใช้กติกานี้ */
    public const FROM_EVENT = 74;

    public static function appliesToActiveEvent(): bool
    {
        return active_event_number() >= self::FROM_EVENT;
    }

    /** จำนวนนักเรียนที่เปลี่ยนตัวได้สูงสุด (0 = เปลี่ยนไม่ได้) จากจำนวนสมาชิกสูงสุดของทีม */
    public static function maxSubstitutes(int $teamSize): int
    {
        return match (true) {
            $teamSize <= 1 => 0,
            $teamSize <= 3 => 1,
            $teamSize <= 6 => 2,
            $teamSize <= 10 => 3,
            $teamSize <= 20 => 4,
            default => 5,
        };
    }

    public static function describe(int $teamSize): string
    {
        if ($teamSize <= 1) {
            return 'ประเภทเดี่ยว ไม่อนุญาตให้เปลี่ยนตัวผู้เข้าแข่งขันในทุกกรณี';
        }
        $max = self::maxSubstitutes($teamSize);

        return $teamSize <= 3
            ? "ทีม {$teamSize} คน เปลี่ยนตัวนักเรียนได้ {$max} คน"
            : "ทีมสูงสุด {$teamSize} คน เปลี่ยนตัวนักเรียนได้ไม่เกิน {$max} คน";
    }
}
