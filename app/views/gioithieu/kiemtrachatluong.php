<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">

        <style>
            .main-content {
                padding: 30px 40px;
                min-height: calc(100vh - 180px);
                background: #f5f7fa;
            }

            .intro-card {
                background: #fff;
                padding: 30px;
                border-radius: 14px;
                max-width: 900px;
                margin: 40px auto;
                box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                position: relative;
            }

            .intro-card::before {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                width: 4px;
                height: 100%;
                background: linear-gradient(to bottom, #3498db, #2980b9);
                border-radius: 4px 0 0 4px;
            }

            .intro-card h2 {
                font-size: 28px;
                font-weight: 600;
                padding-left: 10px;
                color: #2c3e50;
                margin-bottom: 15px;
            }

            .intro-card h2::after {
                content: "";
                position: absolute;
                bottom: -8px;
                left: 10px;
                width: 60px;
                height: 3px;
                background: #3498db;
                border-radius: 2px;
            }

            .intro-card p {
                font-size: 16px;
                color: #555;
                line-height: 1.7;
                padding-left: 10px;
            }

            .intro-card ul {
                margin-top: 20px;
                padding-left: 40px;
            }

            .intro-card li {
                margin-bottom: 12px;
                font-size: 16px;
                position: relative;
            }

            .intro-card li::before {
                content: "•";
                color: #3498db;
                font-size: 20px;
                position: absolute;
                left: -18px;
                top: 2px;
            }
        </style>

        <div class="intro-card">
    <h2>🚀 Quản Lý Chất Lượng (QC)</h2>
    <p>Module này hỗ trợ bộ phận QC theo dõi, đánh giá và kiểm soát chất lượng sản phẩm trong toàn bộ quy trình sản xuất — giúp đảm bảo thành phẩm đạt chuẩn trước khi xuất kho.</p>

    <ul>
        <li><strong>Cập nhật thành phẩm</strong> — ghi nhận kết quả kiểm tra thành phẩm, phân loại đạt/không đạt và theo dõi lỗi chi tiết.</li>

        <li><strong>Báo cáo chất lượng</strong> — tạo báo cáo tổng hợp theo ngày/tuần/tháng, thống kê lỗi, xu hướng chất lượng và hiệu suất sản xuất.</li>

    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
