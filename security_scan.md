# 🔒 Security Scan Report
**เว็บแอปพลิเคชัน:** sillapa.cpn1.go.th/2570  
**Framework:** Laravel 13 (PHP 8.3)  
**วันที่สแกน:** 2026-07-16  
**ผู้สแกน:** Antigravity AI Security Scan

---

## สรุปภาพรวม (อัปเดตสถานะการแก้ไข)

| ระดับความเสี่ยง | ทั้งหมด | แก้ไขแล้ว | คงเหลือ |
|---|---|---|---|
| 🔴 Critical | 3 | 2 | 1 (ขอยกเว้นข้อ 2 ไว้ก่อน) |
| 🟠 High | 4 | 4 | 0 |
| 🟡 Medium | 5 | 5 | 0 |
| 🟢 Low / Info | 4 | 1 | 3 |

---

## 🔴 Critical

### 1. SQL Injection — Raw Query ฝัง parameter โดยตรง
**ไฟล์:** `app/Http/Controllers/HomeController.php` บรรทัด 738–759  
**สถานะ:** ✅ **แก้ไขแล้ว** (เปลี่ยนไปใช้ Parameter Binding เรียบร้อย)

```php
// ✅ แก้ไขแล้ว: ใช้ Prepared Statement Binding แทนการแทรกตัวแปรตรงๆ
$dbrank = DB::select("SELECT a.school_name, ... WHERE activity_status = 1 AND school = ?", [$id]);
```

---

### 2. ใช้ DB user root + รหัสผ่านเก็บใน .env แบบ plaintext
**ไฟล์:** `.env` บรรทัด 17–18  
**สถานะ:** ⚠️ **คงการใช้งานไว้ก่อนตามคำสั่งผู้ใช้งาน** (จะแก้ไขภายหลัง)

```
DB_USERNAME=root
DB_PASSWORD="*#@%Kaelq9jg"
```

**ความเสี่ยงคงเหลือ:**
- ใช้ root user ในการรันระบบจริง มีสิทธิ์สูงสุดใน DBMS หากเกิดการบุกรุกจะส่งผลต่อฐานข้อมูลทั้งหมดบนเครื่อง

---

### 3. File Upload ไม่ตรวจสอบประเภทไฟล์จริง (Potential RCE)
**ไฟล์:** `app/Http/Controllers/NoticeController.php` บรรทัด 16–33  
**สถานะ:** ✅ **แก้ไขแล้ว** (ใช้ Laravel Request File Validation และ Validate MIME/Image)

```php
// ✅ แก้ไขแล้ว
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:12048'
]);
```

---

## 🟠 High

### 4. Middleware ตรวจสอบสิทธิ์ใช้ String Comparison กับตัวเลข
**ไฟล์:** `app/Http/Middleware/areamiddleware.php` บรรทัด 26  
**สถานะ:** ✅ **แก้ไขแล้ว** (ทำการ Cast position เป็น Integer ก่อนเปรียบเทียบ)

```php
// ✅ แก้ไขแล้ว
elseif ((int) Auth::user()->position >= 10) { ... }
```

---

### 5. ไม่มี Rate Limiting บนหน้า Login (Brute Force)
**ไฟล์:** `routes/web.php` บรรทัด 228  
**สถานะ:** ✅ **แก้ไขแล้ว** (ใส่ `throttle:5,1` middleware เรียบร้อย)

```php
// ✅ แก้ไขแล้ว
Route::post('/userlogin','UserauthController@authenticate')->middleware('throttle:5,1');
```

---

### 6. Self-Registration เปิดให้ทุกคนสมัครได้ ไม่มีการอนุมัติ
**ไฟล์:** `app/Http/Controllers/Auth/RegisterController.php`  
**สถานะ:** ✅ **แก้ไขแล้ว** (ปิดระบบสมัครสมาชิกหน้าเว็บสาธารณะ โดยให้ส่งผลลัพธ์เป็น 403 Forbidden และให้แอดมินสร้างผ่านระบบหลังบ้านเท่านั้น)

