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

        <h2 class="main-title" style="text-align:center; font-size:1.4em; margin-bottom:20px; color:#fff; text-shadow:0 2px 8px rgba(0,0,0,0.5); font-weight:700;">
            PHÊ DUYỆT CÁC YÊU CẦU
        </h2>

        <section class="request-detail" style="background:#fff; border:1px solid #dee2e6; border-radius:8px; padding:25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h3 class="section-title" style="text-align:center; background:#e9f3ff; color:#0d47a1; padding:10px; border-radius:6px; font-weight:600;">
                CHI TIẾT PHIẾU YÊU CẦU
            </h3>

            <table class="data-table detail-table" style="width:100%; border-collapse:collapse; margin-top:20px;">
                <tbody>
                    <tr>
                        <th style="width:200px; background:#f8f9fa; padding:10px;">Tên phiếu</th>
                        <td style="padding:10px;"><?= htmlspecialchars($chiTiet[0]['tenPhieu'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th style="background:#f8f9fa; padding:10px;">Loại phiếu</th>
                        <td style="padding:10px;">
                            <?php
                                $typeText = [
                                    'capnvl' => 'Cung cấp nguyên vật liệu',
                                    'nhapkho' => 'Nhập kho nguyên vật liệu',
                                    'kiemtra' => 'Kiểm tra chất lượng'
                                ];
                                echo htmlspecialchars($typeText[$_GET['type']] ?? '-');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="background:#f8f9fa; padding:10px;">Người lập</th>
                        <td style="padding:10px;"><?= htmlspecialchars($chiTiet[0]['tenNguoiLap'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th style="background:#f8f9fa; padding:10px;">Ngày lập</th>
                        <td style="padding:10px;">
                            <?= !empty($chiTiet[0]['ngayLap']) ? date('d/m/Y', strtotime($chiTiet[0]['ngayLap'])) : '-' ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 style="margin-top:30px; font-weight:bold; font-size:1.1em;">Nội dung phiếu</h4>
            <table class="data-table list-table" style="width:100%; border-collapse:collapse; border:1px solid #dee2e6; margin-top:10px;">
                <thead>
                    <tr style="background:#f8f9fa;">
                        <th style="padding:10px; border:1px solid #dee2e6;">STT</th>
                        <th style="padding:10px; border:1px solid #dee2e6;">Tên vật liệu / sản phẩm</th>
                        <th style="padding:10px; border:1px solid #dee2e6;">Số lượng</th>
                        <th style="padding:10px; border:1px solid #dee2e6;">Đơn vị tính</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chiTiet)): ?>
                        <?php $i=1; foreach ($chiTiet as $ct): ?>
                            <tr>
                                <td style="padding:10px; text-align:center; border:1px solid #dee2e6;"><?= $i++ ?></td>
                                <td style="padding:10px; border:1px solid #dee2e6;">
                                    <?= htmlspecialchars($ct['tenNVL'] ?? $ct['tenSanPham'] ?? '') ?>
                                </td>
                                <td style="padding:10px; text-align:center; border:1px solid #dee2e6;">
                                    <?= htmlspecialchars($ct['soLuong'] ?? '-') ?>
                                </td>
                                <td style="padding:10px; text-align:center; border:1px solid #dee2e6;">
                                    <?= htmlspecialchars($ct['donViTinh'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:15px; color:#6c757d;">Không có chi tiết nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="footer-box-buttons" style="border-top:1px solid #dee2e6; margin-top:30px; padding-top:20px; display:flex; justify-content:center; gap:20px;">
                <form method="POST" action="index.php?page=duyet-phieu" id="form-duyet" style="display:inline;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($chiTiet[0]['maYCCC'] ?? $chiTiet[0]['maYCNK'] ?? $chiTiet[0]['maYC'] ?? '') ?>">
                    <input type="hidden" name="loai" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">
                    <button type="button" class="btn-approve" id="btnDuyet" style="border:none; padding:10px 25px; font-size:1em; font-weight:bold; color:white; border-radius:5px; cursor:pointer; background-color:#28A745;">Phê duyệt</button>
                </form>

                <form method="POST" action="index.php?page=tuchoi-phieu" id="form-tuchoi" style="display:inline;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($chiTiet[0]['maYCCC'] ?? $chiTiet[0]['maYCNK'] ?? $chiTiet[0]['maYC'] ?? '') ?>">
                    <input type="hidden" name="loai" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">
                    <button type="button" class="btn-reject" id="btnTuChoi" style="border:none; padding:10px 25px; font-size:1em; font-weight:bold; color:white; border-radius:5px; cursor:pointer; background-color:#DC3545;">Từ chối</button>
                </form>

                <a href="index.php?page=phe-duyet-cac-yeu-cau&type=<?= urlencode($_GET['type'] ?? '') ?>" 
                   class="btn-exit" 
                   style="display:inline-block; text-decoration:none; border:none; padding:10px 25px; font-size:1em; font-weight:bold; color:white; border-radius:5px; background-color:#6c757d;">
                    Thoát
                </a>
            </div>
        </section>
    </main>
</div>

<!-- ===== MODAL XÁC NHẬN CHUNG ===== -->
<div id="modalConfirm" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;">
    <div style="background:white; padding:25px 35px; border-radius:8px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:380px;">
        <p id="modalMessage" style="font-size:1.1em; font-weight:600; margin-bottom:20px;">Bạn có chắc chắn muốn phê duyệt?</p>

        <!-- Khung nhập lý do chỉ hiện khi từ chối -->
        <textarea id="reasonInput" rows="4" placeholder="Nhập lý do từ chối..." 
                  style="display:none; width:100%; padding:8px; border:1px solid #ced4da; border-radius:5px; margin-bottom:20px;"></textarea>

        <div style="display:flex; justify-content:center; gap:15px;">
            <button id="btnYes" style="background-color:#007bff; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">Xác nhận</button>
            <button id="btnNo" style="background-color:#dc3545; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">Hủy</button>
        </div>
    </div>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalConfirm');
    const msg = document.getElementById('modalMessage');
    const yes = document.getElementById('btnYes');
    const no = document.getElementById('btnNo');
    const reason = document.getElementById('reasonInput');

    const formDuyet = document.getElementById('form-duyet');
    const formTuChoi = document.getElementById('form-tuchoi');

    const btnDuyet = document.getElementById('btnDuyet');
    const btnTuChoi = document.getElementById('btnTuChoi');

    let currentAction = null;

    function openModal(message, showReason, callback) {
        msg.textContent = message;
        reason.style.display = showReason ? 'block' : 'none';
        reason.value = '';
        modal.style.display = 'flex';

        yes.onclick = () => {
            if (showReason && reason.value.trim() === '') {
                alert('Vui lòng nhập lý do từ chối!');
                return;
            }
            modal.style.display = 'none';
            callback(true, reason.value.trim());
        };
        no.onclick = () => { modal.style.display = 'none'; callback(false, null); };
    }

    btnDuyet.addEventListener('click', () => {
        currentAction = 'approve';
        openModal('Xác nhận phê duyệt phiếu này?', false, (ok) => {
            if (ok) formDuyet.submit();
        });
    });

    btnTuChoi.addEventListener('click', () => {
        currentAction = 'reject';
        openModal('Vui lòng nhập lý do từ chối phiếu này:', true, (ok, reasonText) => {
            if (ok) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'lyDo';
                input.value = reasonText;
                formTuChoi.appendChild(input);
                formTuChoi.submit();
            }
        });
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>
