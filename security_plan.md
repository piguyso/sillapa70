# แผนยกระดับความปลอดภัยระบบ sillapa

**วันที่จัดทำ:** 18 กรกฎาคม 2026  
**ขอบเขต:** Static code/configuration review ของ Laravel application และ dependency audit  
**ข้อจำกัด:** เป็นการตรวจสอบจาก source code และ configuration ที่เข้าถึงได้ ไม่ใช่ penetration test จากภายนอก และไม่ได้เปิดเผย/คัดลอก secret ที่อาจอยู่ใน `.env`

---

## สรุปสำหรับผู้ดูแลระบบ

พบความเสี่ยงที่ต้องดำเนินการเร่งด่วน 2 เรื่อง:

1. **Critical — `PdfController` ไม่มี authentication และไม่มี authorization ระดับข้อมูล**
   - `app/Http/Controllers/PdfController.php:28-31` มี `auth` middleware ถูก comment out
   - หลายเมธอดรับ `$id` หรือ `$request->id` แล้วดึงข้อมูลนักเรียน ครู โรงเรียน และกรรมการจากฐานข้อมูลโดยตรง
   - ผู้โจมตีที่เดา ID ได้อาจเรียก endpoint ที่ route เปิดไว้ เพื่ออ่าน/สร้าง PDF ที่มีข้อมูลส่วนบุคคลของหน่วยงานอื่น (IDOR / unauthorized disclosure)
   - ยังพบ `dd("หาไฟล์ไม่พบที่: " . $imagePath)` หลายจุด ซึ่งอาจเปิดเผย absolute path ของเซิร์ฟเวอร์เมื่อเกิด error

2. **High — ฟังก์ชันย้าย/คัดลอกข้อมูลและ SQL DDL ต้องจำกัดสิทธิ์สูงสุด**
   - `CopyDataController` แสดงรายชื่อฐานข้อมูลที่ขึ้นต้นด้วย `sillapa` และทำ data transfer
   - `DatatranferController` ใช้ `DB::statement()` สร้าง database/table แบบ dynamic รวมถึงตั้ง `sql_mode` เป็นค่าว่างชั่วคราว
   - แม้ `CopyDataController` ตรวจ connection จาก `config('database.connections')` จึงไม่พบ SQL injection โดยตรงในจุดนี้ แต่ endpoint เหล่านี้ต้องจำกัดเฉพาะผู้ดูแลระบบและต้อง audit ทุกการทำงาน

ผล `composer audit --locked --no-interaction` วันที่ 18 กรกฎาคม 2026: **ไม่พบ security vulnerability advisories** ในแพ็กเกจที่ lock อยู่ ณ เวลาตรวจสอบ

---

## ผลการตรวจสอบที่ยืนยันได้

| รหัส | ระดับ | สถานะ | หลักฐาน/ผลกระทบ |
|---|---|---|---|
| SEC-01 | Critical | ต้องแก้ไข | `PdfController` ไม่มี `auth`; endpoints สร้าง PDF จาก ID และมีข้อมูลส่วนบุคคล |
| SEC-02 | High | ต้องแก้ไข | `CopyDataController` มี database enumeration/data copy; `DatatranferController` มี dynamic DDL |
| SEC-03 | High | ต้องตรวจ route และแก้หากเปิดถึง | PDF controller ยังไม่มีการตรวจ ownership/scope ตาม `area`, school group หรือ role |
| SEC-04 | Medium | ต้องแก้ไข | `dd()` ใน PDF flow เปิดเผย path/debug detail เมื่อ production เกิดข้อผิดพลาด |
| SEC-05 | Medium | ต้องแก้ไข | `areamiddleware` เรียก `Auth::user()` โดยไม่ป้องกัน unauthenticated request; ต้องใช้ร่วมกับ `auth` และปรับเป็น explicit authorization |
| SEC-06 | Medium | ต้องยืนยัน production | `.env.example` ตั้ง development defaults เช่น `APP_DEBUG=true`, `http://localhost`, `SESSION_ENCRYPT=false`; production `.env` ต้องไม่ใช้ค่าเหล่านี้ |
| SEC-07 | Medium | ต้องยืนยัน production | ต้องตรวจว่าบัญชี DB เป็น least-privilege user และใช้ TLS เมื่อ DB อยู่คนละเครื่อง |
| SEC-08 | Low | แนะนำ | Redis/Mail configuration ต้องไม่เปิดรับจากภายนอกโดยไม่มี authentication/TLS |
| SEC-09 | Info | ผ่าน ณ เวลาตรวจ | `composer audit --locked` ไม่พบ advisory |