---

### 7. Session Lifetime นานเกินไป (1,440 นาที = 24 ชั่วโมง)
**ไฟล์:** `.env` บรรทัด 25  
**สถานะ:** ✅ **แก้ไขแล้ว** (ปรับลดลงเหลือ 120 นาที ในไฟล์ `.env`)

---

## 🟡 Medium

### 8. หลาย Route ที่มีข้อมูล sensitive ไม่มี `auth` Middleware
**ไฟล์:** `routes/web.php` และ Controllers  
**สถานะ:** ✅ **แก้ไขแล้ว** (เพิ่ม `auth` middleware ลงใน `PdfController`, `pdfController2`, `newpdfController`, `ExcelController` และปรับปรุง construct ใน `HomeController` เพื่อครอบคลุมหน้าจอและรายงานสำคัญทั้งหมด)

---

### 9. Session ไม่ได้ถูก Encrypt
**ไฟล์:** `config/session.php` บรรทัด 50  
**สถานะ:** ✅ **แก้ไขแล้ว** (เพิ่ม `SESSION_ENCRYPT=true` ในไฟล์ `.env`)

---

### 10. `centermiddleware` อาจไม่ถูก register ในระบบ
**ไฟล์:** `bootstrap/app.php`  
**สถานะ:** ✅ **ตรวจสอบแล้วใช้งานได้จริง** (พบว่ามีการ register ชี้ไปยัง `mymiddleware::class` ใน `bootstrap/app.php` สำหรับ Laravel 13 เรียบร้อยแล้ว)

---

### 11. Raw SQL Queries หลายจุด (ใช้ static string ไม่มี binding)
**ไฟล์:** `app/Http/Controllers/HomeController.php`  
**สถานะ:** ✅ **ตรวจสอบแล้วปลอดภัย** (เนื่องจากเป็น Static Query ที่ไม่มี Parameter จาก User Input จึงไม่เกิด SQL Injection)

---

### 12. `APP_NAME` ว่างเปล่า ทำให้ session cookie ชื่อ `-session`
**ไฟล์:** `.env`  
**สถานะ:** ✅ **แก้ไขแล้ว** (กำหนด `APP_NAME=sillapa` เรียบร้อย)

---

## 🟢 Low / Info

### 13. Redis ไม่มีรหัสผ่าน
**สถานะ:** ℹ️ แนะนำให้ตั้งค่ารหัสผ่านหากเปิดการใช้งานผ่านเครือข่ายภายนอก

---

### 14. Mail SMTP ยังใช้ค่า Mailtrap (test environment)
**สถานะ:** ℹ️ ควรเปลี่ยนเป็นระบบเมลหลักเมื่อนำขึ้นระบบจริงเต็มรูปแบบ

---

### 15. `minimum-stability: dev` ใน composer.json
**ไฟล์:** `composer.json`  
**สถานะ:** ✅ **แก้ไขแล้ว** (เปลี่ยนเป็น `stable` เรียบร้อย)

---

### 16. Session Driver ไม่ตรงกันระหว่าง .env กับ config default
**สถานะ:** ℹ️ ปัจจุบันตั้งค่าเป็น `file` สามารถใช้งานได้ตามปกติ

---

## 📋 สรุปความคืบหน้าการ Action
- **SQL Injection 🔴** -> **แก้ไขแล้ว**
- **Unrestricted Upload 🔴** -> **แก้ไขแล้ว**
- **Type Juggling 🟠** -> **แก้ไขแล้ว**
- **Rate Limit Login 🟠** -> **แก้ไขแล้ว**
- **Registration Bypass 🟠** -> **แก้ไขแล้ว** (ปิดการลงทะเบียนหน้าเว็บชั่วคราว)
- **Session Lifetime 🟠** -> **แก้ไขแล้ว**
- **Routes Middleware 🟡** -> **แก้ไขแล้ว**
- **Session Encryption 🟡** -> **แก้ไขแล้ว**
- **minimum-stability 🟢** -> **แก้ไขแล้ว**
