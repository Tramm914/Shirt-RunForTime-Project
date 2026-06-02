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
    <h2>🚀 Tổng quan</h2>
    <p>
        Module này cung cấp các chức năng hỗ trợ quản lý thông tin nhân sự, 
        theo dõi hoạt động và xử lý các nghiệp vụ liên quan trong hệ thống sản xuất. 
        Tất cả được thiết kế nhằm giúp quản lý dễ dàng, chính xác và hiệu quả hơn.
    </p>

    <ul>
        <li>
            <strong>Thông tin cá nhân</strong> — 
            Xem và cập nhật hồ sơ cá nhân, bao gồm thông tin liên hệ, chức vụ, quyền truy cập
            và các dữ liệu nhân sự liên quan.
        </li>

        <li>
            <strong>Báo cáo tổng hợp</strong> — 
            Theo dõi tổng quan dữ liệu nhân sự: số lượng nhân viên, tình trạng hoạt động,
            lịch sử làm việc, phân quyền, thống kê theo phòng ban và nhiều thông tin quan trọng khác.
        </li>

        <li>
            <strong>Phê duyệt kế hoạch sản xuất</strong> — 
            Người quản lý xem xét, đánh giá và phê duyệt các kế hoạch sản xuất do bộ phận lập kế hoạch gửi lên,
            đảm bảo quy trình vận hành đúng tiến độ và phù hợp với năng lực nhân sự.
        </li>
    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