---

## รายละเอียดและแนวทางแก้ไข

### SEC-01: ป้องกันการเข้าถึง PDF/ข้อมูลส่วนบุคคลโดยไม่ได้รับอนุญาต

**ไฟล์ที่พบ**
- `app/Http/Controllers/PdfController.php`
- โดยเฉพาะ constructor บรรทัด 28–31 และเมธอดที่รับ `$id`/`$request->id` เช่น `areaprintPDF`, `areaprintPDF2`, `printPDF`, `areaprintjowcerf` และรายงาน certificate อื่น ๆ

**สิ่งที่ต้องทำ**
1. ห้ามพึ่ง middleware ใน controller เพียงอย่างเดียว: จัด route PDF ทั้งหมดใน route group ที่มี `auth` เป็นขั้นต่ำ
2. เพิ่ม role middleware ที่ถูกต้องสำหรับงานนั้น เช่น `area`, `center`, `admin` (ต้องยืนยันชื่อ alias ใน `bootstrap/app.php`)
3. ใช้ Laravel Policy/Gate เพื่อยืนยันทุก record:
   - user ระดับพื้นที่เข้าถึงได้เฉพาะ `schoolgroup`/`area` ของตน
   - user ระดับโรงเรียนเข้าถึงได้เฉพาะ school ของตน
   - user ระดับส่วนกลางเท่านั้นที่เข้าถึงข้ามพื้นที่ได้
4. เปลี่ยน query ที่ใช้ `where('id', $id)->get()` เป็น model binding หรือ `firstOrFail()` หลัง authorize
5. ให้คะแนนและลำดับรางวัลมาจาก record ที่อนุญาตแล้ว ไม่เชื่อค่า `student_score`/`x` จาก request สำหรับเอกสารทางการ
6. แทนที่ `dd()` ด้วย log แบบไม่บันทึกข้อมูลอ่อนไหว และตอบกลับด้วย `abort(404)` หรือ error page ทั่วไป
7. เพิ่ม automated feature test: guest ต้องได้ 302/401, user พื้นที่อื่นต้องได้ 403, owner ต้องได้รับ 200

**เกณฑ์ผ่าน**
- ไม่มี PDF route ใดเข้าถึงได้โดย guest เว้นแต่กำหนดให้เป็น public อย่างจงใจและไม่มี PII
- การเปลี่ยน ID เป็นข้อมูลของพื้นที่อื่นต้องได้ HTTP 403/404
- Production error response ไม่มี full path, stack trace หรือ `dd()` output

---

### SEC-02: จำกัดสิทธิ์งานคัดลอก/โอนข้อมูลและ Dynamic DDL

**ไฟล์ที่พบ**
- `app/Http/Controllers/CopyDataController.php`
- `app/Http/Controllers/area/DatatranferController.php`

**สิ่งที่ต้องทำ**
1. จัดกลุ่ม route ให้ใช้ `auth` + middleware ผู้ดูแลระบบที่ explicit เช่น `role:system-admin`
2. สร้าง allowlist ของ source database/connection ที่อนุญาตจาก config เฉพาะ เช่น `data_transfer.allowed_sources`; ห้ามใช้รายชื่อ connection ทุกตัวโดยอัตโนมัติ
3. ห้ามแสดงผล `SHOW DATABASES` ต่อผู้ใช้ทั่วไป; หากจำเป็น ให้คืนค่าเฉพาะ allowlist ที่ผ่าน authorization
4. ตรวจชื่อ database/table แบบ strict allowlist หรือ regex ก่อนนำไปเป็น SQL identifier; parameter binding ไม่สามารถป้องกัน identifier injection ได้
5. แยก database account สำหรับงาน application ปกติ:
   - application user: เฉพาะ `SELECT/INSERT/UPDATE/DELETE` ของ schema ที่ต้องใช้
   - migration/deployment user: ใช้สิทธิ์ DDL เฉพาะช่วง deploy
   - ห้ามใช้ root/superuser สำหรับ web process
