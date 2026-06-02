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

        <h2 class="main-title" style="text-align:center; font-size: 1.3em; margin-bottom: 20px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
            TẠO YÊU CẦU CUNG CẤP NGUYÊN VẬT LIỆU
        </h2>

        <section class="request-list">
            <h3 class="section-title" style="text-align:center; background: #9bc3ebff; color:#495057; padding: 10px 15px; border-radius: 5px; font-weight: 600;">
                DANH SÁCH KẾ HOẠCH SẢN XUẤT
            </h3>

            <table class="data-table list-table" style="width:100%; border-collapse: collapse; margin-top: 15px; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">STT</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Tên kế hoạch</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Bắt đầu</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Kết thúc</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Người tạo</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kiểm tra xem biến có tồn tại, là mảng và có dữ liệu không
                    if (isset($danhSachKHSX) && is_array($danhSachKHSX) && count($danhSachKHSX) > 0):
                        $stt = 1;
                        foreach ($danhSachKHSX as $khsx) :
                    ?>
                            <tr style="text-align: center;">
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><?php echo $stt++; ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;"><?php echo htmlspecialchars($khsx['tenKHSX']); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;"><?php echo date('d/m/Y', strtotime($khsx['thoiGianBatDau'])); ?></td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;">
                                    <?php echo isset($khsx['thoiGianKetThuc']) ? date('d/m/Y', strtotime($khsx['thoiGianKetThuc'])) : 'N/A'; ?>
                                </td>
                                <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">
                                    <?php echo isset($khsx['tenNguoiTao']) ? htmlspecialchars($khsx['tenNguoiTao']) : 'N/A'; ?>
                                </td>
                                <td style="border: 1px solid #dee2e6; padding: 10px;">
                                    <a href="index.php?page=chi-tiet-yeu-cau&maKHSX=<?php echo $khsx['maKHSX']; ?>"
                                       class="btn-details"
                                       style="display: inline-block; text-decoration: none; background-color: #007bff; color: white; padding: 8px 12px; border-radius: 5px; font-size: 0.9em;">
                                        ➜ Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    // Xử lý trường hợp không có dữ liệu để hiển thị
                    else:
                    ?>
                        <tr>
                            <td colspan="6" style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">Không có kế hoạch sản xuất nào cần lập yêu cầu NVL.</td>
                        </tr>
                    <?php endif; ?>
                     </tbody>
            </table>
        </section>

    </main> </div> <div class="modal-overlay" id="success-create-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div class="modal-content" style="background-color: white; padding: 30px 40px; border-radius: 8px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); min-width: 300px;">
        <p style="font-size: 1.1em; font-weight: bold; color: #085da7ff; margin-bottom: 25px;">LẬP PHIẾU YÊU CẦU CUNG CẤP NGUYÊN VẬT LIỆU THÀNH CÔNG</p>
        <div class="modal-buttons">
             <button class="btn btn-close-modal" id="btn-success-create-close" style="background-color: #f0f0f0; color: #333; border: 1px solid #ccc; padding: 8px 20px; border-radius: 5px; cursor: pointer;">X | Đóng</button>
        </div>
    </div>
</div>

<?php
// Tải Footer (giữ nguyên)
require_once 'app/views/layouts/footer.php';
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const successCreateModal = document.getElementById('success-create-modal');
    const btnSuccessCreateClose = document.getElementById('btn-success-create-close');
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success') && urlParams.get('success') === '1') {
        if (successCreateModal) {
             setTimeout(() => {
                 successCreateModal.style.display = 'flex';
                 const newUrl = window.location.pathname + window.location.search.replace(/&?success=1/, '').replace(/\?$/, '');
                 window.history.replaceState({ path: newUrl }, '', newUrl);
             }, 100);
        } else {
            console.error("Không tìm thấy HTML #success-create-modal trên trang danh sách.");
        }
    }

    if (successCreateModal && btnSuccessCreateClose) {
        btnSuccessCreateClose.addEventListener('click', () => {
            successCreateModal.style.display = 'none';
        });
        successCreateModal.addEventListener('click', (e) => {
            if (e.target === successCreateModal) {
                 successCreateModal.style.display = 'none';
            }
        });
    }
});
</script>