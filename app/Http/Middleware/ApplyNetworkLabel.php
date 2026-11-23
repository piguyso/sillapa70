<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ชื่อเรียก "ระดับเครือข่าย" ที่ตั้งในหน้า /config (เช่น "เครือข่ายสถานศึกษา" หรือ "กลุ่มโรงเรียน")
 * ใช้แทนข้อความที่เขียนตายตัว (hardcode) ในเว็บ เมนู PDF เกียรติบัตร และ Excel เท่านั้น
 * ข้อมูลจากฐานข้อมูล (ชื่อเครือข่าย ชื่อโรงเรียน ชื่อผู้ใช้ ประกาศ ฯลฯ) จะแสดงตามที่บันทึกไว้ ไม่ถูกแทน
 * ค่าเริ่มต้นคือ "เครือข่ายสถานศึกษา"
 */
class ApplyNetworkLabel
{
    public const DEFAULT_FULL = 'เครือข่ายสถานศึกษา';

    private const DEFAULT_SHORT = 'เครือข่าย';

    private const TOKEN = "\u{E000}NETFULL\u{E000}";

    /** คอลัมน์ข้อมูลที่ห้ามถูกแทนคำ (ตาราง => คอลัมน์) */
    private const DATA_COLUMNS = [
        'schoolgroups' => ['schoolgroup_name', 'schoolgroup_detail'],
        'schools' => ['school_name', 'school_detail'],
        'users' => ['name', 'lastname'],
        'activities' => ['activity_name'],
        'notices' => ['notice_text', 'notice_detail', 'notice_owner'],
        'configs' => ['app_name', 'app_group', 'app_area'],
    ];

    /** @var array<int,string>|null */
    private static ?array $protected = null;

    /** ค่าข้อมูลในฐานข้อมูลที่มีคำว่า "เครือข่าย" (ต้องคงไว้ตามเดิม) */
    private static function protectedValues(): array
    {
        if (self::$protected !== null) {
            return self::$protected;
        }

        return self::$protected = Cache::remember('network_label_protected_values', 60, function () {
            $values = [];
            foreach (self::DATA_COLUMNS as $table => $columns) {
                foreach ($columns as $column) {
                    try {
                        foreach (DB::table($table)->where($column, 'like', '%'.self::DEFAULT_SHORT.'%')->distinct()->pluck($column) as $v) {
                            $v = trim((string) $v);
                            // ค่าที่เป็นแค่คำว่า "เครือข่าย"/"เครือข่ายสถานศึกษา" เฉย ๆ (เช่น ชื่อบัญชีผู้ใช้ระดับเครือข่าย) ไม่ใช่ชื่อเฉพาะ จึงไม่กัน
                            if (mb_strlen($v) >= 6 && ! in_array($v, [self::DEFAULT_FULL, self::DEFAULT_SHORT], true)) {
                                $values[$v] = true;
                                $values[e($v)] = true; // รูปที่ Blade escape แล้ว
                            }
                        }
                    } catch (\Throwable $e) {
                        // ตาราง/คอลัมน์ไม่มี: ข้าม
                    }
                }
            }
            $list = array_keys($values);
            usort($list, fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));

            return $list;
        });
    }

    /** แทนคำที่ hardcode ด้วยชื่อเรียกที่ตั้งไว้ โดยไม่แตะข้อมูลจากฐานข้อมูล */
    public static function apply(?string $text): ?string
    {
        if ($text === null || $text === '' || ! str_contains($text, self::DEFAULT_SHORT)) {
            return $text;
        }
        $label = trim((string) config('app.network_label')) ?: self::DEFAULT_FULL;

        // 1) กันข้อมูลจากฐานข้อมูลไว้ก่อน
        $masks = [];
        foreach (self::protectedValues() as $i => $value) {
            if (str_contains($text, $value)) {
                $token = "\u{E001}".$i."\u{E001}";
                $masks[$token] = $value;
                $text = str_replace($value, $token, $text);
            }
        }

        // 2) แทนคำที่ hardcode (ใช้ token กันไม่ให้คำที่แทนแล้วถูกแทนซ้ำ)
        $text = str_replace(self::DEFAULT_FULL, self::TOKEN, $text);
        $text = str_replace(self::DEFAULT_SHORT, self::TOKEN, $text);
        $text = str_replace(self::TOKEN, $label, $text);

        // 3) คืนข้อมูลจากฐานข้อมูล
        return $masks === [] ? $text : strtr($text, $masks);
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // หน้าตั้งค่าเอง ต้องเห็นคำเดิมเพื่อใช้อธิบายช่องกรอก
        if (preg_match('~(?:^|/)config(?:/|$)~', $request->path())) {
            return $response;
        }
        if (! $response instanceof Response || $response instanceof StreamedResponse || $response instanceof BinaryFileResponse
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if ($content !== false) {
            $response->setContent(self::apply($content));
        }

        return $response;
    }
}
