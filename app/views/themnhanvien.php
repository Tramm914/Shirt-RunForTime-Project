<?php
// Nạp layout chung (header + nav)
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<style>
    /* --- BACKGROUND CHO MAIN-CONTENT --- */
    .main-content {
        background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* --- 1. CSS ĐỂ ẨN CÁC PHẦN TỬ KHÁC & CĂN GIỮA FORM --- */
    .main-content .header {
        display: none;
    }
    .main-content-inner .form-section:nth-of-type(2) {
        display: none;
    }
    .history-section {
        display: none;
    }
    .main-content .container {
        max-width: 550px;
        margin: 40px auto;
    }

    /* --- 2. CSS CHO FORM --- */
    #employeeFormContainer {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    #employeeFormContainer h2 {
        font-size: 22px;
        font-weight: 600;
        color: #333;
        margin-top: 0;
        margin-bottom: 24px;
    }
    #employeeFormContainer .form-group {
        margin-bottom: 16px;
    }
    #employeeFormContainer .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #444;
    }
    #employeeFormContainer .form-group .required {
        color: #d93025;
        margin-left: 2px;
    }
    #employeeFormContainer .form-group input[type="text"],
#employeeFormContainer .form-group input[type="tel"],
#employeeFormContainer .form-group input[type="email"],
#employeeFormContainer .form-group input[type="date"],
#employeeFormContainer .form-group select {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    height: 44px; /* rất quan trọng để không lệch */
}
    #employeeFormContainer .form-group select {
        background-color: #fff;
    }
    #employeeFormContainer .form-group input::placeholder {
        color: #999;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    .button-group .btn {
        flex: 1;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        text-align: center;
        transition: background-color 0.2s, color 0.2s;
    }
    .button-group .btn[type="submit"] {
        background-color: #333;
        color: white;
        border: 1px solid #333;
    }
    .button-group .btn[type="submit"]:hover {
        background-color: #555;
    }
    .button-group .btn[type="button"] {
        background-color: #fff;
        color: #333;
        border: 1px solid #333;
    }
    .button-group .btn[type="button"]:hover {
        background-color: #f5f5f5;
    }

    /* --- 3. ALERT --- */
    .alert {
        display: none;
        padding: 12px;
        margin-bottom: 16px;
        border-radius: 6px;
        font-size: 14px;
        border: 1px solid transparent;
    }
    .alert.show { display: block; }
    #alertError { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    #alertSuccess { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
    #alertWarning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
</style>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="container">
             <style>
             .main-content {
                background: url('uploads/img/shirt-factory-bg.jpg') center/cover no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
             </style>
             <h2 class="main-title" style="text-align:center; font-size: 1.3em; margin-bottom: 20px; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,0.5); font-weight: 700;">
            THÊM NHÂN VIÊN MỚI
            </h2>
            <div class="main-content-inner">
                <div class="form-section" id="employeeFormContainer">
                    <h2>Thông Tin Nhân Viên</h2>

                    <?php
                    $errorMsg = $_GET['error'] ?? '';
                    $successMsg = $_GET['success'] ?? '';
                    $warningMsg = $_GET['warning'] ?? '';
                    ?>
                    <div id="alertError" class="alert<?php echo $errorMsg ? ' show' : ''; ?>"><?php echo htmlspecialchars($errorMsg); ?></div>
                    <div id="alertWarning" class="alert<?php echo $warningMsg ? ' show' : ''; ?>"><?php echo htmlspecialchars($warningMsg); ?></div>
                    <div id="alertSuccess" class="alert<?php echo $successMsg ? ' show' : ''; ?>"><?php echo htmlspecialchars($successMsg); ?></div>

                    <form id="addEmployeeForm" method="POST" action="index.php?page=themnhanvien">
                        <div class="form-group">
                            <label for="fullName">Họ Tên <span class="required">*</span></label>
                            <input type="text" id="fullName" name="fullName" placeholder="Nhập họ tên" required>
                        </div>
                        <div class="form-group">
                            <label for="gioiTinh">Giới Tính <span class="required">*</span></label>
                            <select id="gioiTinh" name="gioiTinh" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>

                        <div class="form-group">
    <label for="ngaySinh">Ngày Sinh <span class="required">*</span></label>
    <input type="date" id="ngaySinh" name="ngaySinh" required max="<?= date('Y-m-d') ?>">
</div>

                        <div class="form-group">
                            <label for="address">Địa Chỉ</label>
                            <input type="text" id="address" name="address" placeholder="Nhập địa chỉ">
                        </div>

                        <div class="form-group">
                            <label for="phone">Số Điện Thoại <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Nhập email" required>
                        </div>

                        <!-- Phòng ban -->
                        <div class="form-group">
                            <label for="position">Phòng ban <span class="required">*</span></label>
                            <select id="position" name="position" required>
                                <option value="">-- Chọn phòng ban --</option>
                                <option value="xưởng Cắt">xưởng Cắt</option>
                                <option value="xưởng May">xưởng May</option>
                            </select>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn">Lưu</button>
                            <button type="button" class="btn" onclick="resetForm()">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function resetForm() {
                document.getElementById('addEmployeeForm').reset();
            }
        </script>
    </main>
</div>

<?php
require_once 'app/views/layouts/footer.php';
?>
