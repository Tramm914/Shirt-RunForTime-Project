<?php
require_once 'app/models/XoaNhanVienModel.php';
require_once 'app/models/SuaNhanVienModel.php';

class SuaNhanVienController {
    private $model;
    private $userModel;

    public function __construct() {
        $this->model = new XoaNhanVienModel();
        $this->userModel = new SuaNhanVienModel();
    }

    // ✅ Danh sách nhân viên
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $nhanviens = $keyword ? $this->model->search($keyword) : $this->model->getAll();

        require 'app/views/suanhanvien.php';
    }

    // ✅ Hiển thị form sửa
    public function edit() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_GET['id']) || $_GET['id'] === '') {
            echo "Thiếu ID nhân viên!";
            return;
        }

        $id = $_GET['id'];
        $nhanvien = $this->model->getById($id);

        if (!$nhanvien) {
            echo "Không tìm thấy nhân viên!";
            return;
        }

        // ✅ Lấy lỗi + dữ liệu user vừa nhập (nếu submit sai)
        $errors = $_SESSION['form_errors'] ?? [];
        $old    = $_SESSION['old'] ?? [];

        unset($_SESSION['form_errors'], $_SESSION['old']);

        require 'app/views/suathongtinnv.php';
    }

    // ✅ Update nhân viên (validate đầy đủ)
    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=suanhanvien");
            exit;
        }

        $maND        = trim($_POST['maND'] ?? '');
        $hoTen       = trim($_POST['hoTen'] ?? '');
        $gioiTinh    = trim($_POST['gioiTinh'] ?? '');
        $ngaySinh    = trim($_POST['ngaySinh'] ?? '');
        $phongBan    = trim($_POST['phongBan'] ?? '');
        $diaChi      = trim($_POST['diaChi'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $soDienThoai = trim($_POST['soDienThoai'] ?? '');

        $errors = [];

        // 1) maND
        if ($maND === '' || !ctype_digit($maND)) {
            $errors['form'] = "Mã nhân viên không hợp lệ.";
        }

        // 2) Họ tên: 2-50, chữ có dấu, cho phép khoảng trắng / - / '
        $regexHoTen = '/^(?=.{2,50}$)\p{L}+(?:[ \'-]\p{L}+)*$/u';
        if ($hoTen === '' || !preg_match($regexHoTen, $hoTen)) {
            $errors['hoTen'] = "Họ tên 2–50 ký tự, chỉ gồm chữ và khoảng trắng.";
        }

        // 3) Giới tính: whitelist
        $allowedGioiTinh = ['Nam', 'Nữ'];
        if (!in_array($gioiTinh, $allowedGioiTinh, true)) {
            $errors['gioiTinh'] = "Giới tính không hợp lệ.";
        }

        // 4) Ngày sinh: YYYY-MM-DD + tuổi >= 16
        $dt = DateTime::createFromFormat('Y-m-d', $ngaySinh);
        $validDate = $dt && $dt->format('Y-m-d') === $ngaySinh;
        

        // 5) Phòng ban: whitelist (khớp option trong select)
        $allowedPhongBan = ['Xưởng cắt', 'Xưởng may'];
        if (!in_array($phongBan, $allowedPhongBan, true)) {
            $errors['phongBan'] = "Phòng ban không hợp lệ.";
        }

        // 6) Địa chỉ: 5-120, chữ/số + , . - / #
        $regexDiaChi = '/^(?=.{5,120}$)[\p{L}\p{N}\s,.\-\/#]+$/u';
        if ($diaChi === '' || !preg_match($regexDiaChi, $diaChi)) {
            $errors['diaChi'] = "Địa chỉ 5–120 ký tự, chỉ gồm chữ/số và các ký tự";
        }

        // 7) Email
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email không hợp lệ.";
        }

        // 8) SĐT: 0xxxxxxxxx (10 số) hoặc +84xxxxxxxxx
        $regexPhone = '/^(0\d{9}|\+84\d{9})$/';
        if ($soDienThoai === '' || !preg_match($regexPhone, $soDienThoai)) {
            $errors['soDienThoai'] = "Số điện thoại phải dạng 0xxxxxxxxx";
        }

        // ✅ Nếu có lỗi -> QUAY LẠI FORM, KHÔNG UPDATE
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['old'] = $_POST;

            header("Location: index.php?page=suanhanvien&action=edit&id=" . urlencode($maND));
            exit;
        }

        // ✅ Hợp lệ -> UPDATE
        $data = [
            'maND'        => (int)$maND,
            'hoTen'       => $hoTen,
            'gioiTinh'    => $gioiTinh,
            'ngaySinh'    => $ngaySinh,
            'phongBan'    => $phongBan,
            'diaChi'      => $diaChi,
            'email'       => $email,
            'soDienThoai' => $soDienThoai
        ];

        $result = $this->userModel->update($data);

        if ($result['ok'] === true) {
            // affected = 0 nghĩa là không có thay đổi hoặc không tìm thấy maND
            if ($result['affected'] === 0) {
                $_SESSION['warning'] = "Không có thay đổi (hoặc không tìm thấy nhân viên).";
            } else {
                $_SESSION['success'] = "Cập nhật nhân viên thành công!";
            }
            header("Location: index.php?page=suanhanvien");
            exit;
        }

        // update thất bại
        $_SESSION['form_errors'] = ['form' => 'Cập nhật thất bại: ' . ($result['error'] ?? 'Vui lòng thử lại')];
        $_SESSION['old'] = $_POST;
        header("Location: index.php?page=suanhanvien&action=edit&id=" . urlencode($maND));
        exit;
    }
}
