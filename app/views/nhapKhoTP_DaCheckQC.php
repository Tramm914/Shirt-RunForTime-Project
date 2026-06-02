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
        </style>
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
            <h2 class="main-title" style="text-align: center; font-size: 1.5em; margin-bottom: 30px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
                NHẬP KHO THÀNH PHẨM - PHIẾU KIỂM TRA CHẤT LƯỢNG ĐÃ ĐẠT
            </h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Lỗi!</strong> <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Đã xảy ra lỗi.' ?>
                    <?php if (strpos($_GET['msg'] ?? '', 'kết nối') !== false): ?>
                        <br><small>Vui lòng kiểm tra kết nối và thử lại.</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    <strong>Thành công!</strong> <?= isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Nhập kho thành công' ?>
                </div>
            <?php endif; ?>

            <?php if (isset($thongBaoLoi) && !empty($thongBaoLoi)): ?>
                <div class="alert alert-warning" style="background-color: #fff3cd; color: #856404; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeaa7;">
                    <strong>Thông báo!</strong> <?= htmlspecialchars($thongBaoLoi) ?>
                </div>
            <?php endif; ?>

            <!-- Danh Sách Phiếu Kiểm Tra Chất Lượng Đã Đạt -->
            <div class="card" style="background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 30px;">
                <h3 style="color: #085da7; margin-bottom: 20px; font-size: 1.2em; border-bottom: 2px solid #085da7; padding-bottom: 10px;">
                    Danh Sách Phiếu Kiểm Tra Chất Lượng Đã Đạt
                </h3>

                <div class="table-container" style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Mã Phiếu</th>
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Sản Phẩm</th>
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Số Lượng</th>
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Người Lập</th>
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Ngày Kiểm Tra</th>
                                <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center; font-weight: 600;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($danhSachPhieuQC)): ?>
                                <?php foreach ($danhSachPhieuQC as $phieu): ?>
                                    <tr style="cursor: pointer;" 
                                        onclick="selectPhieu(<?= $phieu['maPhieu'] ?>)" 
                                        class="<?= (isset($_GET['maPhieu']) && $_GET['maPhieu'] == $phieu['maPhieu']) ? 'selected-row' : '' ?>"
                                        style="<?= (isset($_GET['maPhieu']) && $_GET['maPhieu'] == $phieu['maPhieu']) ? 'background-color: #e7f3ff;' : '' ?>">
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <?= htmlspecialchars($phieu['maPhieu']) ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <?= htmlspecialchars($phieu['sanPham']) ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <?= number_format($phieu['soLuong']) ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <?= htmlspecialchars($phieu['tenNguoiLap']) ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <?= isset($phieu['ngayKiemTra']) && !empty($phieu['ngayKiemTra']) ? date('d/m/Y', strtotime($phieu['ngayKiemTra'])) : date('d/m/Y') ?>
                                        </td>
                                        <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                            <button 
                                                onclick="event.stopPropagation(); selectPhieu(<?= $phieu['maPhieu'] ?>)"
                                                style="padding: 6px 15px; background-color: #085da7; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9em;"
                                                onmouseover="this.style.backgroundColor='#064a85'"
                                                onmouseout="this.style.backgroundColor='#085da7'">
                                                Chọn
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="border: 1px solid #dee2e6; padding: 15px; text-align: center; color: #6c757d;">
                                        Không có phiếu hợp lệ để nhập kho.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chi Tiết Phiếu -->
            <?php if (!empty($chiTietPhieu)): ?>
                <div class="card" style="background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px;">
                    <h3 style="color: #085da7; margin-bottom: 20px; font-size: 1.2em; border-bottom: 2px solid #085da7; padding-bottom: 10px;">
                        Chi Tiết Phiếu
                    </h3>

                    <form method="POST" action="index.php?page=luu-nhap-kho-tp-da-check-qc" id="formNhapKho">
                        <input type="hidden" name="maPhieu" value="<?= htmlspecialchars($chiTietPhieu['maPhieu']) ?>">
                        <input type="hidden" name="maSanPham" value="<?= htmlspecialchars($chiTietPhieu['maSanPham']) ?>">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                            <div class="form-group">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                                    Mã Phiếu:
                                </label>
                                <div style="padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 1em;">
                                    <?= htmlspecialchars($chiTietPhieu['maPhieu']) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                                    Sản Phẩm:
                                </label>
                                <div style="padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 1em;">
                                    <?= htmlspecialchars($chiTietPhieu['sanPham']) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="soLuong" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                                    Số Lượng: *
                                </label>
                                <input 
                                    type="number" 
                                    id="soLuong" 
                                    name="soLuong" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($chiTietPhieu['soLuong']) ?>"
                                    min="1"
                                    max="<?= htmlspecialchars($chiTietPhieu['soLuong']) ?>"
                                    required
                                    style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; font-size: 1em; transition: border-color 0.3s;"
                                    onfocus="this.style.borderColor='#085da7'"
                                    onblur="this.style.borderColor='#ddd'"
                                >
                                <small style="color: #6c757d; font-size: 0.9em;">
                                    (Tối đa: <?= number_format($chiTietPhieu['soLuong']) ?>)
                                </small>
                            </div>

                            <div class="form-group">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                                    Ngày Nhập Kho:
                                </label>
                                <div style="padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 1em;">
                                    <?= date('d/m/Y') ?>
                                </div>
                            </div>
                        </div>

                        <div class="button-group" style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                            <button 
                                type="button" 
                                class="btn btn-secondary" 
                                onclick="window.location.href='index.php?page=nhap-kho-thanh-pham'"
                                style="padding: 12px 30px; background-color: #6c757d; color: white; border: none; border-radius: 5px; font-size: 1em; cursor: pointer; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#5a6268'"
                                onmouseout="this.style.backgroundColor='#6c757d'">
                                Hủy
                            </button>
                            <button 
                                type="submit" 
                                class="btn btn-primary" 
                                id="btnLuuNhapKho"
                                style="padding: 12px 30px; background-color: #085da7; color: white; border: none; border-radius: 5px; font-size: 1em; font-weight: 600; cursor: pointer; transition: background-color 0.3s;"
                                onmouseover="this.style.backgroundColor='#064a85'"
                                onmouseout="this.style.backgroundColor='#085da7'">
                                Lưu Nhập Kho
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="card" style="background-color: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px; text-align: center; color: #6c757d;">
                    <p>Vui lòng chọn một phiếu từ danh sách để xem chi tiết và nhập kho.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>

