<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';

$errors = $errors ?? [];
$old    = $old ?? [];

function v($key, $nhanvien, $old) {
    return htmlspecialchars($old[$key] ?? $nhanvien[$key] ?? '');
}
?>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <div class="nhanvien-container">
            <h2>Sửa thông tin nhân viên</h2>

            <?php if (!empty($errors['form'])): ?>
                <div class="alert-error"><?= htmlspecialchars($errors['form']) ?></div>
            <?php endif; ?>

            <!-- ✅ bỏ novalidate để browser hỗ trợ required/pattern (nhưng vẫn dùng JS để disable nút) -->
            <form id="nvForm" method="POST" action="index.php?page=capnhatnv">
                <input type="hidden" name="maND" value="<?= v('maND', $nhanvien, $old) ?>">

                <!-- HỌ TÊN -->
                <label>Họ tên</label>
                <input
                    type="text"
                    name="hoTen"
                    value="<?= v('hoTen', $nhanvien, $old) ?>"
                    required
                    minlength="2"
                    maxlength="50"
                    pattern="^(?=.{2,50}$)[A-Za-zÀ-ỹà-ỹ]+(?:[ '\-][A-Za-zÀ-ỹà-ỹ]+)*$"
                >
                <div class="field-error" id="err-hoTen">
                    <?= !empty($errors['hoTen']) ? htmlspecialchars($errors['hoTen']) : '' ?>
                </div>

                <!-- GIỚI TÍNH -->
                <label>Giới tính</label>
                <?php $gt = $old['gioiTinh'] ?? ($nhanvien['gioiTinh'] ?? 'Nam'); ?>
                <select name="gioiTinh" required>
                    <option value="Nam" <?= $gt === 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ"  <?= $gt === 'Nữ'  ? 'selected' : '' ?>>Nữ</option>
                </select>
                <div class="field-error" id="err-gioiTinh">
                    <?= !empty($errors['gioiTinh']) ? htmlspecialchars($errors['gioiTinh']) : '' ?>
                </div>

                <!-- NGÀY SINH -->
                <label>Ngày sinh</label>
                <input
                    type="date"
                    name="ngaySinh"
                    value="<?= v('ngaySinh', $nhanvien, $old) ?>"
                    required
                >
                <div class="field-error" id="err-ngaySinh">
                    <?= !empty($errors['ngaySinh']) ? htmlspecialchars($errors['ngaySinh']) : '' ?>
                </div>

                <!-- PHÒNG BAN -->
                <label>Phòng ban</label>
                <?php $pb = $old['phongBan'] ?? ($nhanvien['phongBan'] ?? ''); ?>
                <select name="phongBan" required>
                    <option value="">-- Chọn phòng ban --</option>
                    <option value="Xưởng cắt" <?= $pb === 'Xưởng cắt' ? 'selected' : '' ?>>Xưởng cắt</option>
                    <option value="Xưởng may" <?= $pb === 'Xưởng may' ? 'selected' : '' ?>>Xưởng may</option>
                </select>
                <div class="field-error" id="err-phongBan">
                    <?= !empty($errors['phongBan']) ? htmlspecialchars($errors['phongBan']) : '' ?>
                </div>

                <!-- ĐỊA CHỈ -->
                <label>Địa chỉ</label>
                <input
                    type="text"
                    name="diaChi"
                    value="<?= v('diaChi', $nhanvien, $old) ?>"
                    required
                    minlength="5"
                    maxlength="120"
                    pattern="^(?=.{5,120}$)[A-Za-zÀ-ỹà-ỹ0-9\s,.\-\/#]+$"
                >
                <div class="field-error" id="err-diaChi">
                    <?= !empty($errors['diaChi']) ? htmlspecialchars($errors['diaChi']) : '' ?>
                </div>

                <!-- EMAIL -->
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="<?= v('email', $nhanvien, $old) ?>"
                    required
                >
                <div class="field-error" id="err-email">
                    <?= !empty($errors['email']) ? htmlspecialchars($errors['email']) : '' ?>
                </div>

                <!-- SỐ ĐIỆN THOẠI -->
                <label>Số điện thoại</label>
                <input
                    type="text"
                    name="soDienThoai"
                    value="<?= v('soDienThoai', $nhanvien, $old) ?>"
                    required
                    pattern="^(0\d{9}|\+84\d{9})$"
                >
                <div class="field-error" id="err-soDienThoai">
                    <?= !empty($errors['soDienThoai']) ? htmlspecialchars($errors['soDienThoai']) : '' ?>
                </div>

                <div class="form-buttons">
                    <!-- ✅ disabled mặc định, JS sẽ bật khi hợp lệ -->
                    <button id="saveBtn" type="submit" class="btn btn-primary" disabled>Lưu</button>
                    <a class="btn btn-cancel" href="index.php?page=suanhanvien">Hủy</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
