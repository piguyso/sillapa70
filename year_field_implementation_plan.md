# 🛠️ แผนการนำฟิลด์ `year` มาใช้แยกข้อมูลรายปีการศึกษา (Implementation Plan)
**โครงการ:** ระบบงานศิลปหัตถกรรมนักเรียน (Sillapa)  
**เป้าหมาย:** ปรับปรุงโครงสร้างฐานข้อมูลและโค้ดให้สามารถรองรับข้อมูลหลายปีการศึกษาในฐานข้อมูลเดียวกัน

---

## 📌 1. การกำหนดตารางที่ต้องการเพิ่มฟิลด์ `year`

ข้อมูลที่เปลี่ยนแปลงในแต่ละปี (Transactional Data) จะต้องถูกแยกด้วยฟิลด์ `year` โดยมีตารางเป้าหมายหลักดังนี้:

| ลำดับ | ชื่อตารางในฐานข้อมูล | คำอธิบาย | Model ใน Laravel |
| :--- | :--- | :--- | :--- |
| 1 | `addschools` | ทีม/กิจกรรมที่แต่ละโรงเรียนลงทะเบียน | `App\Addschool` |
| 2 | `addstudents` | รายชื่อนักเรียนและครูในแต่ละทีม | `App\Addstudent` |
| 3 | `jows` | รายชื่อกรรมการตัดสินการแข่งขัน | `App\Jows` |
| 4 | `schoolranks` | คะแนนและอันดับของโรงเรียน | `App\Schoolranks` |
| 5 | `addschools_areas` | ทีมที่ผ่านการลงทะเบียนระดับเขต | `App\addschools_areas` |
| 6 | `addstudents_areas` | นักเรียน/ครูระดับเขต | `App\addstudents_areas` |
| 7 | `jows_areas` | กรรมการระดับเขต | `App\Jows_areas` |

---

## 💾 2. ขั้นตอนการทำ Migration & Backfill ข้อมูลเก่า

เราต้องสร้าง Migration เพื่อทำการเพิ่มคอลัมน์ `year` และใส่ปีการศึกษาเริ่มต้น (เช่น `2570`) ให้กับข้อมูลปัจจุบันที่มีอยู่ในตาราง

### 📝 โค้ดสร้าง Migration:
สร้างไฟล์ migration ใหม่โดยรันคำสั่ง:
`php artisan make:migration add_year_field_to_transaction_tables`

แก้ไขโค้ดในไฟล์ Migration:
```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $tables = [
        'addschools',
        'addstudents',
        'jows',
        'schoolranks',
        'addschools_areas',
        'addstudents_areas',
        'jows_areas'
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // 1. เพิ่มคอลัมน์ year
                    $table->string('year', 4)->default('2570')->after('id');
                    // 2. ทำ Index เพื่อให้ Query ข้อมูลได้เร็วขึ้น
                    $table->index('year');
                });

                // 3. Backfill ข้อมูลเก่าในตารางนั้นให้เป็นปี 2570
                DB::table($tableName)->update(['year' => '2570']);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'year')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropIndex([$tableName . '_year_index']);
                    $table->dropColumn('year');
                });
            }
        }
    }
};
```

---

## 🔒 3. การเก็บปีการศึกษาใน Session และการเลือกปีการศึกษา

เมื่อผู้ใช้งานเข้าสู่ระบบ หรือที่หน้าแรกของเว็บไซต์ ระบบจะต้องเก็บค่า "ปีการศึกษาปัจจุบันที่ต้องการรับชม/ทำงาน" ไว้ใน Session

### 📝 ปรับปรุงหน้าแรกและระบบ Login (Session Setup)
แก้ไขใน `app/Http/Controllers/Auth/LoginController.php` (ในเมธอด `authenticated`):

```php
public function authenticated($request, $user)
{
    // เก็บปีการศึกษาที่ระบุตอน Login หรือใช้ปีปัจจุบันเป็นค่าเริ่มต้น
    $activeYear = $request->input('year', env('APP_YEAR', '2570'));
    session(['year' => $activeYear]);

    // ข้อมูลอื่นๆ ของ User
    session(['position' => $user->position]);
    session(['name' => $user->name]);
    session(['lastname' => $user->lastname]);
    session(['userid' => $user->id]);

    return redirect()->intended($this->redirectPath());
}
```