6. ย้ายงาน copy/backup ไป queue/CLI command ที่ไม่เปิด public route หากทำได้
7. ใช้ transaction ตามความเหมาะสม, จำกัดจำนวน record, ป้องกันงานซ้ำ และเขียน audit log ที่เก็บ actor, source, target, timestamp, result
8. ห้ามปล่อย `SET SESSION sql_mode = ''` ค้างหรือใช้เป็นวิธีแก้ปัญหาข้อมูล; แก้ข้อมูลวันที่/ข้อมูลไม่ถูกต้องก่อน transfer

**เกณฑ์ผ่าน**
- ผู้ใช้ที่ไม่ใช่ system admin ได้ 403
- ค่าที่อยู่นอก allowlist ไม่สามารถ trigger copy/DDL ได้
- audit log มีหลักฐานการ transfer ทุกครั้ง
- web application DB user ไม่มี `CREATE DATABASE`, `DROP`, `GRANT` หรือสิทธิ์ระดับ root

---

### SEC-03 และ SEC-05: ทำ authorization ให้สอดคล้องและตรวจได้

**ข้อสังเกต**
- `app/Http/Middleware/areamiddleware.php` แปลง `position` เป็น integer ในกรณี `>= 10` แล้ว ซึ่งช่วยลดปัญหา string comparison
- middleware ยังเรียก `Auth::user()->position` โดยตรง จึงผิดพลาดได้หาก route ไม่มี `auth` นำหน้า
- การอนุญาต `position == 5`, `position == 10`, หรือ `position >= 10` เป็นกติกาที่กระจายและตีความยาก

**สิ่งที่ต้องทำ**
1. ให้ route group เรียง middleware เป็น `auth` ก่อน `area`
2. เปลี่ยน magic number ของ `position` เป็น enum/constant/role mapping กลาง
3. ใช้ Policy สำหรับ resource ownership แทนการตัดสินเฉพาะ role
4. ทุก controller ที่อ่าน/แก้ข้อมูลตาม ID ต้องใช้ authorization ที่ server-side
5. สร้าง matrix สิทธิ์ (role × action × scope) และ test ตาม matrix

---

### SEC-04: ปิด debug disclosure

