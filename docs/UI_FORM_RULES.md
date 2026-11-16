# กฎกลาง Form และการอัปโหลดรูป

## การอัปโหลดรูป

- ทุกหน้าต้องมีหน้าตัดรูป (crop) และภาพตัวอย่างก่อนบันทึก
- รับเฉพาะ JPG, PNG และ WebP; ไม่รองรับ GIF
- ฝั่งเซิร์ฟเวอร์ต้องตรวจชนิดไฟล์ซ้ำ และบันทึกผ่าน `App\Services\ImageUploadService`
- รูปที่บันทึกใหม่ต้องแปลงเป็น WebP เพื่อลดขนาดไฟล์
- สี ปุ่ม modal และ focus state ต้องใช้สี theme ผ่าน `--ui-primary` และ `--ui-secondary`

## Form

- หน้าใหม่ต้องใช้ Blade component ใน `resources/views/components/form`
- ใช้ `<x-form.input>`, `<x-form.textarea>`, `<x-form.select>`, `<x-form.editor>` และ `<x-form.button>`
- ห้ามสร้างรูปแบบ input/button เฉพาะหน้า เว้นแต่มีเหตุผลด้านการใช้งานชัดเจน
- ข้อผิดพลาดต้องแสดงใต้ field ด้วยรูปแบบ `ui-error`
- ค่า readonly/disabled ต้องมองเห็นชัดและแก้ไขไม่ได้จริง

## Alert, Confirm และ Message

- ห้ามใช้ `window.alert()`, `alert()`, `window.confirm()` หรือ `confirm()` ของ Browser ในโค้ดใหม่
- ใช้ `appAlert(message, options)` สำหรับข้อความที่ต้องให้ผู้ใช้รับทราบ
- ใช้ `appConfirm(message, options)` หรือ `appConfirmSubmit(element, message, options)` ก่อนการกระทำสำคัญ
- ผลลัพธ์จากฝั่งเซิร์ฟเวอร์ใช้ session `success`, `status`, `massage` หรือ `error` เพื่อแสดง Toast กลาง
- สี ปุ่ม icon และ modal ต้องรักษา theme ของปีการแข่งขัน
- การลบ Reset ประกาศผล และการกระทำย้อนกลับยาก ต้องมี Confirm เสมอ

