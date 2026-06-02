<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>

<style>
/* ===============================
   LIST TABLE
================================ */
.table-container {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f1f5f9;
    padding: 12px;
    border: 1px solid #e2e8f0;
    text-align: center;
    font-weight: 600;
    font-size: 14.5px;
    color: #0d1a44;
}

.data-table td {
    padding: 12px;
    border: 1px solid #e2e8f0;
    font-size: 14.5px;
}

.btn-view {
    background: #0d6efd;
    color: #fff;
    border: none;
    padding: 7px 16px;
    border-radius: 6px;
    cursor: pointer;
}
.btn-view:hover { background: #0b5ed7; }

/* ===============================
   MODAL
================================ */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff;
    width: 900px;
    max-width: 96%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
}

/* HEADER */
.modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #0d1a44;
}

.modal-close {
    font-size: 22px;
    cursor: pointer;
    color: #6b7280;
}
.modal-close:hover { color: #000; }

/* INFO */
.modal-info {
    padding: 20px 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    row-gap: 14px;
    column-gap: 40px;
    font-size: 15px;
}

.info-item b { color: #111; }

/* ===============================
   QC TABLE
================================ */
.qc-table-wrapper {
    padding: 0 24px 20px;
}

.modal-table {
    width: 100%;
    max-width: 720px;      /* <<< KHÔNG TRÀN */
    margin: 0 auto;
    border-collapse: collapse;
}

.modal-table th {
    background: #0d1a44;
    color: #fff;
    padding: 12px;
    font-size: 14.5px;
    text-align: center;
    border: 1px solid #0d1a44;
}

.modal-table td {
    padding: 12px;
    border: 1px solid #e5e7eb;
    font-size: 14.5px;
    text-align: center;
    background: #fff;
}

.total-row td {
    background: #f8fafc;
    font-weight: 700;
    font-size: 15px;
    color: #0d1a44;
}

.text-success {
    color: #16a34a;
    font-weight: 700;
    font-size: 16px;
}

.text-danger {
    color: #dc2626;
    font-weight: 700;
    font-size: 16px;
}

/* FOOTER */
.modal-footer {
    padding: 16px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid #e5e7eb;
}

.btn-close {
    background: #e5e7eb;
    color: #111;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-export {
    background: #22c55e;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
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
    <h2 class="main-title" style="text-align: center; font-size: 1.5em; margin-bottom: 30px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
                DANH SÁCH BÁO CÁO CHẤT LƯỢNG
            </h2>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã QC</th>
                    <th>Sản phẩm</th>
                    <th>Tổng SL</th>
                    <th>Người lập</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($dsBaoCao)): ?>
                <?php foreach ($dsBaoCao as $row): ?>
                <tr>
                    <td style="text-align:center;"><?= $row['maYC'] ?></td>
                    <td><?= $row['tenSanPham'] ?></td>
                    <td style="text-align:center;font-weight:600;"><?= $row['soLuong'] ?></td>
                    <td style="text-align:center;"><?= $row['tenNguoiLap'] ?></td>
                    <td style="text-align:center;">
                        <button class="btn-view"
                                onclick="openDetail(<?= $row['maYC'] ?>)">
                            Xem
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;padding:20px;">
                        Không có dữ liệu
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</div>

<!-- ================= MODAL ================= -->
<div id="detailModal" class="modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3>PHIẾU BÁO CÁO CHẤT LƯỢNG</h3>
            <span class="modal-close"
                  onclick="document.getElementById('detailModal').style.display='none'">✕</span>
        </div>

        <div class="modal-info">
            <div class="info-item"><b>Mã QC:</b> <span id="ctMaYC"></span></div>
            <div class="info-item"><b>Ngày kiểm tra:</b> <span id="ctNgay"></span></div>
            <div class="info-item"><b>Sản phẩm:</b> <span id="ctTenSP"></span></div>
            <div class="info-item"><b>Người lập:</b> <span id="ctNguoiLap"></span></div>
        </div>

        <div class="qc-table-wrapper">
            <table class="modal-table">
                <thead>
                    <tr>
                        
                        <th>Tổng số lượng</th>
                        <th>Số lượng đạt</th>
                        <th>Số lượng hỏng</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td id="ctTongSL"></td>
                        <td id="ctDat"></td>
                        <td id="ctHong"></td>
                        <td id="ctGhiChu"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button class="btn-close"
                    onclick="document.getElementById('detailModal').style.display='none'">
                Đóng
            </button>
            <a id="btnExport" class="btn-export">Xuất CSV</a>
        </div>

    </div>
</div>

<script>
function openDetail(maYC) {
    fetch("index.php?page=baocao-get&maYC=" + maYC)
        .then(res => res.json())
        .then(data => {
            document.getElementById("ctMaYC").innerText = maYC;
            document.getElementById("ctTenSP").innerText = data.tenSanPham;
            document.getElementById("ctTongSL").innerText = data.soLuong;
            document.getElementById("ctDat").innerText = data.soLuongDat;
            document.getElementById("ctHong").innerText = data.soLuongHong;
            document.getElementById("ctGhiChu").innerText = data.ghiChu || "—";
            document.getElementById("ctNguoiLap").innerText = data.tenNguoiLap;
            document.getElementById("ctNgay").innerText = data.ngayKiemTra;

            document.getElementById("btnExport").href =
                "index.php?page=baocao-export&maYC=" + maYC;

            document.getElementById("detailModal").style.display = "flex";
        });
}

window.onclick = function(e) {
    const modal = document.getElementById("detailModal");
    if (e.target === modal) modal.style.display = "none";
};
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
