<?php
require_once 'app/models/DanhMucModel.php';
require_once 'app/models/HomeModel.php';

class HomeController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu chưa đăng nhập → chỉ hiển thị trang chào mừng đơn giản
        if (!isset($_SESSION['user'])) {
            $isLoggedIn = false;
            require_once 'app/views/home.php'; // 👈 trang chưa đăng nhập
            return;
        }

        // Nếu đã đăng nhập → hiển thị trang có danh mục
        $isLoggedIn = true;
        $danhMucModel = new DanhMucModel();
        $danhMucData = $danhMucModel->getDanhMuc();

        $homeModel = new HomeModel();
        $thongKe = $homeModel->layThongKeTongQuan();
        $duLieuBieuDo = $homeModel->layNangSuatTheoNgay(7);

        $KHSXDangTrienKhai = $homeModel->layKHSXDangTrienKhai();
        $KHSXDaThucHien = $homeModel->layKHSXDaThucHien();
        $tonKhoNVL = $homeModel->layTonKhoNVL();

        // ✅ Lấy danh sách chức năng theo role
        require_once 'app/middleware/AuthMiddleware.php';
        $menuItems = $this->getMenuItemsByRole();
        
        // Debug
        error_log("MenuItems count: " . count($menuItems));
        foreach ($menuItems as $cat => $items) {
            error_log("Category: $cat - Items: " . count($items));
        }

        require_once 'app/views/home.php';
    }

    /**
     * ✅ Lấy danh sách menu items theo role của user
     */
    private function getMenuItemsByRole()
    {
        $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Công nhân';
        
        // Định nghĩa tất cả chức năng theo danh mục
        $allCategories = [
            'Tổng quan & Báo cáo' => [
                ['link' => 'thong-tin-ca-nhan', 'icon' => 'fa-user', 'text' => 'Thông tin cá nhân'],
                ['link' => 'bao-cao-tong-hop', 'icon' => 'fa-chart-bar', 'text' => 'Báo cáo tổng hợp'],
                ['link' => 'phe-duyet-ke-hoach-sx', 'icon' => 'fa-check-circle', 'text' => 'Phê duyệt KHSX'],
            ],
            'Quản lý Nhân sự' => [
                ['link' => 'themnhanvien', 'icon' => 'fa-user-plus', 'text' => 'Thêm nhân viên'],
                ['link' => 'xemnhanvien', 'icon' => 'fa-users', 'text' => 'Xem nhân viên'],
                ['link' => 'suanhanvien', 'icon' => 'fa-user-edit', 'text' => 'Sửa nhân viên'],
                ['link' => 'xoanhanvien', 'icon' => 'fa-user-times', 'text' => 'Xóa nhân viên'],
            ],
            'Quản lý Sản xuất' => [
                ['link' => 'tao-don-hang-san-xuat', 'icon' => 'fa-cart-plus', 'text' => 'Tạo đơn hàng SX'],
                ['link' => 'lap-khsx', 'icon' => 'fa-calendar-alt', 'text' => 'Lập kế hoạch SX'],
                ['link' => 'phe-duyet-cac-yeu-cau', 'icon' => 'fa-check-circle', 'text' => 'Phê duyệt yêu cầu'],
            ],
            'Kho Nguyên vật liệu' => [
                ['link' => 'tao-yeu-cau-nhap-kho', 'icon' => 'fa-file-import', 'text' => 'Tạo yêu cầu nhập NVL'],
                ['link' => 'nhap-kho-nvl', 'icon' => 'fa-download', 'text' => 'Nhập kho NVL'],
                ['link' => 'xuat-kho-nvl', 'icon' => 'fa-upload', 'text' => 'Xuất kho NVL'],
                ['link' => 'thongke-khonvl', 'icon' => 'fa-chart-line', 'text' => 'Thống kê kho NVL'],
            ],
            'Xưởng sản xuất' => [
                ['link' => 'xemcongviec', 'icon' => 'fa-tasks', 'text' => 'Xem công việc'],
                ['link' => 'tao-yeu-cau-nvl', 'icon' => 'fa-clipboard-list', 'text' => 'Yêu cầu cung cấp nguyên vật liệu'],
                ['link' => 'tao-yeu-cau-kiem-tra-chat-luong', 'icon' => 'fa-clipboard-check', 'text' => 'Yêu cầu kiểm tra chất lượng'],
                ['link' => 'ghi-nhan-tp', 'icon' => 'fa-edit', 'text' => 'Ghi nhận thành phẩm'],
            ],
            'Kiểm tra chất lượng' => [
                ['link' => 'cap-nhat-thanh-pham', 'icon' => 'fa-check-square', 'text' => 'Cập nhật thành phẩm'],
                ['link' => 'baocao-chatluong', 'icon' => 'fa-file-alt', 'text' => 'Báo cáo chất lượng'],
            ],
            'Kho Thành phẩm' => [
                ['link' => 'nhap-kho-thanh-pham', 'icon' => 'fa-box-open', 'text' => 'Nhập kho TP'],
                ['link' => 'xuatthanhpham', 'icon' => 'fa-truck-loading', 'text' => 'Xuất thành phẩm'],
                ['link' => 'thongke', 'icon' => 'fa-chart-pie', 'text' => 'Thống kê kho TP'],
            ],
            'Công việc & Báo cáo' => [
                ['link' => 'calamviec', 'icon' => 'fa-calendar', 'text' => 'Xem lịch làm việc'],
                ['link' => 'baocaosuco', 'icon' => 'fa-exclamation-triangle', 'text' => 'Báo cáo sự cố'],
            ],
        ];

        // Lấy danh sách quyền của role hiện tại
        $permissions = AuthMiddleware::getCurrentPermissions();

        // Lọc các chức năng theo quyền
        $menuByCategory = [];
        foreach ($allCategories as $categoryName => $items) {
            $filteredItems = [];
            foreach ($items as $item) {
                if (in_array($item['link'], $permissions)) {
                    $filteredItems[] = $item;
                }
            }
            if (!empty($filteredItems)) {
                $menuByCategory[$categoryName] = $filteredItems;
            }
        }

        return $menuByCategory;
    }
}
