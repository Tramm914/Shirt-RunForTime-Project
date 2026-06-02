<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<div class="main-layout-wrapper">
<?php require_once 'app/views/layouts/sidebar.php'; ?>

<main class="main-content">

<style>
.main-content {
    background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
}
.box {
    background: #fff;
    padding: 30px 40px;
    border-radius: 14px;
    max-width: 900px;
    margin: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.box h2 {
    text-align: center;
    font-size: 24px;
    color: #0d1a44;
    margin-bottom: 25px;
    font-weight: 700;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #e5e7eb;
}

.info-row .label {
    font-weight: 600;
    color: #1e293b;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #0d1a44;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.2s;
}

.btn-export {
    background: #22c55e;
    color: white;
}

.btn-export:hover {
    background: #16a34a;
}

.btn-cancel {
    background: #e2e8f0;
    color: #334155;
}

.btn-cancel:hover {
    background: #cbd5e1;
}

.button-row {
    margin-top: 25px;
    display: flex;
    gap: 15px;
}
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.popup-box {
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    text-align: center;
    max-width: 350px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.popup-box h3 {
    margin-bottom: 15px;
    color: #dc2626;
    font-weight: 700;
}
</style>

<div class="box">
    <h2>📄 Chi tiết đơn hàng</h2>

    <div class="info-row">
        <span class="label">Mã đơn hàng:</span>
        <span><?= $donhang['maDonHang'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Tên đơn hàng:</span>
        <span><?= $donhang['tenDonHang'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Sản phẩm:</span>
        <span><?= $donhang['tenSanPham'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Số lượng sản xuất:</span>
        <span><?= $donhang['soLuongSanXuat'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Đơn vị:</span>
        <span><?= $donhang['donVi'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Địa chỉ nhận:</span>
        <span><?= $donhang['diaChiNhan'] ?></span>
    </div>
    <div class="info-row">
        <span class="label">Ngày giao:</span>
        <span><?= $donhang['ngayGiao'] ?></span>
    </div>

    <hr style="margin:25px 0">

    <h3 style="color:#0d1a44; margin-bottom:15px;">📦 Xuất thành phẩm</h3>

    <form id="formXuat">
        <input type="hidden" name="maDonHang" value="<?= $donhang['maDonHang'] ?>">

        <div class="form-group">
            <label>Số lượng xuất</label>
            <input type="number" name="soLuongXuat" min="1" value="<?= $donhang['soLuongSanXuat'] ?>" required>
        </div>

        <div class="form-group">
            <label>Ghi chú</label>
            <input type="text" name="ghiChu" placeholder="Nhập ghi chú nếu cần...">
        </div>

        <div class="button-row">
            <button type="submit" class="btn btn-export">Xuất kho</button>
            <a href="index.php?page=xuatthanhpham" class="btn btn-cancel">Quay lại</a>
        </div>
    </form>
</div>
<!-- Popup báo lỗi tồn kho -->
<div id="popupTonKho" class="popup-overlay" style="display:none;">
    <div class="popup-box">
        <h3>⚠️ Thông báo</h3>
        <p>Số lượng tồn kho không đủ để xuất!</p>
        <button id="closePopupBtn" class="btn btn-cancel" style="margin-top:15px;">Đóng</button>
    </div>
</div>
<script>
const popup = document.getElementById("popupTonKho");
const closePopupBtn = document.getElementById("closePopupBtn");

closePopupBtn.onclick = () => popup.style.display = "none";

document.getElementById("formXuat").onsubmit = async function(e) {
    e.preventDefault();
    
    let soLuongXuat = Number(this.soLuongXuat.value);
    let soLuongTon = Number(<?= $donhang['soLuongTon'] ?>);

    // 🔥 Hiện popup khi tồn kho không đủ
    if (soLuongXuat > soLuongTon) {
        popup.style.display = "flex";
        return;
    }

    // Nếu đủ kho → tiếp tục xuất
    let formData = new FormData(this);

    const res = await fetch("index.php?page=xuatthanhpham_xuat", {
        method: "POST",
        body: formData
    });

    const data = await res.json();

    if (data.success) {
        alert("✅ Xuất kho thành công!");
        window.location.href = "index.php?page=xuatthanhpham";
    } else {
        alert("❌ " + data.message);
    }
};
</script>
</main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
