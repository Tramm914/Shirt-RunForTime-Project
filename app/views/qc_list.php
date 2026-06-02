<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>

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
        <h2 class="main-title" style="text-align:center; color:#fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">KIỂM TRA CHẤT LƯỢNG (QC)</h2>

        <div class="table-container" style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom:15px; color:#0056b3;">Danh sách phiếu chờ kiểm tra</h3>
    
            <table class="data-table list-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9;">
                        <th style="padding:10px; border:1px solid #e2e8f0;">Mã QC</th>
                        <th style="padding:10px; border:1px solid #e2e8f0;text-align:center;">Sản phẩm</th>
                        <th style="padding:10px; border:1px solid #e2e8f0;">Tổng SL</th>
                        <th style="padding:10px; border:1px solid #e2e8f0;">Người lập</th>
                        <th style="padding:10px; border:1px solid #e2e8f0;text-align:center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($danhSachQC)): ?>
                        <?php foreach ($danhSachQC as $qc): ?>
                            <tr>
                                <td style="text-align:center; padding:10px; border:1px solid #e2e8f0;">
                                    <?= htmlspecialchars($qc['maYC']) ?>
                                </td>
                                <td style="padding:10px; border:1px solid #e2e8f0;">
                                    <?= htmlspecialchars($qc['tenSanPham']) ?>
                                </td>
                                <td style="text-align:center; font-weight:bold; padding:10px; border:1px solid #e2e8f0;">
                                    <?= number_format($qc['tongSoLuong']) ?>
                                </td>
                                <td style="text-align:center; padding:10px; border:1px solid #e2e8f0;">
                                    <?= htmlspecialchars($qc['tenNguoiLap']) ?>
                                </td>
                                <td style="text-align:center; padding:10px; border:1px solid #e2e8f0;">
                                    <button class="btn-details"
                                        onclick="openQCModal(<?= $qc['maYC'] ?>, '<?= htmlspecialchars($qc['tenSanPham']) ?>', <?= $qc['tongSoLuong'] ?>)"
                                        style="background:#28a745; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">
                                        <i class="fa fa-check-square"></i> Kiểm tra
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#666;">
                                Không có phiếu nào cần kiểm tra.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<div id="qcModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
    <div class="modal-content" style="background:white; padding:25px; border-radius:8px; width:400px; max-width:90%;">
        <h3 style="margin-top:0; color:#0d1a44; border-bottom:1px solid #eee; padding-bottom:10px;">Cập nhật kết quả QC</h3>

        <form action="index.php?page=qc-update" method="POST" id="formQC">
            <input type="hidden" name="maYC" id="modalMaYC">
            <input type="hidden" name="tongSoLuong" id="modalTongSoLuong">

            <div style="margin-bottom:15px;">
                <label style="font-weight:bold; display:block; margin-bottom:5px;">Sản phẩm:</label>
                <input type="text" id="modalTenSP" readonly style="width:100%; background:#f9f9f9; border:1px solid #ccc; padding:8px; border-radius:4px;">
            </div>

            <div style="margin-bottom:15px; display:flex; gap:10px;">
                <div style="flex:1;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px; color:#28a745;">SL Đạt:</label>
                    <input type="number" name="soLuongDat" id="slDat" required min="0"
                        style="width:100%; padding:8px; border:1px solid #28a745; border-radius:4px;">
                </div>
                <div style="flex:1;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px; color:#dc3545;">SL Hỏng:</label>
                    <input type="number" name="soLuongHong" id="slHong" required min="0"
                        style="width:100%; padding:8px; border:1px solid #dc3545; border-radius:4px;">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="font-weight:bold; display:block; margin-bottom:5px;">Ghi chú:</label>
                <textarea name="ghiChu" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
            </div>

            <div style="text-align:right; border-top:1px solid #eee; padding-top:15px;">
                <button type="button" onclick="document.getElementById('qcModal').style.display='none'"
                    style="background:#6c757d; color:white; border:none; padding:8px 15px; border-radius:4px; cursor:pointer; margin-right:5px;">Hủy</button>
                <button type="submit"
                    style="background:#007bff; color:white; border:none; padding:8px 15px; border-radius:4px; cursor:pointer;">Lưu kết quả</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openQCModal(maYC, tenSP, tongSL) {
        document.getElementById('modalMaYC').value = maYC;
        document.getElementById('modalTenSP').value = tenSP;
        document.getElementById('modalTongSoLuong').value = tongSL;

        // Reset giá trị mặc định
        document.getElementById('slDat').value = tongSL;
        document.getElementById('slHong').value = 0;

        document.getElementById('qcModal').style.display = 'flex';
    }

    const slDat = document.getElementById('slDat');
    const slHong = document.getElementById('slHong');
    const tongSL = document.getElementById('modalTongSoLuong');

    slHong.addEventListener('input', function() {
        const total = parseInt(tongSL.value) || 0;
        const hong = parseInt(this.value) || 0;
        if (hong <= total) {
            slDat.value = total - hong;
        } else {
            alert('Số lượng hỏng không thể lớn hơn tổng số lượng!');
            this.value = 0;
            slDat.value = total;
        }
    });

    slDat.addEventListener('input', function() {
        const total = parseInt(tongSL.value) || 0;
        const dat = parseInt(this.value) || 0;
        if (dat <= total) {
            slHong.value = total - dat;
        } else {
            alert('Số lượng đạt không thể lớn hơn tổng số lượng!');
            this.value = total;
            slHong.value = 0;
        }
    });

    window.onclick = function(event) {
        const modal = document.getElementById('qcModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>