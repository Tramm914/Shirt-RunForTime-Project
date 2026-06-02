<?php require 'app/views/layouts/header.php'; ?>
<?php require 'app/views/layouts/nav.php'; ?>

<div class="main-layout-wrapper">
<?php require 'app/views/layouts/sidebar.php'; ?>

<main class="main-content">

<style>
.main-content {
    background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
    background-attachment: fixed;
    min-height: 100vh;
}
/* ===== CARD TỔNG ===== */
.kh-box{
    background:#fff;
    padding:32px;
    margin:20px;
    border-radius:14px;
    box-shadow:0 8px 24px rgba(15,23,42,.08);
}

/* ===== THÔNG TIN ĐƠN HÀNG ===== */
.order-info{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:24px;
    margin-bottom:36px;
}
.order-item{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:16px;
}
.order-item label{
    font-size:13px;
    color:#64748b;
}
.order-item div{
    margin-top:6px;
    font-size:16px;
    font-weight:600;
    color:#0f172a;
}

/* ===== CARD XƯỞNG ===== */
.xuong-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:24px;
    margin-bottom:30px;
    box-shadow:0 4px 16px rgba(15,23,42,.05);
}

.xuong-title{
    font-size:22px;
    font-weight:700;
    color:#2563eb;
    margin-bottom:12px;
}

.xuong-row{
    display:flex;
    gap:48px;
    font-size:15px;
    color:#0f172a;
    margin-bottom:6px;
}

.xuong-kpi{
    font-size:15px;
    margin-bottom:14px;
}

/* ===== HEADER NVL ===== */
.nvl-header{
    background:#e8f1fa;
    border:1px solid #cfe0f5;
    border-radius:10px;
    padding:10px;
    text-align:center;
    font-weight:600;
    color:#1e3a8a;
    margin:16px 0 14px;
}

/* ===== TABLE ===== */
.table-wrap{
    border:1px solid #e5e7eb;
    border-radius:12px;
    overflow:hidden;
}
table{
    width:100%;
    border-collapse:collapse;
}
thead{
    background:#eef3ff;
}
th{
    padding:12px 10px;
    font-size:14px;
    font-weight:600;
    color:#1e3a8a;
    text-align:center;
}
td{
    padding:12px 10px;
    font-size:14px;
    color:#0f172a;
    text-align:center;
    border-top:1px solid #e5e7eb;
}
tbody tr:hover{
    background:#f8fafc;
}


/* ===== NÚT QUAY LẠI ===== */
.back-wrap{
    text-align:right;
    margin-top:30px;
}
.back-btn{
    display:inline-block;
    padding:10px 20px;
    border-radius:12px;
    background:#e5e7eb;
    color:#111827;
    font-weight:600;
    text-decoration:none;
    transition:.2s;
}
.back-btn:hover{
    background:#d1d5db;
}
.page-title {
    text-align:center;
    font-size:22px;
    font-weight:700;
    color: #085da7;
    margin-bottom:20px;
}
</style>

<div class="kh-box">
    
<div class="page-title">
        Xem công việc của <?= htmlspecialchars($plan['tenKHSX']) ?>
    </div>
    <!-- ===== THÔNG TIN CHUNG ===== -->
    <div class="order-info">
        <div class="order-item">
            <label>Tên KHSX</label>
            <div><?= htmlspecialchars($plan['tenKHSX'] ?? '') ?></div>
        </div>
        <div class="order-item">
            <label>Tên sản phẩm</label>
            <div><?= htmlspecialchars($donHang['tenSanPham'] ?? '') ?></div>
        </div>
        <div class="order-item">
            <label>Số lượng cần sản xuất</label>
            <div><?= $donHang['soLuongSanXuat'] ?? '' ?></div>
        </div>
    </div>

<?php
function renderXuong($chiTiet, $maXuong, $tenXuong){
    $info = null;
    foreach ($chiTiet as $ct){
        if ($ct['maXuong'] == $maXuong){
            $info = $ct;
            break;
        }
    }
    if (!$info) return;
?>

    <div class="xuong-card">
        <div class="xuong-title"><?= $tenXuong ?></div>

        <div class="xuong-row">
            <div><b>Bắt đầu:</b> <?= $info['ngayBatDau'] ?></div>
            <div><b>Kết thúc:</b> <?= $info['ngayKetThuc'] ?></div>
        </div>

        <div class="xuong-kpi">
            <b>KPI:</b> <?= $info['KPI'] ?> <span style="color:#64748b">(SP/ngày)</span>
        </div>

        <div class="nvl-header">
            Nguyên vật liệu cần để sản xuất
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên NVL</th>
                        <th>Định mức / SP</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $stt = 1;
                foreach ($chiTiet as $ct):
                    if ($ct['maXuong'] == $maXuong):
                ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td><?= htmlspecialchars($ct['tenNVL']) ?></td>
                        <td><?= $ct['dinhMuc'] ?></td>
                        <td><?= $ct['soLuongNVL'] ?></td>
                    </tr>
                <?php endif; endforeach; ?>

                <?php if ($stt == 1): ?>
                    <tr><td colspan="5">Không có nguyên vật liệu</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php } ?>

<?php
renderXuong($chiTiet, 1, 'Xưởng Cắt');
renderXuong($chiTiet, 2, 'Xưởng May');
?>

    <div class="back-wrap">
        <a href="index.php?page=xemcongviec" class="back-btn">⬅ Quay lại</a>
    </div>

</div>

</main>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
