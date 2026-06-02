<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tenNguoiLap = $_SESSION['user']['hoTen'] ?? 'Chưa đăng nhập';
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
        </style>

        <h2 class="main-title" style="color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">XUẤT KHO NGUYÊN VẬT LIỆU</h2>

        <div class="request-list">

            <h3 class="section-title">PHIẾU XUẤT KHO TỪ PHIẾU YÊU CẦU CUNG CẤP NVL</h3>

            <form id="form-xuat-kho-nvl" method="POST" action="index.php?page=luu-phieu-xuat-kho">

                <input type="hidden" name="maYCCC" value="<?= htmlspecialchars($thongTinPhieu['maYCCC'] ?? '') ?>">

                <div class="form-section1">
                    <div class="form-input-group1">
                        <label>Tên phiếu</label>
                        <?php
                            $tenPhieuGoc = $thongTinPhieu['tenPhieu'] ?? '';
                            $pos = strpos($tenPhieuGoc, "KHSX");
                            $maKH = ($pos !== false) ? substr($tenPhieuGoc, $pos) : '';
                            ?>
                        <input type="text" name="tenPhieu"
                                value="<?php echo 'Xuất NVL ' . htmlspecialchars($maKH); ?>"
                                required>
                    </div>

                    <div class="form-input-group1">
                        <label>Người lập</label>
                        <input type="text" name="nguoiLap" readonly value="<?= htmlspecialchars($tenNguoiLap) ?>">
                    </div>

                    <div class="form-input-group1">
                        <label>Ngày lập</label>
                        <input type="text" readonly value="<?= date('d/m/Y') ?>">
                    </div>
                </div>

                <h4 class="materials-subtitle">Nguyên vật liệu xuất kho</h4>

                <table class="data-table list-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên NVL</th>
                            <th>SL yêu cầu</th>
                            <th>SL xuất</th>
                            <th>Xưởng nhận</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; foreach ($dsNVL as $nvl): ?>
                        <?php
                            $tenNVL = htmlspecialchars($nvl['tenNVL'] ?? 'Không xác định');
                            $maNVL = intval($nvl['maNVL'] ?? 0);
                            $soYC = intval($nvl['soLuongYeuCau'] ?? 0);
                        ?>
                        <tr>
                            <td style="text-align:center;"><?= $i++ ?></td>

                            <td style="text-align:left;">
                                <?= $tenNVL ?>
                                <input type="hidden" name="maNVL[]" value="<?= $maNVL ?>">
                                <input type="hidden" name="tenNVL[]" value="<?= $tenNVL ?>">
                            </td>

                            <td style="text-align:center;">
                                <input type="number" readonly class="input-small" value="<?= $soYC ?>">
                                <input type="hidden" name="soLuongYeuCau[]" value="<?= $soYC ?>">
                            </td>

                            <td style="text-align:center;">
                                <input type="number" name="soLuongNhap[]" class="input-small"
                                       min="1" max="<?= $soYC ?>" value="<?= $soYC ?>" required>
                            </td>

                            <td style="text-align:center;">
                                <?php
                                    // lấy xưởng tương ứng với maNVL của dòng hiện tại
                                    $xuongRow = $mapXuongTheoNVL[$maNVL] ?? null;
                                ?>
                                <?php if (!empty($xuongRow)) : ?>
                                    <?= htmlspecialchars($xuongRow['tenXuong']) ?>
                                    <input type="hidden" name="xuongNhan[]" value="<?= htmlspecialchars($xuongRow['maXuong']) ?>">
                                <?php else: ?>
                                    <span style="color:red;">Không tìm thấy xưởng</span>
                                    <input type="hidden" name="xuongNhan[]" value="">
                                <?php endif; ?>
                            </td>


                            <td style="text-align:center;">
                                <input type="text" name="ghiChu[]" class="input-normal" placeholder="Ghi chú...">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="footer-box-buttons">
                    <button type="submit" class="btn-submit">Lập phiếu</button>
                    <a href="index.php?page=xuat-kho-nvl" class="btn-cancel">Thoát</a>
                </div>

            </form>

        </div>

    </main>
</div>

<!-- ✅ Modal xác nhận -->
<div class="modal-overlay" id="confirm-modal"
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.5);justify-content:center;align-items:center;z-index:1000;">
    <div class="modal-content"
         style="background:#fff;padding:25px 35px;border-radius:8px;text-align:center;
                box-shadow:0 4px 15px rgba(0,0,0,0.2);min-width:300px;">
        <p style="font-size:1.1em;font-weight:bold;margin-bottom:25px;">
            Xác nhận lập phiếu xuất kho?
        </p>
        <div style="display:flex;gap:20px;justify-content:center;">
            <button id="btn-confirm-yes"
                    style="background:#28a745;border:none;padding:8px 20px;color:white;
                           border-radius:5px;cursor:pointer;font-weight:bold;">
                Xác nhận
            </button>
            <button id="btn-confirm-no"
                    style="background:#DC3545;border:none;padding:8px 20px;color:white;
                           border-radius:5px;cursor:pointer;font-weight:bold;">
                Hủy
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-xuat-kho-nvl");
    const confirmModal = document.getElementById("confirm-modal");
    const btnYes = document.getElementById("btn-confirm-yes");
    const btnNo = document.getElementById("btn-confirm-no");

    // ✅ Bắt sự kiện submit và hiển thị modal xác nhận
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        confirmModal.style.display = "flex";
    });

    btnYes.addEventListener("click", () => {
        confirmModal.style.display = "none";
        form.submit(); // ✅ Submit thật
    });

    btnNo.addEventListener("click", () => {
        confirmModal.style.display = "none";
    });

    confirmModal.addEventListener("click", (e) => {
        if (e.target === confirmModal) confirmModal.style.display = "none";
    });
});
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
