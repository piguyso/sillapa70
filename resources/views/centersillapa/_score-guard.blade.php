{{-- ตรวจคะแนนก่อนส่ง: 0-100, ลำดับ 1-3 ห้ามเท่ากัน; ใช้ checkScores(action) ก่อนบันทึก/ประกาศผล (ฝั่งเซิร์ฟเวอร์ตรวจซ้ำเสมอ) --}}
<script>
function checkScores(action) {
    action = String(action);
    if (action !== '0' && action !== '1') return true;
    var inputs = document.querySelectorAll('input[name^="activity_score["]');
    var scores = [];
    for (var i = 0; i < inputs.length; i++) {
        var raw = inputs[i].value.trim();
        var v = Number(raw);
        if (raw === '' || isNaN(v) || v < 0 || v > 100) {
            inputs[i].focus();
            appAlert('คะแนนต้องเป็นตัวเลขระหว่าง 0 ถึง 100 (ห้ามเกิน 100)');
            return false;
        }
        scores.push(v);
    }
    var sorted = scores.slice().sort(function (a, b) { return b - a; });
    for (var p = 0; p < sorted.length; p++) {
        if (sorted[p] <= 0) break;
        // ตำแหน่ง 1-4 ห้ามคะแนนเท่ากับทีมอื่น
        var sameBefore = p > 0 && sorted[p - 1] === sorted[p];
        var sameAfter = p + 1 < sorted.length && sorted[p + 1] === sorted[p];
        var start = sorted.indexOf(sorted[p]) + 1;
        if ((sameBefore || sameAfter) && start <= 4) {
            appAlert('ลำดับที่ 1-4 ห้ามมีคะแนนเท่ากัน (พบคะแนน ' + sorted[p] + ' ซ้ำ) กรุณาแก้ไขก่อน ส่วนลำดับที่ 5 ขึ้นไปเท่ากันได้ จะได้ลำดับเดียวกันและข้ามลำดับถัดไป');
            return false;
        }
    }
    return true;
}
</script>
