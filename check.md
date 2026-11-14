# รายงานตรวจสอบการทำงานของระบบ

วันที่ตรวจสอบ: 14 พฤศจิกายน 2569  
ระบบ: งานศิลปหัตถกรรมนักเรียน `sillapa.cpn1.go.th/2570`  
หมายเหตุ: การตรวจสอบครั้งนี้ไม่รวมการตรวจด้านความปลอดภัย

## สรุปผล

- ทดสอบ GET ภายใน Laravel จำนวน 172 เส้นทาง กับผู้ใช้ 5 สถานะ รวม 860 requests
- ตอบสำเร็จ 200 จำนวน 582 ครั้ง
- Redirect ตามขั้นตอนหรือสิทธิ์ 302 จำนวน 154 ครั้ง
- ปฏิเสธสิทธิ์ 403 จำนวน 24 ครั้ง
- ไม่พบหน้า 404 จำนวน 5 ครั้ง
- Internal Server Error 500 จำนวน 95 ครั้ง กระจุกตัวใน 40 URL
- PDF แบบ DomPDF จำนวน 8 รูปแบบสร้างสำเร็จทั้งหมด
- Blade templates compile ผ่าน
- Migration ทำงานครบทุกไฟล์
- PHPUnit ผ่าน 6 tests รวม 14 assertions
- ไม่พบกิจกรรมที่ขาดความสัมพันธ์กับกลุ่มสาระ สาขา หรือระดับชั้น
- ไม่มีข้อมูลจริงถูกเพิ่ม แก้ไข หรือลบระหว่างการทดสอบ

## ปัญหาระดับวิกฤต

### 1. เข้า event จากหน้าเว็บจริงไม่ได้

หน้าแรกเปิดได้ที่:

```text
https://sillapa.cpn1.go.th/2570/
```

แต่ลิงก์ event ที่หน้าแรกสร้างขึ้นตอบ `404 Not Found`:

```text
https://sillapa.cpn1.go.th/2570/74
https://sillapa.cpn1.go.th/2570/73
```

เส้นทางแบบ `/2570/cpn1/74` และหน้าลูก เช่น login, ข่าว และผลการแข่งขันก็ตอบ 404 เช่นกัน

เมื่อจำลอง request ภายใน Laravel โดยตรง `/74` ตอบ 200 แสดงว่าปัญหาอยู่ที่ IIS rewrite หรือ front controller ไม่ใช่ route หลักของ Laravel

ไฟล์ที่เกี่ยวข้อง:

- `web.config`
- `public/web.config`
- `index.php`
- `public/index.php`

จุดน่าสงสัยคือ `index.php` ที่ root ยังเรียก `../cpn1/index.php` ขณะที่ route event ถูก rewrite ไป `public/index.php`

ผลกระทบ: ผู้ใช้งานทั่วไปไม่สามารถเข้าสู่ระบบการแข่งขันจากหน้าแรกได้

## ปัญหาระดับสูง

### 2. มี View ที่ถูกเรียกใช้แต่ไม่มีไฟล์จริง

พบอย่างน้อย 4 รายการ:

| View | จุดที่เรียกใช้ | ผลลัพธ์ |
|---|---|---|
| `home.home2` | `HomeController.php:369` | `/74/home` ตอบ 500 ทุกบทบาท |
| `adduser` | `HomeController.php:852` | `/74/adduser` ตอบ 500 |
| `auth.adduser2` | `MyUserController.php:56` | `/74/myuser/create` ตอบ 500 |
| `myuser.adddata` | `NoticeController` | หน้าเพิ่มข้อมูลที่เกี่ยวข้องตอบ 500 |

ตัวอย่าง error:

```text
View [home.home2] not found.
View [adduser] not found.
View [auth.adduser2] not found.
```

### 3. Resource route ของ Datatranfer เรียก method ที่ไม่มี

มีการประกาศ:

```php
Route::resource('datatranfer', 'area\DatatranferController');
```

แต่ controller ไม่มี method ต่อไปนี้:

- `create`
- `show`
- `edit`

เส้นทางที่ตอบ 500:

