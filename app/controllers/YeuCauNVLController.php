<?php
require_once './app/models/KeHoachSanXuatModel.php';
require_once './app/models/PhieuYeuCauCCNVLModel.php';

class YeuCauNVLController
{

    public function index()
    {
        // 1. Gọi Model
        $khsxModel = new KeHoachSanXuatModel();
        $danhSachKHSX = $khsxModel->getAllPlans();

        // 2. Tải View và truyền dữ liệu $danhSachKHSX ra
        require_once './app/views/taoYCCungCapNVL.php';
    }



    public function chiTiet()
    {
        // Lấy mã KHSX từ URL
        if (!isset($_GET['maKHSX'])) {
            echo "Lỗi: Không có mã kế hoạch sản xuất.";
            return;
        }
        $maKHSX = $_GET['maKHSX'];

        // 1. Gọi Model
        $khsxModel = new KeHoachSanXuatModel();

        // Lấy thông tin phiếu (để điền tên, người lập)
        $thongTinPhieu = $khsxModel->getPlanById($maKHSX);

        // Lấy danh sách NVL (để điền vào bảng)
        $danhSachNVL = $khsxModel->getMaterialsForPlan($maKHSX);

        // 2. Tải View và truyền 2 khối dữ liệu
        require_once './app/views/taoYCCungCapNVL_ChiTiet.php';
    }
    public function luuPhieu()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Khởi động session nếu chưa có
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // 2. Lấy maND từ session (cấu trúc dựa trên LoginController của bạn)
            $maND_dang_nhap = $_SESSION['user']['maND'] ?? null;

            if (!$maND_dang_nhap) {
                die("Lỗi: Bạn cần đăng nhập để thực hiện chức năng này.");
            }

            // 3. Chuẩn bị dữ liệu để lưu
            // Gán maND trực tiếp từ session vào mảng POST để Model sử dụng
            $_POST['maND'] = $maND_dang_nhap;

            $phieuModel = new PhieuYeuCauModel(); // Đảm bảo tên class khớp với file Model của bạn
            $success = $phieuModel->createPhieuYeuCau($_POST);

            if ($success) {
                header('Location: index.php?page=tao-yeu-cau-nvl&success=1');
                exit;
            } else {
                // Hiển thị lỗi để kiểm tra (như bạn đã viết)
                echo "<h1>Lỗi xảy ra khi lưu phiếu!</h1>";
                echo "<pre>";
                print_r($_POST);
                echo "</pre>";
                die();
            }
        } else {
            header('Location: index.php?page=tao-yeu-cau-nvl');
            exit;
        }
    }
}
?>