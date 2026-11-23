<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolgroup extends Model
{
    public const TYPES = ['district' => 'อำเภอ', 'cluster' => 'กลุ่มโรงเรียน'];

    /** ชุดสีประจำเครือข่าย (ทุกสีตัวอักษรขาวอ่านชัด: contrast >= 4.5) */
    public const PALETTE = [
        '#0f766e', '#1d4ed8', '#7e22ce', '#b45309', '#15803d',
        '#c2410c', '#0e7490', '#4338ca', '#a21caf', '#475569',
    ];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_schoolgroup', 'schoolgroup_id', 'school_id');
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->group_type] ?? self::TYPES['cluster'];
    }

    /** สีของเครือข่าย หรือ null = ใช้สี theme ของระบบ */
    public static function colorFor($id): ?string
    {
        if (! $id) {
            return null;
        }
        $color = static::whereKey((int) $id)->value('theme_color');

        return preg_match('/^#[0-9a-fA-F]{6}$/', (string) $color) ? $color : null;
    }

    /** สีถัดไปจากชุดสีที่ยังไม่ถูกใช้ (ใช้เป็นค่าเริ่มต้นของเครือข่ายใหม่) */
    public static function nextPaletteColor(): string
    {
        $used = static::pluck('theme_color')->map(fn ($c) => strtolower((string) $c))->all();
        foreach (self::PALETTE as $c) {
            if (! in_array($c, $used, true)) {
                return $c;
            }
        }

        return self::PALETTE[static::count() % count(self::PALETTE)];
    }

    /** contrast ratio ของสีนี้กับตัวอักษรสีขาว (WCAG) */
    public static function contrastWithWhite(string $hex): float
    {
        $lin = function (int $v) {
            $c = $v / 255;

            return $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;
        };
        [$r, $g, $b] = sscanf(ltrim($hex, '#'), '%02x%02x%02x');
        $l = 0.2126 * $lin($r) + 0.7152 * $lin($g) + 0.0722 * $lin($b);

        return 1.05 / ($l + 0.05);
    }
}