**สิ่งที่ต้องทำ**
1. ลบ `dd()`, `dump()`, debug helper และ response ที่ต่อ absolute path/exception message ออกทุก production flow
2. ตั้ง production:
   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://<production-host>
   LOG_LEVEL=warning
   ```
3. ตั้ง error pages แบบทั่วไป และเก็บรายละเอียดใน log ที่จำกัดสิทธิ์การอ่าน
4. ตรวจ web server ว่าไม่เสิร์ฟ `.env`, `storage/logs`, `.git`, backup files, หรือ directory listing

---

### SEC-06: Session, cookie และ HTTPS hardening

**สิ่งที่ต้องตั้งใน production**
```dotenv
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_LIFETIME=120
```

**งานประกอบ**
1. บังคับ redirect HTTP → HTTPS ที่ reverse proxy/web server และตั้ง HSTS หลังทดสอบครบ
2. ตรวจ `config/session.php` ว่าอ่านค่าจาก environment และ cookie name ไม่ใช่ชื่อ default ที่ชนกัน
3. หลัง login ต้อง regenerate session ID และหลัง logout ต้อง invalidate session
4. ตั้ง CSRF middleware กับทุก state-changing web route; exception ต้องมีเหตุผลและเอกสารกำกับ
5. จำกัด login ด้วย rate limit ที่ผูกทั้ง account และ IP; log failed login และแจ้งเตือนเมื่อผิดปกติ

---

### SEC-07: Secrets และฐานข้อมูล

1. ห้าม commit `.env`, backup, database dump หรือ credential ลง Git
2. หาก credential เคยปรากฏในเอกสาร/commit/log ให้ rotate รหัสผ่านทันที และตรวจ Git history กับ deployment logs
3. ใช้ secret manager หรืออย่างน้อย file permission ที่เข้มงวดสำหรับ `.env`
4. ใช้ DB user เฉพาะระบบ, password ยาวแบบสุ่ม, host restriction และ TLS หากเชื่อมข้าม host
5. ตั้ง backup encryption, retention และทดสอบ restore เป็นระยะ
6. ตรวจ `MYSQL_ATTR_SSL_CA`/DB TLS configuration เมื่อฐานข้อมูลไม่ได้อยู่ loopback เดียวกัน

---

## สิ่งที่ตรวจแล้วและไม่พบปัญหาโดยตรง

- สแกนคำสั่งอันตราย (`eval`, `exec`, `shell_exec`, `system`, `passthru`) ใน `app/**/*.php`: ไม่พบผลลัพธ์ใน pattern ที่ตรวจ
- Raw SQL จำนวนมากใช้เพื่อ aggregate/reporting; จากส่วนที่ตรวจพบ parameter binding (`?`, bindings) ใน query ที่มี parameter สำคัญ อย่างไรก็ตาม raw SQL ต้องทบทวนต่อเมื่อมีการเพิ่ม identifier หรือ input จาก request
- `CopyDataController` ตรวจชื่อ `$sourceDb` ว่าอยู่ใน `config('database.connections')` ก่อนเรียก `DB::connection()` จึงไม่เป็น SQL injection โดยตรง
- `composer audit --locked --no-interaction`: ไม่พบ dependency advisory ณ วันที่จัดทำแผน

---

## ความคลาดเคลื่อนกับ `security_scan.md`

รายงานเดิมระบุว่า `PdfController` ถูกครอบด้วย `auth` แล้ว แต่ source ปัจจุบันที่ตรวจพบมีบรรทัด:

```php
// $this->middleware('auth');
```

ดังนั้นต้องถือว่า **ยังไม่มีการยืนยันการป้องกัน** จนกว่าจะตรวจ route ที่ map ไปยังทุก PDF action และทดสอบด้วย guest account จริง การป้องกันที่ route level ยังใช้ได้ แต่ต้องมีหลักฐาน route list และ automated tests ประกอบ

รายงานเดิมยังกล่าวถึงการตั้งค่า production ใน `.env`; ไฟล์ `.env` ไม่ควรถูกอ่านหรือบันทึก secret ลงรายงานนี้ จึงกำหนดเป็นรายการตรวจยืนยันโดยผู้ดูแล deployment แทน

---

## แผนดำเนินการตามลำดับ

### ระยะ 0: ภายใน 24 ชั่วโมง
- [ ] ปิดหรือจำกัด PDF routes ที่ยังไม่ยืนยัน `auth`/authorization
- [ ] บังคับ `APP_DEBUG=false` ใน production และลบ `dd()` ใน public/request flows
- [ ] ตรวจว่า `.env` ไม่ถูกเสิร์ฟผ่านเว็บ และ rotate credential ที่อาจเคยเผยแพร่
- [ ] จำกัด Data Transfer/Copy routes เฉพาะ system admin หรือปิดชั่วคราว

### ระยะ 1: ภายใน 7 วัน
- [ ] สร้าง Policy สำหรับ PDF/certificate/report data ตาม area/school ownership
- [ ] จัด route groups ให้ครบ `auth` + role + throttling ที่เหมาะสม
- [ ] ทำ allowlist สำหรับ database transfer และ audit log
- [ ] แยก DB credential ของ application จาก admin/migration account
- [ ] ยืนยัน cookie secure, encryption, CSRF และ HTTPS/HSTS configuration
- [ ] เพิ่ม feature tests สำหรับ authentication, role และ IDOR

### ระยะ 2: ภายใน 30 วัน
- [ ] Refactor role magic numbers เป็น enum/central permission model
- [ ] ตั้ง dependency audit ใน CI/CD และอัปเดต dependencies ตามรอบ
- [ ] ตั้ง centralized logging, alert สำหรับ login/privileged data transfer และ backup test
- [ ] ทำ external authenticated penetration test และทดสอบ restore/incident response

---

## คำสั่งตรวจซ้ำที่แนะนำ

```powershell
composer audit --locked --no-interaction
php artisan route:list
php artisan test
```

ก่อน deploy ให้ตรวจ route list ว่าทุก endpoint ที่มีข้อมูลส่วนบุคคล การ export และการทำงานระดับผู้ดูแลระบบมี middleware/authorization ครบถ้วน และทดสอบสิทธิ์ข้ามพื้นที่ด้วยบัญชีทดสอบจริง