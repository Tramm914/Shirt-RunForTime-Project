<?php
// --- START: ADD LAYOUT INCLUDES ---
// Đường dẫn tính từ file index.php ở gốc
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
// --- END: ADD LAYOUT INCLUDES ---
?>

<div class="main-layout-wrapper">

    <?php
    // --- START: ADD SIDEBAR INCLUDE ---
    require_once 'app/views/layouts/sidebar.php';
    // --- END: ADD SIDEBAR INCLUDE ---
    ?>

    <main class="main-content">

        <style>
            .main-content {
                background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
        </style>

        <h2 class="main-title"
            style="text-align:center; font-size: 1.3em; margin-bottom: 20px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
            TẠO YÊU CẦU CUNG CẤP NGUYÊN VẬT LIỆU
        </h2>
        <h3 class="section-title"
            style="text-align:center; background: #9bc3ebff; color:#495057; padding: 10px 15px; border-radius: 5px; font-weight: 600;">
            PHIẾU YÊU CẦU CUNG CẤP NGUYÊN VẬT LIỆU
        </h3>

        <form id="form-tao-yeu-cau" method="POST" action="index.php?page=luu-yeu-cau-nvl">

            <input type="hidden" name="maKHSX" value="<?php echo isset($maKHSX) ? htmlspecialchars($maKHSX) : ''; ?>">

            <div class="form-section"
                style="margin-bottom: 20px; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                <div class="form-input-group" style="display: flex; align-items: center; margin-bottom: 15px;">
                    <label for="ten-phieu"
                        style="width: 100px; text-align: right; margin-right: 10px; font-weight: bold;">Tên
                        phiếu</label>
                    <input type="text" id="ten-phieu" name="tenPhieu"
                        value="Yêu cầu NVL cho <?php echo isset($thongTinPhieu['tenKHSX']) ? htmlspecialchars($thongTinPhieu['tenKHSX']) : 'KHSX'; ?>"
                        style="flex-grow: 1; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>
                <div class="form-input-group" style="display: flex; align-items: center; margin-bottom: 15px;">
                    <label for="nguoi-lap"
                        style="width: 100px; text-align: right; margin-right: 10px; font-weight: bold;">Người
                        lập</label>
                    <input type="text" id="nguoi-lap" name="nguoiLap"
                        value="<?php echo isset($_SESSION['user']['hoTen']) ? htmlspecialchars($_SESSION['user']['hoTen']) : 'N/A'; ?>"
                        readonly
                        style="flex-grow: 1; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; background-color: #e9ecef;">
                </div>
                <div class="form-input-group" style="display: flex; align-items: center;">
                    <label for="ngay-lap"
                        style="width: 100px; text-align: right; margin-right: 10px; font-weight: bold;">Ngày lập</label>
                    <input type="text" id="ngay-lap" name="ngayLap" value="<?php echo date('d/m/Y'); ?>" readonly
                        style="flex-grow: 1; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; background-color: #e9ecef;">
                </div>
            </div>

            <div class="materials-section">
                <h4 class="materials-subtitle"
                    style="text-align:center;font-weight: bold; font-size: 1.1em; margin-top: 30px; margin-bottom: 15px; color:#ffff">
                    THÔNG TIN CÁC NGUYÊN VẬT LIỆU</h4>
                <table class="data-table list-table"
                    style="width:100%; border-collapse: collapse; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">STT</th>
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Tên NVL</th>
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Loại NVL</th>
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Số lượng</th>
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Đơn vị tính</th>
                            <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Kiểm tra biến $danhSachNVL
                        if (isset($danhSachNVL) && is_array($danhSachNVL) && count($danhSachNVL) > 0):
                            $stt = 1;
                            foreach ($danhSachNVL as $nvl):
                                ?>
                                <tr>
                                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                        <?php echo $stt++; ?></td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">
                                        <input type="hidden" name="maNVL[]"
                                            value="<?php echo isset($nvl['maNVL']) ? $nvl['maNVL'] : ''; ?>">
                                        <input type="text" name="tenNVL[]"
                                            value="<?php echo isset($nvl['tenNVL']) ? htmlspecialchars($nvl['tenNVL']) : ''; ?>"
                                            readonly style="border:none; background:transparent; width: 100%;">
                                    </td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">
                                        <?php echo isset($nvl['loaiNVL']) ? htmlspecialchars($nvl['loaiNVL']) : 'N/A'; ?>
                                    </td>

                                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                        <input type="number" name="soLuong[]"
                                            value="<?php echo isset($nvl['soLuongNVL']) ? $nvl['soLuongNVL'] : 0; ?>" readonly
                                            style="width: 80px; text-align:center; padding: 5px; border: 1px solid #ced4da; border-radius: 4px; background-color: #e9ecef; color: #6c757d;">
                                    </td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                        <?php echo isset($nvl['donViTinh']) ? htmlspecialchars($nvl['donViTinh']) : 'N/A'; ?>
                                        <input type="hidden" name="donViTinh[]"
                                            value="<?php echo isset($nvl['donViTinh']) ? htmlspecialchars($nvl['donViTinh']) : ''; ?>">
                                    </td>
                                    <td style="border: 1px solid #dee2e6; padding: 10px;">
                                        <input type="text" name="ghiChu[]" placeholder="Ghi chú..."
                                            style="width: 100%; padding: 5px; border: 1px solid #ced4da; border-radius: 4px;">
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            // Xử lý trường hợp không có NVL hoặc lỗi
                        else:
                            ?>
                            <tr>
                                <td colspan="6"
                                    style="border: 1px solid #dee2e6; padding: 10px; text-align: center; color: #6c757d;">
                                    <?php echo isset($danhSachNVL) ? 'Không có nguyên vật liệu nào cho kế hoạch này.' : 'Lỗi: Dữ liệu nguyên vật liệu không có sẵn.'; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="footer-box-buttons"
                style="border: 1px solid #dee2e6; background-color: #f8f9fa; padding: 20px; margin-top: 30px; display: flex; justify-content: center; gap: 20px; border-radius: 5px;">
                <button type="submit" class="btn btn-approve"
                    style="border: none; padding: 10px 25px; font-size: 1em; font-weight: bold; color: white; border-radius: 5px; cursor: pointer; background-color: #28A745;">Lập
                    phiếu</button>
                <a href="index.php?page=tao-yeu-cau-nvl" class="btn btn-reject"
                    style="display: inline-block; border: none; padding: 10px 25px; font-size: 1em; font-weight: bold; color: white; border-radius: 5px; cursor: pointer; background-color: #DC3545; text-decoration: none;">Thoát</a>
            </div>
        </form>
    </main>
</div>
<div class="modal-overlay" id="confirm-create-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div class="modal-content"
        style="background-color: white; padding: 30px 40px; border-radius: 8px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); min-width: 300px;">
        <p style="font-size: 1.1em; font-weight: bold; color: #333; margin-bottom: 25px;">Xác nhận lập phiếu yêu cầu?
        </p>
        <div class="modal-buttons" style="display: flex; justify-content: center; gap: 20px;">
            <button class="btn btn-confirm" id="btn-create-yes"
                style="border: none; padding: 10px 25px; font-size: 1em; font-weight: bold; color: white; border-radius: 5px; cursor: pointer; background-color: #007bff;">Xác
                nhận</button>
            <button class="btn btn-cancel" id="btn-create-no"
                style="border: none; padding: 10px 25px; font-size: 1em; font-weight: bold; color: white; border-radius: 5px; cursor: pointer; background-color: #DC3545;">Hủy</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="success-create-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div class="modal-content"
        style="background-color: white; padding: 30px 40px; border-radius: 8px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); min-width: 300px;">
        <p style="font-size: 1.1em; font-weight: bold; color: #28a745; margin-bottom: 25px;">LẬP PHIẾU THÀNH CÔNG!</p>
        <div class="modal-buttons">
            <button class="btn btn-close-modal" id="btn-success-create-close"
                style="background-color: #f0f0f0; color: #333; border: 1px solid #ccc; padding: 8px 20px; border-radius: 5px; cursor: pointer;">Đóng</button>
        </div>
    </div>
</div>

<?php
// Tải Footer (giữ nguyên)
require_once 'app/views/layouts/footer.php';
?>

<script>
    // --- JavaScript xử lý modal ---
    const formTaoYeuCau = document.getElementById('form-tao-yeu-cau');
    // Modal elements
    const confirmCreateModal = document.getElementById('confirm-create-modal');
    const btnCreateYes = document.getElementById('btn-create-yes');
    const btnCreateNo = document.getElementById('btn-create-no');
    const successCreateModal = document.getElementById('success-create-modal');
    const btnSuccessCreateClose = document.getElementById('btn-success-create-close');

    // Bắt sự kiện submit của form
    if (formTaoYeuCau) {
        formTaoYeuCau.addEventListener('submit', (e) => {
            e.preventDefault(); // Ngăn submit ngay
            if (confirmCreateModal) {
                confirmCreateModal.style.display = 'flex'; // Hiện modal xác nhận
            } else {
                console.error("Lỗi: Không tìm thấy HTML của #confirm-create-modal");
                alert("Đã xảy ra lỗi khi hiển thị xác nhận.");
            }
        });
    } else {
        console.error("Lỗi: Không tìm thấy HTML của #form-tao-yeu-cau");
    }

    // Xử lý nút trong modal xác nhận
    if (confirmCreateModal) {
        // Nút Hủy
        if (btnCreateNo) {
            btnCreateNo.addEventListener('click', () => {
                confirmCreateModal.style.display = 'none';
            });
        }
        // Nút Xác nhận
        if (btnCreateYes) {
            btnCreateYes.addEventListener('click', () => {
                if (formTaoYeuCau) {
                    confirmCreateModal.style.display = 'none'; // Ẩn modal trước
                    formTaoYeuCau.submit(); // Gửi form đi
                }
            });
        }
        // Đóng khi click ra ngoài
        confirmCreateModal.addEventListener('click', (e) => {
            if (e.target === confirmCreateModal) {
                confirmCreateModal.style.display = 'none';
            }
        });
    }

    // Hiển thị modal thành công NẾU có tham số ?success=1 trên URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === '1') {
        if (successCreateModal) {
            // Đợi một chút để trang tải xong hẳn rồi mới hiện modal
            setTimeout(() => {
                successCreateModal.style.display = 'flex';
                // Xóa tham số khỏi URL
                const newUrl = window.location.pathname + window.location.search.replace(/&?success=1/, '').replace(/\?$/, '');
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }, 100); // 100ms delay
        }
    }
    // Hiển thị thông báo lỗi NẾU có tham số ?error=1 trên URL
    else if (urlParams.has('error') && urlParams.get('error') === '1') {
        alert("Đã xảy ra lỗi khi lưu phiếu. Vui lòng thử lại.");
        // Xóa tham số khỏi URL
        const newUrl = window.location.pathname + window.location.search.replace(/&?error=1/, '').replace(/\?$/, '');
        window.history.replaceState({ path: newUrl }, '', newUrl);
    }


    // Xử lý nút Đóng trong modal thành công
    if (successCreateModal && btnSuccessCreateClose) {
        btnSuccessCreateClose.addEventListener('click', () => {
            successCreateModal.style.display = 'none'; // Chỉ ẩn modal
        });
        // Đóng khi click ra ngoài
        successCreateModal.addEventListener('click', (e) => {
            if (e.target === successCreateModal) {
                successCreateModal.style.display = 'none';
            }
        });
    }
</script>