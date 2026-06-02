<?php
require_once("layouts/header.php");
require_once("layouts/nav.php");
?>

<div class="main-layout-wrapper">
    <?php
    require_once("layouts/sidebar.php");
    ?>

    <main class="main-content">
        <style>
            .main-content {
                background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
            /* ===== MAIN CONTENT ===== */
            /* Bố cục 2 cột */
            .main-content {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
            }

            .left-column {
                flex: 2;
                min-width: 300px;
            }

            .right-column {
                flex: 1;
                min-width: 250px;
                display: block;
            }


            /* Box style cho .form-box */
            .form-box {
                background: var(--mau-nen-container);
                border: 1px solid var(--mau-vien);
                padding: 25px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .form-box h3 {
                display: block;
                font-size: 1.25em;
                color: var(--xanh-dam);
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid var(--mau-vien);
            }

            /* Box style cho .info-box (cột phải) */
            .info-box {
                display: block;
                background: var(--mau-nen-container);
                border: 1px solid var(--mau-vien);
                padding: 25px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .info-box h3 {
                font-size: 1.25em;
                color: var(--xanh-dam);
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid var(--mau-vien);
            }

            .info-box p {
                font-size: 0.95em;
                margin-bottom: 10px;
            }

            /* Style form cho label nằm trên input */
            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
            }

            input[type="date"],
            input[type="text"],
            select {
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 14px;
                width: 100%;
                box-sizing: border-box;
            }

            /* Bố cục date-range */
            .form-group.date-range {
                display: flex;
                align-items: flex-end;
                gap: 10px;
            }

            .form-group.date-range div {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }

            .form-group.date-range span {
                margin: 0 5px;
                padding-bottom: 8px;
            }

            /* Nút bấm */
            .button-group {
                margin-top: 20px;
                display: flex;
                gap: 10px;
                padding-left: 0;
            }

            .btn-submit,
            .btn-reset {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                font-size: 14px;
                color: white;
            }

            .btn-submit {
                background-color: #27ae60;
            }

            .btn-submit:hover {
                background-color: #2ecc71;
            }

            .btn-reset {
                background-color: #7f8c8d;
            }

            .btn-reset:hover {
                background-color: #95a5a6;
            }

            /* Style cho "Danh Sách Kế Hoạch Gần Đây" */
            .table-container {
                width: 100%;
                margin-top: 10px;
            }

            .table-container h3 {
                font-size: 1.25em;
                color: var(--mau-chu-chinh);
                background: var(--mau-nen-container);
                padding: 10px;
                border-bottom: 1px solid var(--mau-vien);
            }

            .table-container table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            .table-container th,
            .table-container td {
                border: 1px solid var(--mau-vien);
                padding: 10px;
                text-align: left;
            }

            .table-container th {
                background-color: var(--mau-nen-container);
            }

            .btn-view,
            .btn-delete {
                padding: 6px 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 600;
                font-size: 13px;
                color: white;
                margin-right: 5px;
                color: white;
            }

            .btn-view {
                background-color: #2980b9;
            }

            .btn-view:hover {
                background-color: #3498db;
            }

            .btn-delete {
                background-color: #c0392b;
            }

            .btn-delete:hover {
                background-color: #e74c3c;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
        <div class="baocao-form-container">

            <h2>Báo Cáo Tổng Hợp</h2>

            <hr>
            <?php if (!empty($error_message)): ?>
                <div class="error-box"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <hr>
            <div class="main-content">
                <div class="left-column">
                    <div class="form-box">
                        <h3>Lọc Và Chọn Báo Cáo</h3>
                        <form action="view_report.php" method="POST">
                            <div class="form-group date-range">
                                <div>
                                    <label for="from_date">Từ Ngày *</label>
                                    <input type="date" id="from_date" name="from_date" value="<?php echo $_POST['from_date'] ?? ''; ?>" required>
                                </div>
                                <span>đến</span>
                                <div>
                                    <label for="to_date">Đến Ngày *</label>
                                    <input type="date" id="to_date" name="to_date" value="<?php echo $_POST['to_date'] ?? ''; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="report_type">Chọn Loại Báo Cáo </label>
                                <select id="report_type" name="report_type" required>
                                    <option value="" disabled <?php echo empty($_POST['report_type']) ? 'selected' : ''; ?>>-- Chọn loại báo cáo --</option>
                                    <option value="san_luong" <?php echo ($_POST['report_type'] ?? '') == 'san_luong' ? 'selected' : ''; ?>>Báo cáo Sản lượng</option>
                                    <option value="nguyen_lieu" <?php echo ($_POST['report_type'] ?? '') == 'nguyen_lieu' ? 'selected' : ''; ?>>Báo cáo Nguyên liệu</option>
                                    <option value="nhan_su" <?php echo ($_POST['report_type'] ?? '') == 'nhan_su' ? 'selected' : ''; ?>>Báo cáo Nhân sự</option>
                                    <option value="kiem_tra_chat_luong" <?php echo ($_POST['report_type'] ?? '') == 'kiem_tra_chat_luong' ? 'selected' : ''; ?>>Báo cáo Kiểm tra chất lượng</option>
                                </select>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="btn-submit">Xem Báo Cáo</button>
                                <button type="reset" class="btn-reset">Đặt Lại</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="right-column">
                    <div class="info-box">
                        <h3>Thông Tin Báo Cáo Hệ thống </h3>
                        <p>Tổng số báo cáo: <strong><?php echo htmlspecialchars($stats['total'] ?? 0); ?></strong></p>
                        <p>Báo cáo mới trong tuần: <strong><?php echo htmlspecialchars($stats['new_this_week'] ?? 0); ?></strong></p>
                    </div>
                </div>

                <div class="table-container">
                    <h3>Báo Cáo Tổng Hợp Gần đây </h3>
                    <table class="report-table" style="width:100%; border-collapse: collapse; margin-top: 15px;">
                        <thead>
                            <tr style="background-color: #f0f0f0;">
                                <th style="padding: 10px; border: 1px solid #ddd;">Mã Báo Cáo</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Tên Báo Cáo</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Loại</th>
                                <th style="padding: 10px; border: 1px solid #ddd;">Ngày Tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reportData)): ?>
                                <tr>
                                    <td colspan="4" style="padding: 10px; border: 1px solid #ddd; text-align: center;">Không tìm thấy dữ liệu phù hợp.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reportData as $row): ?>
                                    <tr>
                                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['report_id'] ?? 'N/A'); ?></td>
                                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['report_name'] ?? 'N/A'); ?></td>
                                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['report_type'] ?? 'N/A'); ?></td>
                                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['report_date'] ?? $row['created_at'] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php
require_once("layouts/footer.php");
?>