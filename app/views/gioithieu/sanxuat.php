<?php require_once 'app/views/layouts/header.php'; ?>
<?php require_once 'app/views/layouts/nav.php'; ?>
<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">

        <style>
            /* Khung chính giống các trang còn lại */
            .main-content {
                padding: 30px 40px;
                min-height: calc(100vh - 180px);
                background-color: #f5f7fa;
            }

            /* Card giới thiệu */
            .intro-card {
                background: #fff;
                padding: 30px 35px;
                border-radius: 14px;
                max-width: 900px;
                margin: 40px auto;
                box-shadow: 0 4px 18px rgba(0,0,0,0.08);
                position: relative;
            }

            .intro-card::before {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 4px;
                background: linear-gradient(to bottom, #3498db, #2980b9);
                border-radius: 4px 0 0 4px;
            }

            .intro-card h2 {
                font-size: 26px;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 15px;
                padding-left: 10px;
                position: relative;
            }

            .intro-card h2::after {
                content: "";
                position: absolute;
                bottom: -8px;
                left: 10px;
                height: 3px;
                width: 60px;
                background: #3498db;
                border-radius: 2px;
            }

            .intro-card p {
                font-size: 16px;
                line-height: 1.7;
                color: #555;
                padding-left: 10px;
            }

            .intro-card ul {
                padding-left: 40px;
                margin-top: 20px;
            }

            .intro-card li {
                margin-bottom: 12px;
                font-size: 16px;
                position: relative;
            }

            .intro-card li::before {
                content: "•";
                color: #3498db;
                position: absolute;
                left: -18px;
                font-size: 22px;
            }

            @media (max-width: 768px) {
                .main-content {
                    padding: 20px;
                }
                .intro-card {
                    padding: 22px;
                }
            }
        </style>

        <div class="intro-card">
    <h2>🚀 Giới thiệu Module Sản Xuất</h2>
    <p>
        Module Sản Xuất hỗ trợ doanh nghiệp quản lý toàn bộ quy trình sản xuất theo một chu trình
        rõ ràng và khép kín: từ tiếp nhận đơn hàng, lập kế hoạch, điều phối nguồn lực, đến theo dõi
        tiến độ và phê duyệt. Tất cả nhằm đảm bảo năng suất ổn định, giảm lãng phí và nâng cao chất lượng.
    </p>

    <ul>
        <li>
            <strong>Tạo và quản lý đơn hàng sản xuất</strong> — 
            Thiết lập đơn hàng theo mẫu áo, số lượng, size, thời gian giao hàng và các thông tin yêu cầu;
            giúp dễ dàng theo dõi tình trạng và lịch sử xử lý.
        </li>

        <li>
            <strong>Lập kế hoạch sản xuất theo tuần/tháng</strong> — 
            Tự động tính toán công suất, phân bổ chuyền/xưởng, đối chiếu nguyên vật liệu và sắp xếp thứ tự ưu tiên
            nhằm tối ưu tiến độ.
        </li>

        <li>
            <strong>Phê duyệt yêu cầu từ các bộ phận</strong> — 
            Kiểm tra tính hợp lệ của yêu cầu sản xuất, xác nhận kế hoạch và đảm bảo sự thống nhất giữa các phòng ban
            trước khi triển khai thực tế.
        </li>
    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
