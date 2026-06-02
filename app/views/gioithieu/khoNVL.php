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
    <h2>🚀 Quản Lý Nguyên Vật Liệu</h2>
    <p>Module này hỗ trợ quản lý toàn bộ vòng đời của nguyên vật liệu trong doanh nghiệp — từ yêu cầu, nhập kho, xuất kho cho đến thống kê tồn kho. Giúp đảm bảo nguồn cung luôn đầy đủ, chính xác và tối ưu chi phí.</p>

    <ul>
        <li><strong>Tạo yêu cầu nhập nguyên vật liệu</strong> — cho phép bộ phận sản xuất hoặc kho gửi yêu cầu bổ sung vật tư khi sắp hết hoặc cần cho đơn hàng mới.</li>

        <li><strong>Nhập kho nguyên vật liệu</strong> — ghi nhận các lô hàng được mua từ nhà cung cấp, bao gồm số lượng, ngày nhập, đơn giá và nhà cung cấp.</li>

        <li><strong>Xuất nguyên vật liệu</strong> — quản lý việc xuất kho cho sản xuất theo từng đơn hàng, đảm bảo đúng số lượng và kiểm soát hao hụt.</li>

        <li><strong>Thống kê nguyên vật liệu</strong> — báo cáo tồn kho, lịch sử xuất–nhập, dự báo nhu cầu để hỗ trợ lập kế hoạch sản xuất chính xác.</li>
    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