---

## 🚀 4. การประยุกต์ใช้ Laravel Global Scope (จุดสำคัญที่สุด)

เพื่อไม่ให้แอดมินหรือโปรแกรมเมอร์ต้องไปไล่เขียน `->where('year', session('year'))` ใน Query เก่าๆ นับร้อยจุด เราจะสร้าง **Global Scope** เพื่อให้ Laravel กรองข้อมูลปีการศึกษาให้อัตโนมัติในเบื้องหลัง

### 📝 ขั้นตอนที่ 4.1: สร้างคลาส Scope กลาง (`app/Scopes/YearScope.php`)
```php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class YearScope implements Scope
{
    public function apply(Builder $builder, Model model)
    {
        // กรองข้อมูลตามปีการศึกษาใน Session หากไม่มี ให้ใช้ค่าคงที่จาก env
        $activeYear = session('year', env('APP_YEAR', '2570'));
        $builder->where($model->getTable() . '.year', $activeYear);
    }
}
```

### 📝 ขั้นตอนที่ 4.2: นำ Scope ไปผูกกับ Model ต่างๆ
เปิดไฟล์ Model เช่น `app/Addschool.php`, `app/Addstudent.php`, `app/Jows.php` แล้วเพิ่มฟังก์ชัน `booted()` เข้าไป:

```php
use App\Scopes\YearScope;

protected static function booted()
{
    static::addGlobalScope(new YearScope);
}
```

#### ⚠️ ข้อยกเว้น (กรณีต้องการดึงข้อมูลทุกปีการศึกษา):
ในหน้าจอผู้ดูแลระบบที่ต้องการดูข้อมูลประวัติศาสตร์ข้ามปี สามารถยกเลิกการฟิลเตอร์อัตโนมัติได้ง่ายๆ โดยใช้:
```php
$allYearsData = Addschool::withoutGlobalScope(YearScope::class)->get();
```

---

## 🔄 5. การสลับปีการศึกษาหน้าเว็บ (Year Switcher)

เพิ่มคอมโพเนนต์ Dropdown สำหรับสลับปีการศึกษาที่แถบเมนูด้านบน (Navbar)

### 📝 โค้ดของ Controller สำหรับรับการเลือกปีการศึกษา:
สร้าง Route และ Controller เมธอดรับสลับปีการศึกษา:
```php
public function switchYear(Request $request)
{
    $request->validate([
        'year' => 'required|string|size:4'
    ]);
    
    // บันทึกปีใหม่ลงใน Session
    session(['year' => $request->year]);
    
    return redirect()->back()->with('success', 'เปลี่ยนปีการศึกษาเรียบร้อยแล้ว');
}
```

---

## 📋 เช็คลิสต์ขั้นตอนการทำงาน (Checklist)

1. [ ] สร้างไฟล์ Migration ตามตัวอย่างในข้อ 2
2. [ ] รันคำสั่ง `php artisan migrate` ใน Terminal เพื่ออัปเกรดฐานข้อมูลจริง
3. [ ] สร้างคลาส `YearScope.php` ไว้ในโฟลเดอร์ `app/Scopes`
4. [ ] เปิดใช้ `booted()` เรียกใช้ Scope ใน Model ทั้ง 7 ตารางที่เกี่ยวข้อง
5. [ ] ตรวจสอบและแก้ไข `LoginController` และ `HomeController` ให้มีการเซ็ตค่าเริ่มต้นของ `session(['year'])`
6. [ ] ทำเมนูสลับปีการศึกษาบน Navbar พร้อมลิงก์ส่งไปสลับ Session
7. [ ] ทดสอบระบบโดยลองสลับปีการศึกษา และบันทึกข้อมูลทีมในแต่ละปีการศึกษาเพื่อดูความถูกต้อง
