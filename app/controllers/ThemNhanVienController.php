<?php
require_once 'app/models/NhanVienModel.php';
require_once 'app/models/TaiKhoanModel.php'; // Gọi thêm model tài khoản

class ThemNhanVienController {
    private $nhanVienModel;
    private $taiKhoanModel;

    public function __construct() {
        $this->nhanVienModel = new NhanVienModel();
        $this->taiKhoanModel = new TaiKhoanModel();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $hoTen = trim($_POST['fullName'] ?? '');
            $gioiTinh = trim($_POST['gioiTinh'] ?? '');
            $ngaySinh = trim($_POST['ngaySinh'] ?? '');
            $diaChi = trim($_POST['address'] ?? '');
            $soDienThoai = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $chucVu = trim($_POST['position'] ?? ''); // Phòng ban = chức vụ

            // --- 1. Kiểm tra bắt buộc ---
            if ($hoTen === '' || $soDienThoai === '' || $email === '' || $chucVu === '') {
                $message = 'Vui lòng nhập đầy đủ Họ tên, Số điện thoại, Email và Phòng ban.';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }

            // --- 2. Kiểm tra định dạng email ---
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Email không hợp lệ.';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }

            // --- 3. Kiểm tra định dạng số điện thoại ---
            if (!preg_match('/^[0-9]{10,11}$/', $soDienThoai)) {
                $message = 'Số điện thoại không hợp lệ (chỉ gồm 10-11 chữ số).';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }

            // --- 4. Kiểm tra trùng ---
            if ($this->nhanVienModel->checkDuplicate($email, $soDienThoai)) {
                $message = 'Email hoặc Số điện thoại đã tồn tại.';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }

            // --- 5. Tạo tài khoản ---
            // Lấy phần trước @ từ email, chuẩn hóa (lowercase, chỉ chữ/số)
            $tenDangNhap = strtolower(preg_replace('/[^a-z0-9]/', '', explode('@', $email)[0] ?? ''));
            if ($tenDangNhap === '') {
                // Fallback nếu tên rỗng: dùng một phần của số điện thoại
                $tenDangNhap = preg_replace('/[^0-9]/', '', $soDienThoai);
            }

            // Mật khẩu mặc định (đã mã hóa MD5)
            $matKhau = md5('12345'); // Mật khẩu mặc định: 12345 → MD5
            $trangThai = 'Hoạt động';

            // Gọi hàm createAccount (model sẽ đảm bảo username duy nhất và hash mật khẩu)
            $maTK = $this->taiKhoanModel->createAccount($tenDangNhap, $matKhau, $trangThai);

            if (!$maTK) {
                $message = 'Không thể tạo tài khoản cho nhân viên.';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }

            // --- 6. Thêm nhân viên ---
            $success = $this->nhanVienModel->insert([
                'hoTen' => $hoTen,
                'gioiTinh' => $gioiTinh,
                'ngaySinh' => $ngaySinh,
                'diaChi' => $diaChi,
                'soDienThoai' => $soDienThoai,
                'email' => $email,
                'phongBan' => $chucVu, // Phòng ban từ form
                // 'chucVu' không gửi - sẽ để mặc định là 'Công nhân' trong model
                'maTK' => $maTK
            ]);

            // --- 7. Kết quả ---
            if ($success) {
                $message = 'Thêm nhân viên và tạo tài khoản thành công.';
                header('Location: index.php?page=themnhanvien&success=' . urlencode($message));
                exit;
            } else {
                $message = 'Lưu nhân viên thất bại.';
                header('Location: index.php?page=themnhanvien&error=' . urlencode($message));
                exit;
            }
        } else {
            // --- 8. Hiển thị form ---
            $success = $_GET['success'] ?? '';
            $error = $_GET['error'] ?? '';
            require 'app/views/themnhanvien.php';
        }
    }
}
?>
