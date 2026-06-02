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
    <h2>🚀 Quản Lý Công Việc Sản Xuất</h2>
    <p>Module này hỗ trợ theo dõi và quản lý các công việc trong quá trình sản xuất, giúp nhân viên thực hiện nhiệm vụ đúng tiến độ, đồng thời đảm bảo nguyên vật liệu và chất lượng được kiểm soát đầy đủ.</p>

    <ul>
        <li><strong>Xem công việc</strong> — hiển thị danh sách công việc được giao theo từng đơn hàng, bao gồm trạng thái, hạn hoàn thành và người phụ trách.</li>

        <li><strong>Theo dõi công việc</strong> — cập nhật tiến độ, báo cáo tình trạng thực hiện, ghi chú và phản hồi để đảm bảo quy trình diễn ra trơn tru.</li>

        <li><strong>Yêu cầu cung cấp nguyên vật liệu</strong> — tạo yêu cầu bổ sung NVL khi cần để tránh gián đoạn sản xuất; tự động liên kết với kho.</li>

        <li><strong>Yêu cầu kiểm tra chất lượng</strong> — gửi yêu cầu QC để kiểm tra bán thành phẩm hoặc thành phẩm trước khi chuyển sang giai đoạn tiếp theo.</li>
    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
