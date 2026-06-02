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
    <h2>🚀 Nhân sự</h2>
    <p>
        Module Nhân sự cho phép quản lý toàn bộ thông tin về nhân viên trong hệ thống. 
        Các chức năng được thiết kế để hỗ trợ việc thêm mới, theo dõi, điều chỉnh và 
        quản lý vòng đời nhân sự một cách hiệu quả và minh bạch.
    </p>

    <ul>
        <li>
            <strong>Thêm nhân viên</strong> — 
            Cho phép tạo mới hồ sơ nhân viên, bao gồm thông tin cá nhân, chức vụ, phòng ban, 
            tài khoản đăng nhập và các thuộc tính liên quan.
        </li>

        <li>
            <strong>Xem nhân viên</strong> — 
            Hiển thị danh sách toàn bộ nhân viên cùng thông tin chi tiết, hỗ trợ tìm kiếm, 
            lọc theo phòng ban, chức vụ và trạng thái làm việc.
        </li>

        <li>
            <strong>Sửa nhân viên</strong> — 
            Cho phép cập nhật thông tin nhân viên, điều chỉnh quyền truy cập, thay đổi chức vụ, 
            cập nhật liên hệ hoặc chỉnh sửa các dữ liệu hồ sơ khác.
        </li>

        <li>
            <strong>Xóa nhân viên</strong> — 
            Hỗ trợ vô hiệu hóa hoặc xóa khỏi hệ thống những nhân viên không còn hoạt động,
            đảm bảo dữ liệu được xử lý an toàn và đúng quy trình.
        </li>
    </ul>
</div>


    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