```text
/74/datatranfer/create
/74/datatranfer/{id}
/74/datatranfer/{id}/edit
```

ตัวอย่าง error:

```text
Method App\Http\Controllers\area\DatatranferController::create does not exist.
Method App\Http\Controllers\area\DatatranferController::show does not exist.
Method App\Http\Controllers\area\DatatranferController::edit does not exist.
```

### 4. ActivityController ใช้ชื่อโมเดลผิด

ที่ `ActivityController.php:116` มี type-hint เป็น `activity` แต่โมเดลจริงคือ `App\Activities`

ตัวอย่าง error:

```text
Class "App\Http\Controllers\activity" does not exist
```

ทำให้เส้นทางแสดงรายละเอียดกิจกรรมบางกรณีตอบ 500

### 5. หลายหน้าไม่จัดการกรณีค้นหาข้อมูลไม่พบ

Controller หลายตัวใช้ `find()`, `first()` หรือ array index แล้วส่งค่าต่อไปยัง view โดยไม่ตรวจสอบ `null` หรือ array ว่าง ทำให้ตอบ 500 แทน 404 หรือข้อความแจ้งผู้ใช้

Error ที่พบซ้ำ:

```text
Attempt to read property "subjectgroup_name" on null
Attempt to read property "schoolgroup_name" on null
Attempt to read property "jow_activity" on null
Attempt to read property "school" on null
Undefined array key 0
```

กลุ่มหน้าที่พบปัญหา:

- เพิ่มและแก้ไขกรรมการ
- เพิ่มและแก้ไขกรรมการเขต
- แก้ไขนักเรียน
- หน้าศูนย์แข่งขัน
- หน้ากลุ่มสาระ
- หน้าลงทะเบียนนักเรียน ครู และกรรมการ
- หน้าประกาศของเขต
- หน้าตั้งค่าเขต

ควรใช้ `findOrFail()` หรือเช็กข้อมูลก่อนส่งเข้า view และคืนค่า 404/ข้อความที่เหมาะสม

### 6. การบันทึกปีการศึกษาล้มเหลวได้

คอลัมน์ `events.academic_year` รองรับเพียง 4 ตัวอักษร แต่ `ConfigController` นำค่าจาก `app_year` ไปบันทึกโดยตรง โดยหน้าแก้ไข Config ไม่ได้ validate ว่าเป็นตัวเลข 4 หลัก

พบ error ใน production log:

```text
SQLSTATE[22001]: Data too long for column 'academic_year'
```

ตัวอย่างค่าที่ทำให้ผิดพลาด:

```text
ปีการศึกษา 2569
```

ค่าที่ฐานข้อมูลรองรับ:

```text
2569
```

## ปัญหาระดับปานกลาง

### 7. เส้นทางจำนวนมากและซ้ำซ้อน

ระบบลงทะเบียนทั้งหมด 652 routes และรองรับ URL สองรูปแบบพร้อมกัน:

```text
/{eventNumber}/...
/cpn1/{eventNumber}/...
```

ทำให้ดูแล route name, URL generation และ IIS rewrite ได้ยาก และมีโอกาสเป็นสาเหตุร่วมของปัญหา 404 ปัจจุบัน

### 8. Error บางหน้าขึ้นอยู่กับชนิด ID

หลาย route ใช้ parameter ชื่อ `{id}` เหมือนกัน แต่คาดหวังข้อมูลต่างชนิด เช่น:

- activity ID
- subject group ID
- branch ID
- school group ID
- addschool ID
- student ID
- judge ID

ถ้าส่ง ID ผิดชนิด Controller มักตอบ 500 แทน 404 ทำให้ลิงก์เก่า ลิงก์ผิด หรือข้อมูลที่ถูกลบไปแล้วทำให้หน้าเว็บล้มได้

### 9. TCPDF เคยส่ง header ไม่สำเร็จ

พบประวัติใน production log:

```text
Cannot modify header information - headers already sent
```

อาจทำให้การดาวน์โหลดเกียรติบัตรหรือ PDF บางเส้นทางตอบ 500 แม้ PDF ที่ใช้ DomPDF ในการทดสอบรอบนี้จะสร้างสำเร็จ

