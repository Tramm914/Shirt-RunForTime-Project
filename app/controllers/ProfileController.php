<?php
require_once 'app/models/UserModel.php';
require_once 'app/models/ketNoi.php';

class ProfileController {
    public function index() {
        session_start();

        if (!isset($_SESSION['user']['maTK'])) {
            header("Location: index.php?page=login");
            exit();
        }

        // Kết nối CSDL
        $db = (new KetNoi())->connect();
        $userModel = new UserModel($db);

        // Lấy dữ liệu người dùng từ DB
        $maTK = $_SESSION['user']['maTK'];
        $user = $userModel->getUserById($maTK);

        // Kiểm tra nếu không có kết quả
        if (!$user) {
            $user = [
                'maTK' => $maTK,
                'hoTen' => 'Không tìm thấy',
                'chucVu' => '',
                'soDienThoai' => '',
                'email' => '',
                'diaChi' => ''
            ];
        }

        // Nạp view và truyền dữ liệu
        
        include 'app/views/thongTinCaNhan.php';
    }
}
?>