.field-error{
    margin-top: 6px;
    font-size: 13px;
    color: #dc3545;
    font-weight: 600;
    min-height: 18px;
}

.alert-error{
    background: #ffecec;
    border: 1px solid #f5b5b5;
    color: #b42318;
    padding: 10px 12px;
    border-radius: 10px;
    margin-bottom: 12px;
    font-size: 14px;
}

.nhanvien-container {
    background-color: #fff;
    border-radius: 12px;
    padding: 40px 50px;
    margin: 20px auto;
    max-width: 600px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.nhanvien-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #0d1a44;
}

label {
    display: block;
    margin-top: 12px;
    font-weight: 600;
    font-size: 14px;
}

input, select {
    width: 100%;
    padding: 9px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    background-color: #fff;
}

input:focus, select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.15);
}

.form-buttons {
    text-align: center;
    margin-top: 25px;
    display:flex;
    justify-content:center;
    gap:10px;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    padding: 8px 22px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 700;
}
.btn-primary:hover { background-color: #0069d9; }

/* ✅ disable nhìn rõ */
.btn-primary:disabled{
    opacity: .55;
    cursor: not-allowed;
}

.btn-cancel{
    background:#e9ecef;
    color:#111;
    padding: 8px 22px;
    border-radius: 6px;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-weight: 700;
}
.btn-cancel:hover{ background:#dde2e7; }
</style>

<script>
(function () {
    const form = document.getElementById('nvForm');
    const saveBtn = document.getElementById('saveBtn');

    const els = {
        hoTen: form.querySelector('[name="hoTen"]'),
        gioiTinh: form.querySelector('[name="gioiTinh"]'),
        ngaySinh: form.querySelector('[name="ngaySinh"]'),
        phongBan: form.querySelector('[name="phongBan"]'),
        diaChi: form.querySelector('[name="diaChi"]'),
        email: form.querySelector('[name="email"]'),
        soDienThoai: form.querySelector('[name="soDienThoai"]'),
    };

    const err = {
        hoTen: document.getElementById('err-hoTen'),
        gioiTinh: document.getElementById('err-gioiTinh'),
        ngaySinh: document.getElementById('err-ngaySinh'),
        phongBan: document.getElementById('err-phongBan'),
        diaChi: document.getElementById('err-diaChi'),
        email: document.getElementById('err-email'),
        soDienThoai: document.getElementById('err-soDienThoai'),
    };

    // Regex giống server (nhẹ nhàng)
    const reHoTen = /^(?=.{2,50}$)\p{L}+(?:[ '\-]\p{L}+)*$/u;
    const reDiaChi = /^(?=.{5,120}$)[\p{L}\p{N}\s,.\-\/#]+$/u;
    const rePhone = /^(0\d{9}|\+84\d{9})$/;

    function setErr(field, msg) { err[field].textContent = msg || ''; }
    function clearErr(field) { err[field].textContent = ''; }

    function calcAge(dateStr) {
        // dateStr: YYYY-MM-DD
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return NaN;
        const today = new Date();
        let age = today.getFullYear() - d.getFullYear();
        const m = today.getMonth() - d.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
        return age;
    }

    function validateHoTen() {
        const v = els.hoTen.value.trim();
        if (!v) { setErr('hoTen', 'Vui lòng nhập họ tên.'); return false; }
        if (!reHoTen.test(v)) {
            setErr('hoTen', "Họ tên 2–50 ký tự, chỉ gồm chữ và khoảng trắng.");
            return false;
        }
        clearErr('hoTen'); return true;
    }

    function validateNgaySinh() {
        const v = els.ngaySinh.value.trim();
        if (!v) { setErr('ngaySinh', 'Vui lòng chọn ngày sinh.'); return false; }
        const age = calcAge(v);
        if (!Number.isFinite(age)) { setErr('ngaySinh', 'Ngày sinh không hợp lệ.'); return false; }
        if (age < 16) { setErr('ngaySinh', 'Nhân viên phải từ 16 tuổi trở lên.'); return false; }
        clearErr('ngaySinh'); return true;
    }

    function validatePhongBan() {
        const v = els.phongBan.value.trim();
        if (!v) { setErr('phongBan', 'Vui lòng chọn phòng ban.'); return false; }
        // whitelist
        if (!['Xưởng cắt','Xưởng may'].includes(v)) { setErr('phongBan', 'Phòng ban không hợp lệ.'); return false; }
        clearErr('phongBan'); return true;
    }

    function validateDiaChi() {
        const v = els.diaChi.value.trim();
        if (!v) { setErr('diaChi', 'Vui lòng nhập địa chỉ.'); return false; }
        if (!reDiaChi.test(v)) {
            setErr('diaChi', 'Địa chỉ 5–120 ký tự, chỉ gồm chữ/số và các ký tự');
            return false;
        }
        clearErr('diaChi'); return true;
    }

    function validateEmail() {
        const v = els.email.value.trim();
        if (!v) { setErr('email', 'Vui lòng nhập email.'); return false; }
        // dùng constraint của browser cho type=email
        if (!els.email.checkValidity()) { setErr('email', 'Email không hợp lệ.'); return false; }
        clearErr('email'); return true;
    }

    function validatePhone() {
        const v = els.soDienThoai.value.trim();
        if (!v) { setErr('soDienThoai', 'Vui lòng nhập số điện thoại.'); return false; }
        if (!rePhone.test(v)) { setErr('soDienThoai', 'Số điện thoại phải dạng 0xxxxxxxxx'); return false; }
        clearErr('soDienThoai'); return true;
    }

    function validateGioiTinh() {
        const v = els.gioiTinh.value.trim();
        if (!v) { setErr('gioiTinh', 'Vui lòng chọn giới tính.'); return false; }
        if (!['Nam','Nữ'].includes(v)) { setErr('gioiTinh', 'Giới tính không hợp lệ.'); return false; }
        clearErr('gioiTinh'); return true;
    }

    function validateAll() {
        const ok =
            validateHoTen() &
            validateGioiTinh() &
            validateNgaySinh() &
            validatePhongBan() &
            validateDiaChi() &
            validateEmail() &
            validatePhone();

        // ✅ bật/tắt nút Lưu
        saveBtn.disabled = !Boolean(ok);
        return Boolean(ok);
    }

    // validate realtime
    Object.values(els).forEach(el => {
        el.addEventListener('input', validateAll);
        el.addEventListener('change', validateAll);
        el.addEventListener('blur', validateAll);
    });

    // chặn submit nếu còn lỗi (double safety)
    form.addEventListener('submit', function (e) {
        if (!validateAll()) {
            e.preventDefault();
            // focus field đầu tiên sai
            const order = ['hoTen','gioiTinh','ngaySinh','phongBan','diaChi','email','soDienThoai'];
            for (const k of order) {
                if (err[k] && err[k].textContent) { els[k].focus(); break; }
            }
        }
    });

    // chạy 1 lần lúc load để set trạng thái nút
    validateAll();
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form[action*="capnhatnv"]') || document.querySelector('form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    if (!confirm("Bạn có chắc chắn muốn lưu thay đổi không?")) {
      e.preventDefault();
    }
  });
});
</script>
<?php require_once 'app/views/layouts/footer.php'; ?>
