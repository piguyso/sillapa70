<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function mapping(): array
    {
        return [
            316 => '86010042',
            292 => '86010056',
            293 => '86010055',
            294 => '86010041',
            264 => '86010048',
            295 => '86010053',
            246 => '86010008',
            269 => '86010047',
            348 => '86010039',
            273 => '86010037',
            286 => '86010066',
            287 => '86010032',
            288 => '86010024',
            275 => '86010029',
            289 => '86010030',
            290 => '86010023',
            260 => '86010011',
            251 => '86010064',
            259 => '86010013',
            258 => '86010014',
            255 => '86010045',
            256 => '86010063',
            257 => '86010046',
            253 => '86010009',
            252 => '86010010',
            254 => '86010043',
            338 => '86010006',
            339 => '86010060',
            298 => '86010015',
            337 => '86010016',
            268 => '86010061',
            341 => '86010017',
            272 => '86010062',
            340 => '86010001',
            343 => '86010019',
            342 => '86010020',
            336 => '86010059',
            303 => '86010071',
            308 => '86010106',
            265 => '86010095',
            300 => '86010100',
            313 => '86010105',
            270 => '86010103',
            312 => '86010107',
            311 => '86010098',
            314 => '86010109',
            267 => '86010102',
            309 => '86010097',
            299 => '86010096',
            310 => '86010094',
            306 => '86010072',
            301 => '86010108',
            305 => '86010101',
            277 => '86010080',
            281 => '86010089',
            280 => '86010068',
            278 => '86010069',
            271 => '86010083',
            328 => '86010112',
            262 => '86010090',
            266 => '86010111',
            329 => '86010093',
            332 => '86010091',
            274 => '86010076',
            334 => '86010077',
            330 => '86010078',
            333 => '86010073',
            335 => '86010092',
            331 => '86010110',
            263 => '86010128',
            317 => '86010137',
            318 => '86010113',
            319 => '86010127',
            320 => '86010136',
            321 => '86010139',
            322 => '86010130',
            323 => '86010118',
            324 => '86010129',
            325 => '86010140',
            327 => '86010115',
        ];
    }

    public function up(): void
    {
        if (! Schema::hasTable('school_username_changes')) {
            Schema::create('school_username_changes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('old_username');
                $table->string('new_username')->unique();
                $table->timestamp('changed_at');
            });
        }

        DB::transaction(function () {
            $mapping = $this->mapping();
            $users = DB::table('users')->whereIn('id', array_keys($mapping))->lockForUpdate()->get()->keyBy('id');

            foreach ($mapping as $userId => $newUsername) {
                if (! isset($users[$userId])) {
                    throw new RuntimeException("School user {$userId} not found.");
                }

                $collision = DB::table('users')
                    ->where('email', $newUsername)
                    ->whereNotIn('id', array_keys($mapping))
                    ->exists();

                if ($collision) {
                    throw new RuntimeException("Username {$newUsername} belongs to a non-migrated user.");
                }

                DB::table('school_username_changes')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'old_username' => $users[$userId]->email,
                        'new_username' => $newUsername,
                        'changed_at' => now(),
                    ]
                );
            }

            foreach (array_keys($mapping) as $userId) {
                DB::table('users')->where('id', $userId)->update([
                    'email' => '__smis_migration_'.$userId,
                ]);
            }

            foreach ($mapping as $userId => $newUsername) {
                DB::table('users')->where('id', $userId)->update([
                    'email' => $newUsername,
                ]);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('school_username_changes')) {
            return;
        }

        DB::transaction(function () {
            $changes = DB::table('school_username_changes')->lockForUpdate()->get();

            foreach ($changes as $change) {
                DB::table('users')->where('id', $change->user_id)->update([
                    'email' => '__smis_rollback_'.$change->user_id,
                ]);
            }

            foreach ($changes as $change) {
                DB::table('users')->where('id', $change->user_id)->update([
                    'email' => $change->old_username,
                ]);
            }
        });

        Schema::dropIfExists('school_username_changes');
    }
};

