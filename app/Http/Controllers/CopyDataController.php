<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activities;
use App\Subjectgroup;
use App\Branch;
use App\Classes;
use App\Addschool;
use App\dayofsillapas;
use Session;
use DB;
use Illuminate\Support\Facades\Log;
class CopyDataController extends Controller
{
    public function index()
    {
        // ดึงรายชื่อฐานข้อมูลทั้งหมด
        $databases = DB::select('SHOW DATABASES');

        // แปลงผลลัพธ์จาก Object เป็น Array
        $databaseList = [];
        foreach ($databases as $db) {
            $dbName = reset($db); // ดึงค่าจาก Object
            if (strpos($dbName, 'sillapa') === 0) { // กรองเฉพาะฐานข้อมูลที่ขึ้นต้นด้วย "sillapa"
                $databaseList[] = $dbName;
            }
        }

        return view('copydata.copydata', compact('databaseList'));
    }

    public function copyData(Request $request)
    {
        $sourceDb = $request->input('source_db');
        $targetDb = 'mysql2';

        if (!array_key_exists($sourceDb, config('database.connections'))) {
            return back()->with('error', "Database '{$sourceDb}' not found.");
        }

        $sourceData = DB::connection($sourceDb)->table('addschool')->get();

        Log::notice('System administrator started school data copy', [
            'user_id' => $request->user()->id,
            'source_connection' => $sourceDb,
            'target_connection' => $targetDb,
            'ip' => $request->ip(),
        ]);

        foreach ($sourceData as $data) {
            $exists = DB::connection($targetDb)
                ->table('addschool')
                ->where('school_code', $data->school_code)
                ->exists();

            if (!$exists) {
                DB::connection($targetDb)->table('addschool')->insert([
                    'school_code' => $data->school_code,
                    'school_name' => $data->school_name,
                    'province' => $data->province,
                ]);
            }
        }

        return back()->with('success', "ข้อมูลถูกคัดลอกจาก '{$sourceDb}' ไปยัง 'sillapa2' เรียบร้อยแล้ว!");
    }
}