<script>
function selectPhieu(maPhieu) {
    window.location.href = 'index.php?page=nhap-kho-thanh-pham&maPhieu=' + maPhieu;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formNhapKho');
    if (form) {
        form.addEventListener('submit', function(e) {
            const soLuong = document.getElementById('soLuong').value;
            const soLuongMax = document.getElementById('soLuong').max;

            if (!soLuong || parseInt(soLuong) <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập số lượng hợp lệ!');
                return false;
            }

            if (parseInt(soLuong) > parseInt(soLuongMax)) {
                e.preventDefault();
                alert('Số lượng không được vượt quá ' + parseInt(soLuongMax) + '!');
                return false;
            }

            if (!confirm('Bạn có chắc chắn muốn nhập kho thành phẩm này không?')) {
                e.preventDefault();
                return false;
            }

            // Disable button để tránh double submit
            const btn = document.getElementById('btnLuuNhapKho');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Đang xử lý...';
            }

            return true;
        });
    }
});
</script>

<style>
.selected-row {
    background-color: #e7f3ff !important;
    font-weight: 600;
}

.table-container table tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #085da7 !important;
    box-shadow: 0 0 0 0.2rem rgba(8, 93, 167, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px !important;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .button-group button {
        width: 100%;
    }

    .form-group {
        grid-column: 1 / -1;
    }
}
</style>

