<?php
require_once 'layouts/header.php';
require_once 'layouts/nav.php';
?>

<div class="main-layout-wrapper">
    <?php require_once 'layouts/sidebar.php'; ?>

    <main class="main-content">
        <style>
            .main-content {
                background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }

            /* --- CSS CHUNG --- */
            .form-control,
            input[type="text"],
            input[type="number"],
            input[type="date"],
            select,
            textarea {
                width: 100%;
                height: 38px;
                padding: 6px 12px;
                font-size: 14px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;
                background-color: #fff;
            }

            textarea {
                height: auto;
            }

            .table-results {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }

            .table-results th,
            .table-results td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            .table-results th {
                background-color: #f4f4f4;
            }

            .result-row {
                cursor: pointer;
            }

            .result-row:hover {
                background-color: #f0f0f0;
            }

            .modal-overlay {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }

            .modal-overlay.show {
                display: flex;
            }

            .modal-content {
                background: #fff;
                padding: 25px 30px;
                border-radius: 8px;
                width: 680px;
                max-width: 95%;
                position: relative;
                box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
                max-height: 90vh;
                overflow-y: auto;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 28px;
                font-weight: bold;
                color: #888;
                border: none;
                background: none;
                cursor: pointer;
            }

            .close-btn:hover {
                color: #000;
            }

            .assignment-box {
                border-radius: 6px;
                padding: 15px;
                margin-bottom: 15px;
                font-size: 13px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .assignment-box h5 {
                margin-top: 0;
                margin-bottom: 12px;
                font-weight: bold;
                font-size: 14px;
                text-transform: uppercase;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                padding-bottom: 8px;
            }

            .assignment-box label {
                font-weight: 600;
                margin-bottom: 4px;
                display: block;
                font-size: 12px;
                color: #444;
            }

            .assignment-row {
                display: flex;
                gap: 15px;
                margin-bottom: 12px;
                align-items: flex-end;
            }

            .assignment-col {
                flex: 1;
                min-width: 0;
            }

            .box-cat {
                background-color: #e3f2fd;
                border: 1px solid #90caf9;
                border-left: 5px solid #1565c0;
            }

            .box-cat h5 {
                color: #1565c0;
            }

            .box-cat input:focus,
            .box-cat textarea:focus {
                border-color: #1565c0;
                outline: none;
            }

            .box-may {
                background-color: #fff3e0;
                border: 1px solid #ffcc80;
                border-left: 5px solid #ef6c00;
            }

            .box-may h5 {
                color: #ef6c00;
            }

            .box-may input:focus,
            .box-may textarea:focus {
                border-color: #ef6c00;
                outline: none;
            }

            .btn-add-nvl {
                background: #28a745;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                margin-top: 5px;
            }

            .btn-submit {
                background-color: #007bff;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                width: 100%;
                margin-top: 10px;
                font-size: 16px;
            }

            .btn-submit:hover {
                background-color: #0056b3;
            }

            .inline-group {
                display: flex;
                align-items: baseline;
                margin-bottom: 6px;
            }

            .inline-group label {
                width: 110px;
                font-weight: 600;
                color: #555;
            }

            .inline-group b {
                color: #0056b3;
                margin-left: 5px;
            }

            .nvl-row {
                display: flex;
                gap: 10px;
                margin-bottom: 10px;
                align-items: flex-end;
                background: #f8f9fa;
                padding: 10px;
                border: 1px solid #e9ecef;
                border-radius: 4px;
            }

            .btn-remove-nvl {
                height: 38px;
                width: 30px;
                border: none;
                background: transparent;
                color: #dc3545;
                font-size: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-remove-nvl:hover {
                background-color: #ffe6e6;
                border-radius: 4px;
            }

            /* Nút Lập KHSX mới */
            .btn-lap-khsx {
                display: inline-block;
                padding: 8px 14px;
                background: linear-gradient(135deg, #1976d2, #0d47a1);
                color: #fff !important;
                border-radius: 6px;
                font-size: 13.5px;
                font-weight: 600;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: 0.25s;
            }

            .btn-lap-khsx:hover {
                background: linear-gradient(135deg, #1565c0, #0b3c91);
                transform: translateY(-1px);
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            }

            .btn-lap-khsx:active {
                transform: scale(0.97);
            }

            .table-results td:last-child,
            .table-results th:last-child {
                text-align: center !important;
                vertical-align: middle !important;
            }

            .table-results th,
            .table-results td {
                text-align: center !important;
                vertical-align: middle !important;
            }

            /* ============================
   TABLE – FLAT CLEAN STYLE
============================ */
            .table-wrapper {
                margin-top: 20px;
            }

            /* Bảng */
            .table-results {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
                background: #fff;
                border: 1px solid #d7dce3;
            }

            /* Header */
            .table-results thead tr {
                background: #eef2f7;
                color: #000000ff;
                border-bottom: 1px solid #d7dce3;
            }

            .table-results th {
                padding: 10px 12px;
                font-weight: 700;
                text-align: center;
                letter-spacing: 0.2px;
                border-right: 1px solid #d7dce3;
                font-size: 15px;
            }

            .table-results th:last-child {
                border-right: none;
            }

            /* Body */
            .table-results td {
                padding: 10px 12px;
                text-align: center;
                border-bottom: 1px solid #e6e9ee;
                border-right: 1px solid #e6e9ee;
                color: #000000ff;
            }

            .table-results td:last-child {
                border-right: none;
            }

            /* HOVER – nhẹ nhàng */
            .table-results tbody tr:hover {
                background: #f5f8fc;
                transition: 0.2s;
            }

            /* Dòng cuối không bo cong */
            .table-results tbody tr:last-child td {
                border-bottom: none;
            }
        </style>

        <div class="kehoach-form-container">
            <h2 style="text-align:center;color: #ffff"><?= $pageTitle ?? 'LẬP KẾ HOẠCH SẢN XUẤT' ?></h2>
            <hr>

            <!-- FILTER ĐƠN HÀNG -->
            <div style="background:#f8f9fa; padding:15px; border-radius:5px; margin-bottom:20px;">
                <div style="margin-bottom:10px;">
                    <label style="font-weight:bold; display:block; margin-bottom:5px;">Tìm kiếm Đơn hàng:</label>
                    <input type="text" id="search-box" placeholder="Nhập mã hoặc tên đơn hàng...">
                </div>
                <div style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label style="font-weight:bold; display:block; margin-bottom:5px;">Ngày giao:</label>
                        <input type="date" id="filter-ngayGiao">
                    </div>

                    <div style="display:flex; align-items:end;">
                        <button id="btn-clear-filters" class="btn-secondary"
                            style="height:38px; padding:0 15px; background:#6c757d; color:white; border:none; border-radius:4px; cursor:pointer;">Xóa
                            lọc</button>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH ĐƠN HÀNG -->
            <h3 style="margin-top:30px;color: #ffff;text-align:center;">DANH SÁCH CÁC ĐƠN HÀNG</h3>
            <table class="table-results">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Tên Đơn Hàng</th>
                        <th>Ngày Giao</th>
                        <th>Trạng Thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="donhang-tbody">
                    <?php if (!empty($danhSachDonHang)): ?>
                        <?php foreach ($danhSachDonHang as $dh): ?>
                            <tr>
                                <td><?= htmlspecialchars($dh['maDonHang']) ?></td>
                                <td><?= htmlspecialchars($dh['tenDonHang']) ?></td>
                                <td><?= htmlspecialchars($dh['ngayGiao']) ?></td>
                                <td><?= htmlspecialchars($dh['trangThai']) ?></td>
                                <td>
                                    <a href="index.php?page=lap-ke-hoach-chi-tiet&id=<?= $dh['maDonHang'] ?>"
                                        class="btn-lap-khsx">
                                        Lập KHSX
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px;">Chưa có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <!-- DANH SÁCH KẾ HOẠCH ĐÃ LẬP -->
            <h3 style="margin-top:30px;color: #ffff;text-align:center;">DANH SÁCH CÁC KẾ HOẠCH ĐÃ LẬP</h3>
            <table class="table-results">
                <thead>
                    <tr>
                        <th>Tên Kế hoạch</th>
                        <th>Mã Đơn hàng</th>
                        <th>Bắt đầu</th>
                        <th>Kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Người lập</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($danhSachKHSX)): ?>
                        <?php foreach ($danhSachKHSX as $khsx): ?>
                            <tr>
                                <td><?= htmlspecialchars($khsx['tenKHSX']) ?></td>
                                <td><?= htmlspecialchars($khsx['maDonHang']) ?></td>
                                <td><?= htmlspecialchars($khsx['thoiGianBatDau']) ?></td>
                                <td><?= htmlspecialchars($khsx['thoiGianKetThuc']) ?></td>
                                <td><?= htmlspecialchars($khsx['trangThai']) ?></td>
                                <td><?= htmlspecialchars($khsx['tenNguoiLap']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px;">Chưa có dữ liệu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- MODAL LẬP CHI TIẾT KHSX -->
        <div class="modal-overlay" id="modal-lap-khsx">
            <div class="modal-content">
                <button class="close-btn" id="modal-close">&times;</button>
                <h3>Lập Kế hoạch sản xuất chi tiết</h3>
                <form id="form-lap-khsx" method="post" action="index.php?page=lap-ke-hoach-store">
                    <input type="hidden" name="maDonHang" id="modal-maDonHang">

                    <!-- Xưởng Cắt -->
                    <div class="assignment-box box-cat" id="box-xuong-cat">
                        <h5>Xưởng Cắt</h5>
                        <div id="xuong-cat-list"></div>
                        <button type="button" class="btn-add-nvl" data-xuong="cat">Thêm NVL</button>
                    </div>

                    <!-- Xưởng May -->
                    <div class="assignment-box box-may" id="box-xuong-may">
                        <h5>Xưởng May</h5>
                        <div id="xuong-may-list"></div>
                        <button type="button" class="btn-add-nvl" data-xuong="may">Thêm NVL</button>
                    </div>

                    <button type="submit" class="btn-submit">Lưu KHSX</button>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once 'layouts/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const searchBox = document.getElementById("search-box");
    const filterNgayGiao = document.getElementById("filter-ngayGiao");
    const btnClearFilters = document.getElementById("btn-clear-filters");
    const tableRows = document.querySelectorAll("#donhang-tbody tr");

    function filterTable() {
        const searchTerm = searchBox.value.toLowerCase().trim();
        const searchDate = filterNgayGiao.value;

        tableRows.forEach(row => {
            // Lấy dữ liệu từ các cột tương ứng
            const maDH = row.cells[0].textContent.toLowerCase();
            const tenDH = row.cells[1].textContent.toLowerCase();
            const ngayGiao = row.cells[2].textContent.trim();

            // Kiểm tra điều kiện khớp mã/tên VÀ khớp ngày (nếu có chọn ngày)
            const matchesSearch = maDH.includes(searchTerm) || tenDH.includes(searchTerm);
            const matchesDate = searchDate === "" || ngayGiao === searchDate;

            if (matchesSearch && matchesDate) {
                row.style.display = ""; // Hiển thị
            } else {
                row.style.display = "none"; // Ẩn
            }
        });
    }

    // Lắng nghe sự kiện nhập liệu
    searchBox.addEventListener("input", filterTable);
    filterNgayGiao.addEventListener("change", filterTable);

    // Xử lý nút Xóa lọc
    btnClearFilters.addEventListener("click", () => {
        searchBox.value = "";
        filterNgayGiao.value = "";
        tableRows.forEach(row => row.style.display = ""); // Hiện lại tất cả
    });
});
</script>