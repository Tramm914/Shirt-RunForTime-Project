<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<!-- <style>
 body{
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 }
    .main-content {
    background: url('uploads/img/infor1.png') center center no-repeat;
    background-size: contain;   /* hiển thị toàn bộ ảnh */
    min-height: 100vh;          /* đủ chiều cao khung hình */
}
</style> -->

<div class="main-layout-wrapper_1">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>

    <main class="main-content">
        <h2 class="main-title" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">THÔNG TIN CÁ NHÂN</h2>

        <div class="profile-layout">
            <!-- Cột trái -->
            <div class="profile-left" >
                <div class="avatar-box">
                    <?php if (!empty($user['hinhAnh'])): ?>
                        <img src="uploads/avatar/<?php echo htmlspecialchars($user['hinhAnh']); ?>" class="avatar-img"
                            alt="Avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">👤</div>
                    <?php endif; ?>
                </div>

                <div class="name-box" style="margin-top: 10px;">
                    <?php echo htmlspecialchars($user['hoTen'] ?? 'Không rõ'); ?>
                </div>
            </div>

            <!-- Cột phải -->
            <div class="profile-right">
                <div class="form-grid">
                    <div class="form-group" >
                        <label>Giới tính</label>
                        <div class="input-box"><?php echo htmlspecialchars($user['gioiTinh'] ?? ''); ?></div>
                    </div>

                    <div class="form-group" >
                        <label>Chức vụ</label>
                        <div class="input-box"><?php echo htmlspecialchars($user['chucVu'] ?? ''); ?></div>
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <div class="input-box" ><?php 
                            // Giả sử tên cột trong DB là 'ngaySinh'. 
                            // Nếu có dữ liệu thì format sang ngày/tháng/năm (VN), nếu không thì để trống.
                            if (!empty($user['ngaySinh'])) {
                                echo date('d/m/Y', strtotime($user['ngaySinh']));
                            } else {
                                echo ''; // Hoặc hiển thị "Chưa cập nhật"
                            }
                            ?></div>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <div class="input-box" ><?php echo htmlspecialchars($user['soDienThoai'] ?? ''); ?></div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-box"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
                    </div>

                    <div class="form-group ">
                        <label>Địa chỉ</label>
                        <div class="input-box"><?php echo htmlspecialchars($user['diaChi'] ?? ''); ?></div>
                    </div>
                </div>

                <div class="profile-footer">
                    <a href="index.php" class="btn-reject">← Thoát</a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once 'app/views/layouts/footer.php'; ?>