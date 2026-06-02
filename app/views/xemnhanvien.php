<?php
require_once 'app/views/layouts/header.php';
require_once 'app/views/layouts/nav.php';
?>

<div class="main-layout-wrapper">
    <?php require_once 'app/views/layouts/sidebar.php'; ?>
    <main class="main-content">
        <div class="nhanvien-container">
            <h2>QUẢN LÝ NHÂN SỰ</h2>
            <form class="form-view">
                <label>Mã nhân viên</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['maND']) ?>" readonly>

                <label>Họ tên</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['hoTen']) ?>" readonly>

                <label>Giới Tính</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['gioiTinh']) ?>" readonly>

                <label>Ngày Sinh</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['ngaySinh']) ?>" readonly>

                <label>Chức vụ</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['chucVu']) ?>" readonly>

                <label>Phòng ban</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['phongBan']) ?>" readonly>

                <label>Địa chỉ</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['diaChi']) ?>" readonly>

                <label>Email</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['email']) ?>" readonly>

                <label>Số điện thoại</label>
                <input type="text" value="<?= htmlspecialchars($nhanvien['soDienThoai']) ?>" readonly>

                <div class="form-buttons">
                    <button type="button" onclick="window.history.back()">Xong</button>
                </div>
            </form>
        </div>

        <style>
        .nhanvien-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 40px 50px;
            margin: 20px auto;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            font-size: 20px;
            color: #0d1a44;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #0d1a44;
        }

        input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }

        input[readonly] {
            background-color: #f1f1f1;
            cursor: not-allowed;
        }

        .form-buttons {
            text-align: center;
        }

        button {
            background-color: #5a8dee;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #4076db;
        }
        </style>
    </main>
    <script>
document.addEventListener("DOMContentLoaded", function () {

    const xemNV = document.getElementById("xemNhanVien");
    const xoaNV = document.getElementById("xoaNhanVien");

    if (xemNV) {
        xemNV.addEventListener("click", function () {
            window.location.href = "index.php?page=nhanvien";
        });
    }

    if (xoaNV) {
        xoaNV.addEventListener("click", function () {
            window.location.href = "index.php?page=nhanvien";
        });
    }
});
</script>

</div>

<?php require_once 'app/views/layouts/footer.php'; ?>
