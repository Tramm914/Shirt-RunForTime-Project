<?php
// app/views/ghiNhanTP.php
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
        <div class="container">

            <h2 class="page-title" style="color:#ffff;">GHI NHẬN THÀNH PHẨM</h2>

            <div class="content-wrapper">

                <div class="form-card">
                    <div class="card-header bg-primary text-white">
                        <h3><i class="fas fa-file-pen"></i> Thông Tin Chung</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="required">1. Ngày ghi nhận</label>
                                <input type="date" id="ngayLam" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="form-group">
                                <label class="required">2. Kế hoạch sản xuất</label>
                                <select id="maKHSX" class="form-control select-custom">
                                    <option value="">-- Chọn kế hoạch --</option>
                                    <?php foreach ($danhSachKHSX as $kh): ?>
                                        <option value="<?= $kh['maKHSX'] ?>"><?= htmlspecialchars($kh['tenKHSX']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="required">3. Sản phẩm</label>
                                <select id="maSanPham" class="form-control select-custom" disabled>
                                    <option value="">-- Chọn KHSX trước --</option>
                                </select>
                            </div>
                        </div>

                        <hr class="divider">

                        <div class="add-employee-area">
                            <h4 class="area-title"><i class="fas fa-user-plus"></i> Thêm nhân viên vào danh sách</h4>

                            <div class="form-grid-4 align-end">
                                <div class="form-group">
                                    <label>Lọc Xưởng</label>
                                    <select id="filterXuong" class="form-control">
                                        <option value="">-- Tất cả --</option>
                                        <?php foreach ($danhSachXuong as $x): ?>
                                            <option value="<?= $x['maXuong'] ?>"><?= htmlspecialchars($x['tenXuong']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-span-2">
                                    <label class="required">Chọn Nhân viên</label>
                                    <select id="maNhanVien" class="form-control select-custom" disabled>
                                        <option value="">-- Vui lòng chọn Xưởng --</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="required">Số lượng</label>
                                    <input type="number" id="soLuong" class="form-control text-center" placeholder="0"
                                        min="1">
                                </div>

                                <div class="form-group">
                                    <button type="button" id="btnThemDong" class="btn btn-secondary w-100 h-100-input">
                                        <i class="fas fa-plus"></i> Thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="split-layout">

                    <div class="list-card left-col">
                        <div class="card-header">
                            <h3><i class="fas fa-list-check"></i> Danh sách chờ lưu</h3>
                        </div>
                        <div class="table-scroll">
                            <table class="custom-table" id="tableTemp">
                                <thead>
                                    <tr>
                                        <th>Nhân viên</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="emptyRow">
                                        <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <button type="button" id="btnLuuTatCa" class="btn btn-success w-100" disabled>
                                <i class="fas fa-save"></i> LƯU TẤT CẢ
                            </button>
                        </div>
                    </div>

                    <div class="list-card right-col">
                        <div class="card-header">
                            <h3><i class="fas fa-history"></i> Lịch sử gần đây</h3>
                        </div>
                        <div class="table-scroll">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Kế hoạch / Sản phẩm</th>
                                        <th class="text-center">BTP (Cắt)</th>
                                        <th class="text-center">TP (May)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($lichSuGhiNhan)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Chưa có lịch sử</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($lichSuGhiNhan as $row): ?>
                                            <tr>
                                                <td><?= date('d/m', strtotime($row['ngayLam'])) ?></td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?= htmlspecialchars($row['tenKHSX']) ?>
                                                    </div>
                                                    <small
                                                        class="text-muted"><?= htmlspecialchars($row['tenSanPham']) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="qty-badge btp"><?= number_format($row['slBanThanhPham'] ?? 0) ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="qty-badge tp"><?= number_format($row['slThanhPham'] ?? 0) ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn-icon"
                                                        onclick="viewDetail('<?= $row['ngayLam'] ?>', <?= $row['maKHSX'] ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<div id="modalDetail" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chi tiết phiếu ghi nhận</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nhân viên</th>
                        <th>Phòng ban</th>
                        <th class="text-center">Số lượng</th>
                    </tr>
                </thead>
                <tbody id="tableDetailContent"></tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Reset & Layout */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', sans-serif;
    }

    .page-title {
        text-align: center;
        color: #1565c0;
        font-weight: 800;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .content-wrapper {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Card Styling */
    .form-card,
    .list-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .card-header {
        padding: 15px 25px;
        border-bottom: 1px solid #eee;
    }

    .card-header.bg-primary {
        background: #1565c0;
        color: white;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 25px;
    }

    .card-footer {
        padding: 15px 25px;
        background: #f9f9f9;
        border-top: 1px solid #eee;
    }

    /* Form Grids */
    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .form-grid-4 {
        display: grid;
        grid-template-columns: 1.5fr 2fr 1fr 1fr;
        gap: 15px;
    }

    .align-end {
        align-items: end;
    }

    .col-span-2 {
        grid-column: span 2;
    }

    /* Inputs */
    label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #444;
        margin-bottom: 8px;
        display: block;
    }

    .required::after {
        content: " *";
        color: #e53935;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 0.95rem;
        height: 42px;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        outline: none;
    }

    .form-control:disabled {
        background: #f5f5f5;
        color: #888;
        cursor: not-allowed;
    }

    /* Area Thêm Nhân viên */
    .divider {
        margin: 25px 0;
        border-top: 1px dashed #ddd;
    }

    .add-employee-area {
        background: #e3f2fd;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #bbdefb;
    }

    .area-title {
        color: #1565c0;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    /* Buttons */
    .btn {
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-secondary {
        background: #546e7a;
        color: white;
        height: 42px;
    }

    .btn-secondary:hover {
        background: #455a64;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #2e7d32;
        color: white;
        padding: 12px;
        font-size: 1rem;
    }

    .btn-success:hover:not(:disabled) {
        background: #1b5e20;
    }

    .btn-success:disabled {
        background: #a5d6a7;
        cursor: not-allowed;
    }

    .btn-icon {
        background: #e1f5fe;
        color: #0288d1;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-icon:hover {
        background: #0288d1;
        color: white;
    }

    /* Split Layout (2 Cột dưới) */
    .split-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .table-scroll {
        max-height: 400px;
        overflow-y: auto;
    }

    /* Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        background: #f5f5f5;
        color: #555;
        font-weight: 600;
        padding: 12px 15px;
        text-align: left;
        position: sticky;
        top: 0;
    }

    .custom-table td {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .text-center {
        text-align: center;
    }

    .text-end {
        text-align: right;
    }

    .text-muted {
        color: #888;
    }

    .text-primary {
        color: #1565c0;
    }

    .text-success {
        color: #2e7d32;
    }

    .fw-bold {
        font-weight: 700;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        width: 600px;
        max-width: 90%;
        border-radius: 10px;
        overflow: hidden;
        animation: slideIn 0.3s;
    }

    .modal-header {
        background: #1565c0;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 0;
        max-height: 60vh;
        overflow-y: auto;
    }

    .close-btn {
        font-size: 24px;
        cursor: pointer;
        color: white;
        opacity: 0.8;
    }

    .close-btn:hover {
        opacity: 1;
    }

    /* Style cho các nhãn số lượng */
    .qty-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        min-width: 45px;
        text-align: center;
    }

    /* Màu cho Bán thành phẩm (Cắt) - Thường dùng màu vàng/cam */
    .qty-badge.btp {
        background-color: #fff3e0;
        color: #e65100;
        border: 1px solid #ffe0b2;
    }

    /* Màu cho Thành phẩm (May) - Thường dùng màu xanh lá */
    .qty-badge.tp {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    /* Tăng độ rộng cột để không bị gãy dòng */
    .custom-table th:nth-child(3),
    .custom-table th:nth-child(4) {
        width: 100px;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 992px) {

        .form-grid-3,
        .form-grid-4,
        .split-layout {
            grid-template-columns: 1fr;
        }

        .col-span-2 {
            grid-column: auto;
        }
    }
</style>

<script>
    let tempList = [];

    document.addEventListener('DOMContentLoaded', () => {

        // 1. Load Sản phẩm khi chọn KHSX
        document.getElementById('maKHSX').addEventListener('change', async function () {
            const maKHSX = this.value;
            const spSelect = document.getElementById('maSanPham');

            spSelect.innerHTML = '<option>Đang tải...</option>';
            spSelect.disabled = true;

            if (!maKHSX) {
                spSelect.innerHTML = '<option value="">-- Chọn KHSX trước --</option>';
                return;
            }

            try {
                const res = await fetch(`index.php?page=ajax-get-sp-theo-khsx&maKHSX=${maKHSX}`);
                const data = await res.json();

                spSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.maSanPham;
                        opt.textContent = p.tenSanPham;
                        spSelect.appendChild(opt);
                    });
                    spSelect.disabled = false;
                    if (data.length === 1) spSelect.selectedIndex = 1;
                } else {
                    spSelect.innerHTML = '<option>Không có sản phẩm</option>';
                }
            } catch (e) {
                console.error(e);
                spSelect.innerHTML = '<option>Lỗi tải dữ liệu</option>';
            }
        });

        // 2. Load Nhân viên khi chọn Xưởng
        document.getElementById('filterXuong').addEventListener('change', async function () {
            const maXuong = this.value;
            const nvSelect = document.getElementById('maNhanVien');

            nvSelect.innerHTML = '<option>Đang tải...</option>';
            nvSelect.disabled = true;

            if (!maXuong) {
                nvSelect.innerHTML = '<option value="">-- Vui lòng chọn Xưởng --</option>';
                return;
            }

            try {
                const res = await fetch(`index.php?page=ajax-get-nv-theo-xuong&maXuong=${maXuong}`);
                const data = await res.json();

                nvSelect.innerHTML = '<option value="">-- Chọn nhân viên --</option>';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(nv => {
                        const opt = document.createElement('option');
                        opt.value = nv.maND;
                        opt.dataset.name = nv.hoTen;
                        opt.textContent = `${nv.hoTen} (${nv.chucVu || 'NV'})`;
                        nvSelect.appendChild(opt);
                    });
                    nvSelect.disabled = false;
                } else {
                    nvSelect.innerHTML = '<option>Không có nhân viên</option>';
                }
            } catch (e) {
                console.error(e);
                nvSelect.innerHTML = '<option>Lỗi tải dữ liệu</option>';
            }
        });

        // 3. Thêm vào danh sách tạm
        document.getElementById('btnThemDong').addEventListener('click', () => {
            const nvSelect = document.getElementById('maNhanVien');
            const maNV = nvSelect.value;
            const tenNV = nvSelect.options[nvSelect.selectedIndex]?.dataset.name;
            const slInput = document.getElementById('soLuong');
            const sl = slInput.value;

            if (!maNV || !sl || parseInt(sl) <= 0) {
                alert("Vui lòng chọn nhân viên và nhập số lượng hợp lệ!");
                return;
            }

            tempList.push({
                maNhanVien: maNV,
                tenNhanVien: tenNV,
                soLuong: sl
            });
            renderTable();
            slInput.value = '';
            slInput.focus();
        });

        // 4. Lưu tất cả (Gửi JSON)
        document.getElementById('btnLuuTatCa').addEventListener('click', async () => {
            const maKHSX = document.getElementById('maKHSX').value;
            const maSanPham = document.getElementById('maSanPham').value;
            const ngayLam = document.getElementById('ngayLam').value;

            if (!maKHSX || !maSanPham) {
                alert("Thiếu Kế hoạch hoặc Sản phẩm!");
                return;
            }
            if (tempList.length === 0) {
                alert("Danh sách trống!");
                return;
            }

            if (!confirm(`Xác nhận lưu ${tempList.length} dòng?`)) return;

            const payload = {
                // SỬA: Gom các thông tin chung vào object "header" để khớp với Controller
                header: {
                    maKHSX: maKHSX,
                    maSanPham: maSanPham,
                    ngayLam: ngayLam
                },
                details: tempList
            };

            try {
                const res = await fetch('index.php?page=luu-ghi-nhan-tp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();

                if (result.success) {
                    alert("✅ Lưu thành công!");
                    location.reload();
                } else {
                    alert("❌ Lỗi: " + result.message);
                }
            } catch (e) {
                console.error(e);
                alert("Lỗi kết nối!");
            }
        });
    });

    function renderTable() {
        const tbody = document.querySelector('#tableTemp tbody');
        tbody.innerHTML = '';
        const btnSave = document.getElementById('btnLuuTatCa');

        if (tempList.length === 0) {
            tbody.innerHTML = '<tr id="emptyRow"><td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu</td></tr>';
            btnSave.disabled = true;
            return;
        }

        tempList.forEach((item, index) => {
            tbody.innerHTML += `
            <tr>
                <td>${item.tenNhanVien}</td>
                <td class="text-center fw-bold">${item.soLuong}</td>
                <td class="text-end"><button class="btn-icon" style="background:#ffebee; color:#c62828" onclick="removeRow(${index})"><i class="fas fa-trash-alt"></i></button></td>
            </tr>`;
        });
        btnSave.disabled = false;
    }

    function removeRow(index) {
        tempList.splice(index, 1);
        renderTable();
    }

    // Modal Detail Logic
    async function viewDetail(ngay, khsx) {
        const modal = document.getElementById('modalDetail');
        const tbody = document.getElementById('tableDetailContent');
        modal.style.display = 'flex';
        tbody.innerHTML = '<tr><td colspan="3" class="text-center p-3">Đang tải...</td></tr>';

        try {
            const res = await fetch(`index.php?page=ajax-get-chitiet-phieu&ngay=${ngay}&khsx=${khsx}`);
            const data = await res.json();
            tbody.innerHTML = '';
            if (data.length > 0) {
                data.forEach(r => {
                    tbody.innerHTML += `<tr><td>${r.hoTen}</td><td>${r.phongBan}</td><td class="text-center fw-bold">+${r.soLuongSPHoanThanh}</td></tr>`;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center p-3">Không có dữ liệu</td></tr>';
            }
        } catch (e) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger p-3">Lỗi tải dữ liệu</td></tr>';
        }
    }

    function closeModal() {
        document.getElementById('modalDetail').style.display = 'none';
    }
    window.onclick = function (e) {
        if (e.target == document.getElementById('modalDetail')) closeModal();
    }
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>