## ผลทดสอบ PDF

PDF ต่อไปนี้สร้างสำเร็จในหน่วยความจำจากข้อมูลปี 2569:

| เอกสาร | ผลลัพธ์ |
|---|---|
| ใบลงทะเบียนนักเรียน | ผ่าน |
| ใบลงทะเบียนครู | ผ่าน |
| ใบลงทะเบียนกรรมการ | ผ่าน |
| ใบลงทะเบียนกรรมการประเภท 2 | ผ่าน |
| รายงานอันดับ/คะแนนสูงสุด | ผ่าน |
| เอกสารลำดับ/สุ่ม | ผ่าน |
| เอกสารบันทึกคะแนน | ผ่าน |
| เอกสารคิดเลขเร็ว | ผ่าน |

## ผลทดสอบฐานข้อมูลและโครงสร้าง

- Migration ทุกไฟล์มีสถานะ `Ran`
- Event 73 ใช้ปีการศึกษา 2568 และเป็นข้อมูลย้อนหลัง
- Event 74 ใช้ปีการศึกษา 2569 และเป็น event ที่ active
- ผู้ใช้ position 0 จำนวน 101 ราย
- ผู้ใช้ position 1 จำนวน 10 ราย
- ผู้ใช้ position 5 จำนวน 10 ราย
- ผู้ใช้ position 10 จำนวน 2 ราย
- โรงเรียน 126 แห่ง
- เครือข่ายโรงเรียน 10 เครือข่าย
- กลุ่มสาระ 34 รายการ
- กิจกรรม 352 รายการ
- ทีมสมัครแข่งขัน 1,925 รายการ
- นักเรียน/ครูในรายการสมัคร 7,415 รายการ
- กรรมการ 1,090 รายการ
- ไม่พบกิจกรรมที่อ้างถึง subject, branch หรือ class ที่ไม่มีอยู่

## ขอบเขตของ Write Routes

ระบบมี route ที่เปลี่ยนข้อมูลดังนี้:

- POST จำนวน 100 registrations
- PUT/PATCH จำนวน 50 registrations
- PUT จำนวน 7 registrations
- DELETE จำนวน 54 registrations

ได้ตรวจการลงทะเบียน route, controller และ middleware แล้ว แต่ไม่ได้ส่งคำขอที่ทำให้เพิ่ม แก้ไข ลบ ล้าง หรือโอนข้อมูลจริงบนฐาน production

การทดสอบ successful mutation ทุกเส้นทางควรทำบนฐานข้อมูลสำเนาหรือ test database โดยครอบ transaction และ rollback รวมถึงแยกพื้นที่ไฟล์ upload ชั่วคราว เนื่องจาก rollback ฐานข้อมูลไม่สามารถย้อนกลับไฟล์ที่ถูกลบหรือเขียนทับได้

## ลำดับการแก้ไขที่แนะนำ

1. แก้ IIS rewrite/front controller ให้ `/2570/74` เข้า Laravel ได้
2. เพิ่มหรือแก้ชื่อ view ที่หายทั้ง 4 รายการ
3. จำกัด resource route ของ Datatranfer เฉพาะ method ที่มี หรือเพิ่ม method ที่ขาด
4. แก้ type-hint ใน ActivityController จาก `activity` เป็นโมเดลที่ถูกต้อง
5. เพิ่มการจัดการ `null` และ record not found ใน controller ทุกกลุ่ม
6. เพิ่ม validation ปีการศึกษาให้รับเฉพาะตัวเลข 4 หลัก
7. ทดสอบ TCPDF และเกียรติบัตรทุกแบบผ่าน HTTP จริง
8. เพิ่ม Feature tests สำหรับทุกบทบาทและทุกโมดูล
9. สร้าง test database เพื่อทดสอบ POST/PUT/DELETE แบบครบวงจร

## ไฟล์ผลการทดสอบ

- ชุดทดสอบ: `tests/system_smoke.php`
- ผลละเอียดทุก request: `storage/app/system-smoke-results.json`
- Application log: `storage/logs/laravel.log`

