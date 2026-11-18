<?php

namespace App\Services;

/**
 * กติกากลางของเกียรติบัตรนักเรียน/ครู (ระดับเครือข่ายและระดับเขตพื้นที่)
 */
class CertificateText
{
    public const NO_CONTEST_STATUSES = [98, 99];

    /** พิมพ์ได้เฉพาะทีมที่ประกาศผลแล้วและมีคะแนน หรือรายการ 99 (มอบเกียรติบัตร); 98 = งดมอบเกียรติบัตร */
    public static function assertPrintable($team): void
    {
        abort_if($team === null, 404, 'ไม่พบข้อมูลทีม');
        $status = (int) $team->activity_status;
        abort_if($status === 98, 403, 'รายการนี้ประกาศผลแบบงดมอบเกียรติบัตร');
        abort_unless($status === 1 || $status === 99, 403,
            'ยังไม่ประกาศผลการแข่งขัน ไม่สามารถพิมพ์เกียรติบัตรได้');
        abort_if($status === 1 && (float) $team->student_score <= 0, 403,
            'ไม่สามารถพิมพ์เกียรติบัตรสำหรับผู้ไม่เข้าร่วมการแข่งขัน');
    }

    /** @return array{0:string,1:string} [ระดับเหรียญ, ลำดับรางวัล] */
    public static function award($team): array
    {
        if (in_array((int) $team->activity_status, self::NO_CONTEST_STATUSES, true)) {
            return ['เข้าร่วม', ''];
        }

        $score = (float) $team->student_score;
        $medal = match (true) {
            $score >= 80 => 'เหรียญทอง',
            $score >= 70 => 'เหรียญเงิน',
            $score >= 60 => 'เหรียญทองแดง',
            default => 'เข้าร่วม',
        };
        $rank = match ((int) $team->activity_rank) {
            1 => 'ชนะเลิศ',
            2 => 'รองชนะเลิศอันดับ ๑',
            3 => 'รองชนะเลิศอันดับ ๒',
            default => '',
        };

        return [$medal, $rank];
    }

    /**
     * เลขที่เกียรติบัตร: {ระดับ}-{รหัสกิจกรรม}-{รหัสทีม}/{ปี}
     * ระดับเขตพื้นที่ = ๐๑, ระดับเครือข่าย = ๐๔ (๐๒/๐๓ ใช้กับเกียรติบัตรกรรมการแล้ว)
     */
    public static function number(string $levelCode, $team, $year): string
    {
        return 'เลขที่ '.thainumDigit($levelCode.'-'.$team->school_activity.'-'.$team->id.'/'.$year).'      ';
    }
}
