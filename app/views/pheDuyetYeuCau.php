<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>

<style>
    .main-content {
        background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }
</style>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <style>
        .main-content {
            background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        </style>
        <h2 class="main-title" style="text-align:center; font-size: 1.4em; margin-bottom: 20px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
            PHÊ DUYỆT CÁC YÊU CẦU
        </h2>

        <div class="action-group" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
            <a href="index.php?page=phe-duyet-cac-yeu-cau&type=capnvl" class="nav-button">Cung cấp nguyên vật liệu</a>
            <a href="index.php?page=phe-duyet-cac-yeu-cau&type=nhapkho" class="nav-button">Nhập kho nguyên vật liệu</a>
            <a href="index.php?page=phe-duyet-cac-yeu-cau&type=kiemtra" class="nav-button">Kiểm tra chất lượng</a>
        </div>

        <section class="request-list">
            <h3 class="section-title" style="background:#9bc3ebff; padding:10px 15px; border-radius:5px; color:#333; text-align:center;">
                DANH SÁCH PHIẾU YÊU CẦU
            </h3>

            <table class="data-table list-table" style="width:100%; border-collapse:collapse; margin-top:15px;">
                <thead>
                    <tr style="background:#f8f9fa; border-bottom:2px solid #dee2e6;">
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">STT</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Tên phiếu</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Người lập</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Ngày lập</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Trạng thái</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($danhSachYeuCau)): ?>
                        <?php foreach ($danhSachYeuCau as $i => $yc): ?>
                            <?php
                            // Xác định mã phiếu theo loại
                            $maPhieu = '';
                            switch ($type) {
                                case 'capnvl': $maPhieu = $yc['maYCCC'] ?? ''; break;
                                case 'nhapkho': $maPhieu = $yc['maYCNK'] ?? ''; break;
                                case 'kiemtra': $maPhieu = $yc['maYC'] ?? ''; break;
                            }
                            ?>
                            <tr style="text-align:center;">
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;"><?= $i + 1 ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;"><?= htmlspecialchars($yc['tenPhieu'] ?? 'Không có tên') ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;"><?= htmlspecialchars($yc['tenNguoiLap'] ?? 'Không rõ') ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;"><?= !empty($yc['ngayLap']) ? date('d/m/Y', strtotime($yc['ngayLap'])) : '-' ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;"><?= htmlspecialchars($yc['trangThai'] ?? 'Chưa xác định') ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">
                                    <a href="index.php?page=chi-tiet-phe-duyet-yeu-cau&type=<?= urlencode($type) ?>&maPhieu=<?= urlencode($maPhieu) ?>"
                                       class="btn-details"
                                       style="text-decoration:none; background-color:#007bff; color:#fff; padding:6px 12px; border-radius:5px;">
                                       ➜ Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;">Không có yêu cầu nào cần phê duyệt</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- ======================= MODAL THÔNG BÁO ======================= -->
<div class="modal-overlay" id="status-modal"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000;">
    <div class="modal-content"
         style="background:white; padding:30px 40px; border-radius:8px; text-align:center;
                box-shadow:0 4px 15px rgba(0,0,0,0.2); min-width:300px; animation:fadeIn 0.3s;">
        <p id="status-message" style="font-size:1.1em; font-weight:bold; margin-bottom:25px;"></p>
        <button id="btn-close-modal"
                style="background-color:#f0f0f0; color:#333; border:1px solid #ccc; 
                       padding:8px 20px; border-radius:5px; cursor:pointer;">
            Đóng
        </button>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity:0; transform:scale(0.95); }
    to { opacity:1; transform:scale(1); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('status-modal');
    const msg = document.getElementById('status-message');
    const btnClose = document.getElementById('btn-close-modal');
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'approved') {
        msg.textContent = "PHÊ DUYỆT PHIẾU THÀNH CÔNG!";
        msg.style.color = "#28a745";
        modal.style.display = "flex";
    } else if (status === 'rejected') {
        msg.textContent = "PHIẾU ĐÃ BỊ TỪ CHỐI!";
        msg.style.color = "#dc3545";
        modal.style.display = "flex";
    }

    if (btnClose) {
        btnClose.addEventListener("click", () => modal.style.display = "none");
        modal.addEventListener("click", e => { if (e.target === modal) modal.style.display = "none"; });
    }
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
