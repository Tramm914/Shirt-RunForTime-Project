<?php
// app/views/baoCaoTongHop.php
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
            /* ... CSS cũ của bạn ... */
            .report-container {
                background: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
                margin: 20px auto;
                max-width: 1200px;
            }

            h2 {
                text-align: center;
                color: #0d1a44;
                margin-bottom: 25px;
            }

            .filter-form {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 100px;
                gap: 15px;
                align-items: flex-end;
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .filter-form label {
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }

            .filter-form input,
            .filter-form select {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .btn-loc {
                background: #5a8dee;
                color: #fff;
                border: none;
                padding: 9px;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                height: 35px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                padding: 10px;
                border: 1px solid #e2e8f0;
                text-align: left;
            }

            th {
                background: #f1f5fb;
            }

            .badge {
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 0.85em;
                background: #eee;
            }

            /* CSS cho Modal và Nút Xem (đã thêm ở bước trước) */
            .btn-view {
                padding: 5px 10px;
                background: #5a8dee;
                color: white !important;
                text-decoration: none;
                border-radius: 4px;
                font-size: 0.9em;
                cursor: pointer;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }

            .modal-content {
                background: #fff;
                padding: 25px;
                border-radius: 8px;
                width: 800px;
                max-width: 90%;
                max-height: 80vh;
                overflow-y: auto;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                position: relative;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 24px;
                cursor: pointer;
                background: none;
                border: none;
            }
        </style>

        <div class="report-container">
            <h2>BÁO CÁO TỔNG HỢP</h2>

            <form method="POST" action="index.php?page=bao-cao-tong-hop" class="filter-form">
                <div>
                    <label for="start_date">Từ ngày (Bỏ trống = Tất cả)</label>
                    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($filter_values['start']) ?>">
                </div>
                <div>
                    <label for="end_date">Đến ngày (Bỏ trống = Tất cả)</label>
                    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($filter_values['end']) ?>">
                </div>
                <div>
                    <label for="loai_bao_cao">Loại báo cáo</label>
                    <select id="loai_bao_cao" name="loai_bao_cao">
                        <option value="all" <?= $filter_values['loai'] == 'all' ? 'selected' : '' ?>>-- Tất cả --</option>
                        <option value="khsx" <?= $filter_values['loai'] == 'khsx' ? 'selected' : '' ?>>Kế hoạch sản xuất</option>
                        <option value="donhang" <?= $filter_values['loai'] == 'donhang' ? 'selected' : '' ?>>Đơn hàng sản xuất</option>
                        <option value="yeucaunvl" <?= $filter_values['loai'] == 'yeucaunvl' ? 'selected' : '' ?>>Yêu cầu cung cấp NVL</option>
                        <option value="yeucaunhapkho" <?= $filter_values['loai'] == 'yeucaunhapkho' ? 'selected' : '' ?>>Yêu cầu nhập kho NVL</option>
                        <option value="phieunhapnvl" <?= $filter_values['loai'] == 'phieunhapnvl' ? 'selected' : '' ?>>Phiếu nhập NVL</option>
                        <option value="phieuxuatnvl" <?= $filter_values['loai'] == 'phieuxuatnvl' ? 'selected' : '' ?>>Phiếu xuất NVL</option>
                        <option value="phieuxuattp" <?= $filter_values['loai'] == 'phieuxuattp' ? 'selected' : '' ?>>Phiếu xuất Thành phẩm</option>
                        <option value="yeucauqc" <?= $filter_values['loai'] == 'yeucauqc' ? 'selected' : '' ?>>Yêu cầu QC</option>
                        <option value="ghinhanthanhpham" <?= $filter_values['loai'] == 'ghinhanthanhpham' ? 'selected' : '' ?>>Ghi nhận thành phẩm</option>
                        <option value="baocaoloi" <?= $filter_values['loai'] == 'baocaoloi' ? 'selected' : '' ?>>Báo cáo lỗi</option>
                    </select>
                </div>
                <button type="submit" class="btn-loc">Lọc</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Loại Phiếu</th>
                        <th>Tên / Mã Phiếu</th>
                        <th>Người Lập</th>
                        <th>Trạng Thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($danhSachBaoCao)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center;color:gray;">Không có dữ liệu.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($danhSachBaoCao as $row): ?>
                            <tr>
                                <td><?= !empty($row['ngay_tao']) ? date('d/m/Y', strtotime($row['ngay_tao'])) : '-' ?></td>
                                <td><span class="badge"><?= htmlspecialchars($row['loai_phieu_text']) ?></span></td>
                                <td><?= htmlspecialchars($row['ten_phieu']) ?> (ID: <?= $row['id'] ?>)</td>
                                <td><?= htmlspecialchars($row['nguoi_lap'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['trang_thai']) ?></td>
                                <td>
                                    <a href="#" class="btn-view"
                                        onclick="event.preventDefault(); showDetails(<?= $row['id'] ?>, '<?= $row['loai_phieu_key'] ?>')">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<div id="detailsModal" class="modal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeDetails()">&times;</button>
        <h3 id="modalTitle">Chi tiết</h3>
        <div id="modalBody"></div>
    </div>
</div>

<script>
    const modal = document.getElementById('detailsModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');

    async function showDetails(id, type) {
        modal.style.display = 'flex';
        modalTitle.innerText = `Chi tiết Phiếu (ID: ${id})`;
        modalBody.innerHTML = '<p>Đang tải...</p>';

        try {
            const res = await fetch(`index.php?page=ajax-get-report-details&id=${id}&type=${type}`);
            const data = await res.json();

            if (data.error) {
                modalBody.innerHTML = `<p style="color:red;">Lỗi: ${data.error}</p>`;
                return;
            }

            let html = '<h4>Thông tin chung:</h4><ul style="list-style:none; padding-left:0;">';
            if (data.info) {
                for (const key in data.info) {
                    html += `<li style="margin-bottom:5px;"><strong>${key}:</strong> ${data.info[key] || 'N/A'}</li>`;
                }
            }
            html += '</ul>';

            if (data.items && data.items.length > 0) {
                html += '<h4 style="margin-top: 15px;">Chi tiết:</h4>';
                html += '<table style="width:100%; border-collapse: collapse;"><thead><tr style="background:#f0f0f0;">';
                Object.keys(data.items[0]).forEach(key => {
                    html += `<th style="border:1px solid #ccc; padding: 5px;">${key}</th>`;
                });
                html += '</tr></thead><tbody>';
                data.items.forEach(item => {
                    html += '<tr>';
                    Object.values(item).forEach(val => {
                        html += `<td style="border:1px solid #ccc; padding: 5px;">${val || 'N/A'}</td>`;
                    });
                    html += '</tr>';
                });
                html += '</tbody></table>';
            }
            modalBody.innerHTML = html;
        } catch (err) {
            modalBody.innerHTML = `<p style="color:red;">Lỗi: ${err.message}</p>`;
        }
    }

    function closeDetails() {
        modal.style.display = 'none';
        modalBody.innerHTML = '';
    }
    window.onclick = function(event) {
        if (event.target == modal) closeDetails();
    }
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>