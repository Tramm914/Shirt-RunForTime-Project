<?php
/**
 * AuthMiddleware - Kiểm tra phân quyền truy cập
 * Mỗi vai trò chỉ được thực hiện các chức năng được phép
 */

class AuthMiddleware {
    
    /**
     * Mapping từ route sang thông tin menu
     * Format: 'route' => ['icon' => '...', 'text' => '...', 'order' => số thứ tự]
     */
    private static $routeMenuMap = [
        'home' => ['icon' => 'fa-home', 'text' => 'Trang chủ', 'order' => 1],
        'thong-tin-ca-nhan' => ['icon' => 'fa-user', 'text' => 'Thông tin cá nhân', 'order' => 99],
        'profile' => ['icon' => 'fa-user', 'text' => 'Thông tin cá nhân', 'order' => 99],
        
        // Giám đốc
        'phe-duyet-ke-hoach-sx' => ['icon' => 'fa-check-circle', 'text' => 'Phê duyệt KHSX', 'order' => 10],
        'lap-bao-cao' => ['icon' => 'fa-chart-bar', 'text' => 'Xem báo cáo', 'order' => 20],
        'xem-bao-cao' => ['icon' => 'fa-chart-bar', 'text' => 'Xem báo cáo', 'order' => 20],
        
        // Quản lý nhân sự
        'themnhanvien' => ['icon' => 'fa-user-plus', 'text' => 'Thêm nhân viên', 'order' => 10],
        'xemnhanvien' => ['icon' => 'fa-users', 'text' => 'Xem nhân viên', 'order' => 11],
        'suanhanvien' => ['icon' => 'fa-user-edit', 'text' => 'Sửa nhân viên', 'order' => 12],
        'xoanhanvien' => ['icon' => 'fa-user-times', 'text' => 'Xóa nhân viên', 'order' => 13],
        
        // Quản lý sản xuất
        'tao-don-hang-san-xuat' => ['icon' => 'fa-cart-plus', 'text' => 'Tạo đơn hàng SX', 'order' => 10],
        'lap-ke-hoach' => ['icon' => 'fa-calendar-alt', 'text' => 'Lập kế hoạch SX', 'order' => 11],
        'phe-duyet-cac-yeu-cau' => ['icon' => 'fa-check-circle', 'text' => 'Phê duyệt yêu cầu', 'order' => 12],
        
        // Quản lý kho NVL
        'tao-yeu-cau-nhap-kho' => ['icon' => 'fa-file-import', 'text' => 'Tạo yêu cầu nhập NVL', 'order' => 10],
        'nhap-kho-nvl' => ['icon' => 'fa-download', 'text' => 'Nhập kho NVL', 'order' => 11],
        'xuat-kho-nvl' => ['icon' => 'fa-upload', 'text' => 'Xuất kho NVL', 'order' => 12],
        'thongke-khonvl' => ['icon' => 'fa-chart-line', 'text' => 'Thống kê kho NVL', 'order' => 13],
        
        // Quản lý xưởng
        'xemcongviec' => ['icon' => 'fa-tasks', 'text' => 'Xem công việc', 'order' => 10],
        'tao-yeu-cau-nvl' => ['icon' => 'fa-clipboard-list', 'text' => 'Yêu cầu cung cấp NVL', 'order' => 11],
        'ghi-nhan-tp' => ['icon' => 'fa-edit', 'text' => 'Ghi nhận thành phẩm', 'order' => 13],
        
        // Nhân viên QC
        'nhap-kho-tp-da-check-qc' => ['icon' => 'fa-check-square', 'text' => 'Nhập kho TP (đã QC)', 'order' => 10],
        
        // Quản lý kho TP
        'xuatthanhpham' => ['icon' => 'fa-truck', 'text' => 'Xuất thành phẩm', 'order' => 11],
        'thongke_sanpham' => ['icon' => 'fa-chart-pie', 'text' => 'Thống kê kho TP', 'order' => 12],
        
        // Công nhân
        'xem-lich-lam-viec' => ['icon' => 'fa-calendar', 'text' => 'Xem lịch làm việc', 'order' => 10],
        'calamviec' => ['icon' => 'fa-calendar', 'text' => 'Xem lịch làm việc', 'order' => 10],
        'baocaosuco' => ['icon' => 'fa-exclamation-triangle', 'text' => 'Báo cáo sự cố', 'order' => 11],
    ];
    
    /**
     * Danh sách quyền theo vai trò
     * Format: 'vai_tro' => ['page1', 'page2', ...]
     */
    private static $permissions = [
        // 1. GIÁM ĐỐC - Có quyền làm TẤT CẢ
        'Giám đốc' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            // Tổng quan & Báo cáo
            'bao-cao-tong-hop',
            'ajax-get-report-details',
            'lap-bao-cao',
            'xem-bao-cao',
            'phe-duyet-ke-hoach-sx',
            'phe-duyet-ke-hoach-sx-process',
            'ajax-get-approval-history',
            'ajax-get-plan-detail',
            // Quản lý nhân sự
            'themnhanvien',
            'xemnhanvien',
            'suanhanvien',
            'suathongtinnv',
            'capnhatnv',
            'xoanhanvien',
            'timkiem-nhanvien',
            'xacnhan-xoa-nhanvien',
            // Quản lý sản xuất
            'tao-don-hang-san-xuat',
            'luu-don-hang-san-xuat',
            'lap-khsx',
            'lap-ke-hoach',
            'lap-ke-hoach-chi-tiet',
            'luu-ke-hoach',
            'ajax-tim-kiem',
            'ajax-get-modal-data',
            'chitietkhxs',
            'phe-duyet-cac-yeu-cau',
            'chi-tiet-phe-duyet-yeu-cau',
            'duyet-phieu',
            'tuchoi-phieu',
            // Kho NVL
            'tao-yeu-cau-nhap-kho',
            'tao-yeu-cau-nhap-nguyen-vat-lieu',
            'chi-tiet-yeu-cau-nhap-kho',
            'luu-yeu-cau-nhap-kho',
            'luu-phieu-nhap-kho',
            'nhap-kho-nvl',
            'ajax-get-details-nhapkho',
            'luu-phieu-nhap-nvl',
            'xuat-kho-nvl',
            'chi-tiet-xuat-kho',
            'luu-phieu-xuat-kho',
            'thongke-khonvl',
            'xuatcsv-thongkenvl',
            // Xưởng sản xuất
            'xemcongviec',
            'tao-yeu-cau-nvl',
            'chi-tiet-yeu-cau',
            'luu-yeu-cau-nvl',
            'tao-yeu-cau-kiem-tra-chat-luong',
            'tao-yeu-cau-kiem-tra-chat-luong-create',
            'tao-yeu-cau-kiem-tra-chat-luong-process',
            'ghi-nhan-tp',
            'luu-ghi-nhan-tp',
            'ajax-get-sp-theo-khsx',
            'ajax-get-nv-theo-xuong',
            'ajax-get-chitiet-phieu',
            // QC
            'cap-nhat-thanh-pham',
            'qc-update',
            'nhap-kho-thanh-pham',
            'luu-nhap-kho-tp-da-check-qc',
            'baocao-chatluong',
            'baocao-get',
            'baocao-export',
            // Kho TP
            'xuatthanhpham',
            'xuatthanhpham_xuat',
            'xuatthanhpham_chitiet',
            'thongke',
            'export_thongke',
            // Công nhân
            'calamviec',
            'xem-lich-lam-viec',
            'lichlamviec',
            'baocaosuco',
            'luu-baocaosuco',
            'logout'
        ],
        
        // 2. QUẢN LÝ NHÂN SỰ - Thêm/xóa/sửa/xem nhân viên
        'Quản lý nhân sự' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            'themnhanvien',
            'xemnhanvien',
            'suanhanvien',
            'suathongtinnv',
            'capnhatnv',
            'xoanhanvien',
            'timkiem-nhanvien',
            'xacnhan-xoa-nhanvien',
            'logout'
        ],
        
        // 3. QUẢN LÝ SẢN XUẤT - Tạo đơn hàng, lập KHSX, phê duyệt, xem báo cáo
        'Quản lý sản xuất' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            // Quản lý sản xuất
            'tao-don-hang-san-xuat',
            'luu-don-hang-san-xuat',
            'lap-khsx',
            'lap-ke-hoach',
            'lap-ke-hoach-chi-tiet',
            'luu-ke-hoach',
            'ajax-tim-kiem',
            'ajax-get-modal-data',
            'chitietkhxs',
            'phe-duyet-cac-yeu-cau',
            'chi-tiet-phe-duyet-yeu-cau',
            'duyet-phieu',
            'tuchoi-phieu',
            'duyet-yc-nhap-kho',
            'tu-choi-yc-nhap-kho',
            'chi-tiet-yc-nhap-kho',
            // Báo cáo
            'bao-cao-tong-hop',
            'ajax-get-report-details',
            'logout'
        ],
        
        // 4. QUẢN LÝ XƯỞNG - Xem công việc, Tạo yêu cầu NVL, Yêu cầu kiểm tra QC, Ghi nhận TP
        'Quản lý xưởng' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            // Xưởng sản xuất
            'xemcongviec',
            'tao-yeu-cau-nvl',
            'chi-tiet-yeu-cau',
            'luu-yeu-cau-nvl',
            'tao-yeu-cau-kiem-tra-chat-luong',
            'tao-yeu-cau-kiem-tra-chat-luong-create',
            'tao-yeu-cau-kiem-tra-chat-luong-process',
            'ghi-nhan-tp',
            'luu-ghi-nhan-tp',
            'ajax-get-sp-theo-khsx',
            'ajax-get-nv-theo-xuong',
            'ajax-get-chitiet-phieu',
            'chitietkhxs',
            'logout'
        ],
        
        // 5. QUẢN LÝ KHO NVL - Nhập/xuất/thống kê kho NVL
        'Quản lý kho NVL' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            'tao-yeu-cau-nhap-kho',
            'tao-yeu-cau-nhap-nguyen-vat-lieu',
            'chi-tiet-yeu-cau-nhap-kho',
            'luu-yeu-cau-nhap-kho',
            'luu-phieu-nhap-kho',
            'nhap-kho-nvl',
            'ajax-get-details-nhapkho',
            'luu-phieu-nhap-nvl',
            'xuat-kho-nvl',
            'chi-tiet-xuat-kho',
            'luu-phieu-xuat-kho',
            'thongke-khonvl',
            'xuatcsv-thongkenvl',
            'logout'
        ],
        
        // 6. NHÂN VIÊN QC - Cập nhật thành phẩm, báo cáo chất lượng
        'Nhân viên QC' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            'cap-nhat-thanh-pham',
            'qc-update',
            'baocao-chatluong',
            'baocao-get',
            'baocao-export',
            'lap-bao-cao',
            'logout'
        ],
        
        // 7. QUẢN LÝ KHO TP - Nhập/xuất/thống kê kho thành phẩm
        'Quản lý kho TP' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            'nhap-kho-thanh-pham',
            'luu-nhap-kho-tp-da-check-qc',
            'xuatthanhpham',
            'xuatthanhpham_xuat',
            'xuatthanhpham_chitiet',
            'thongke',
            'export_thongke',
            'logout'
        ],
        
        // 8. CÔNG NHÂN - Xem lịch làm việc, báo cáo sự cố
        'Công nhân' => [
            'home',
            'thong-tin-ca-nhan',
            'profile',
            'gioithieu',
            'calamviec',
            'xem-lich-lam-viec',
            'lichlamviec',
            'baocaosuco',
            'luu-baocaosuco',
            'logout'
        ]
    ];

    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     */
    public static function checkLogin() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }
    }

    /**
     * Kiểm tra quyền truy cập trang
     * @param string $page Tên trang đang truy cập
     * @return bool
     */
    public static function checkPermission($page) {
        self::checkLogin();
        
        // Lấy vai trò từ session
        $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Công nhân';
        
        // Kiểm tra vai trò có trong danh sách không
        if (!isset(self::$permissions[$vaiTro])) {
            return false;
        }
        
        // Kiểm tra trang có trong quyền của vai trò không
        return in_array($page, self::$permissions[$vaiTro]);
    }
    
    /**
     * Lấy danh sách menu cho vai trò hiện tại
     * @return array Danh sách menu items đã sắp xếp
     */
    public static function getMenuForCurrentRole() {
        // Không gọi checkLogin() để tránh redirect loop khi include sidebar
        if (!isset($_SESSION['user'])) {
            return []; // Trả về menu rỗng nếu chưa login
        }
        
        $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Công nhân';
        
        // Lấy danh sách quyền của vai trò
        $allowedPages = self::$permissions[$vaiTro] ?? [];
        
        // Tạo menu từ các quyền
        $menu = [];
        foreach ($allowedPages as $page) {
            // Bỏ qua các route không cần hiển thị trên menu
            $skipRoutes = ['logout', 'ajax-', 'luu-', 'capnhat', 'xacnhan-', 'timkiem-', 'process'];
            $shouldSkip = false;
            foreach ($skipRoutes as $skip) {
                if (strpos($page, $skip) !== false) {
                    $shouldSkip = true;
                    break;
                }
            }
            if ($shouldSkip) continue;
            
            // Nếu có mapping cho route này
            if (isset(self::$routeMenuMap[$page])) {
                $menu[] = [
                    'link' => $page,
                    'icon' => self::$routeMenuMap[$page]['icon'],
                    'text' => self::$routeMenuMap[$page]['text'],
                    'order' => self::$routeMenuMap[$page]['order']
                ];
            }
        }
        
        // Loại bỏ trùng lặp (cùng text)
        $uniqueMenu = [];
        $seenTexts = [];
        foreach ($menu as $item) {
            if (!in_array($item['text'], $seenTexts)) {
                $uniqueMenu[] = $item;
                $seenTexts[] = $item['text'];
            }
        }
        
        // Sắp xếp theo thứ tự
        usort($uniqueMenu, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        return $uniqueMenu;
    }

    /**
     * Chặn truy cập nếu không có quyền
     * @param string $page Tên trang đang truy cập
     */
    public static function requirePermission($page) {
        if (!self::checkPermission($page)) {
            $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Không xác định';
            echo "<script>
                alert('⛔ BẠN KHÔNG CÓ QUYỀN TRUY CẬP CHỨC NĂNG NÀY!\\n\\nVai trò của bạn: {$vaiTro}');
                window.location.href = 'index.php?page=home';
            </script>";
            exit();
        }
    }

    /**
     * Kiểm tra vai trò cụ thể
     * @param string|array $roles Vai trò cần kiểm tra (string hoặc array)
     * @return bool
     */
    public static function hasRole($roles) {
        self::checkLogin();
        
        $userRole = $_SESSION['user']['vaiTro'] ?? '';
        
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        
        return $userRole === $roles;
    }

    /**
     * Lấy danh sách quyền của vai trò hiện tại
     * @return array
     */
    public static function getCurrentPermissions() {
        self::checkLogin();
        
        $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Công nhân';
        
        return self::$permissions[$vaiTro] ?? [];
    }

    /**
     * Kiểm tra có phải admin (Giám đốc hoặc Quản lý sản xuất)
     * @return bool
     */
    public static function isAdmin() {
        return self::hasRole(['Giám đốc', 'Quản lý sản xuất']);
    }

    /**
     * ✅ Mapping danh mục với các vai trò được phép truy cập
     * Key: danh mục từ URL (tong-quan, nhan-su, ...)
     * Value: mảng các vai trò được phép xem danh mục đó
     */
    private static $categoryPermissions = [
        'tong-quan' => ['Giám đốc', 'Quản lý sản xuất'],
        'nhan-su' => ['Giám đốc', 'Quản lý nhân sự'],
        'san-xuat' => ['Giám đốc', 'Quản lý sản xuất'],
        'kho-nvl' => ['Giám đốc', 'Quản lý kho NVL'],
        'xuong' => ['Giám đốc', 'Quản lý xưởng'],
        'kiem-tra-chat-luong' => ['Giám đốc', 'Nhân viên QC'],
        'kho-thanh-pham' => ['Giám đốc', 'Quản lý kho TP'],
        'cong-nhan' => ['Giám đốc', 'Công nhân'],
    ];

    /**
     * ✅ Kiểm tra quyền truy cập danh mục
     * @param string $category Tên danh mục (tong-quan, nhan-su, ...)
     * @return bool
     */
    public static function canAccessCategory($category) {
        if (!isset($_SESSION['user'])) {
            return false;
        }

        $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Công nhân';
        
        // Nếu không có cấu hình cho danh mục này, cho phép tất cả
        if (!isset(self::$categoryPermissions[$category])) {
            return true;
        }

        // Kiểm tra vai trò có trong danh sách được phép không
        return in_array($vaiTro, self::$categoryPermissions[$category]);
    }

    /**
     * ✅ Chặn truy cập danh mục nếu không có quyền (hiển thị modal)
     * @param string $category Tên danh mục
     */
    public static function requireCategoryAccess($category) {
        if (!self::canAccessCategory($category)) {
            $vaiTro = $_SESSION['user']['vaiTro'] ?? 'Không xác định';
            $categoryName = self::getCategoryName($category);
            
            echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Không có quyền truy cập</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'>
    <style>
        .permission-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        .modal-icon {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .modal-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .modal-message {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .modal-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .modal-button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class='permission-modal'>
        <div class='modal-content'>
            <div class='modal-icon'>
                <i class='fas fa-lock'></i>
            </div>
            <div class='modal-title'> KHÔNG CÓ QUYỀN TRUY CẬP</div>
            <div class='modal-message'>
                Bạn không có quyền truy cập danh mục <strong>{$categoryName}</strong>.<br>
                <br>
                <strong>Vai trò của bạn:</strong> {$vaiTro}<br>
                Vui lòng liên hệ quản trị viên nếu bạn cần quyền truy cập.
            </div>
            <button class='modal-button' onclick='window.location.href=\"index.php?page=home\"'>
                <i class='fas fa-home'></i> Về trang chủ
            </button>
        </div>
    </div>
</body>
</html>";
            exit();
        }
    }

    /**
     * ✅ Lấy tên tiếng Việt của danh mục (private)
     */
    private static function getCategoryName($category) {
        $names = [
            'tong-quan' => 'Tổng quan & Báo cáo',
            'nhan-su' => 'Quản lý Nhân sự',
            'san-xuat' => 'Quản lý Sản xuất',
            'kho-nvl' => 'Kho Nguyên vật liệu',
            'xuong' => 'Xưởng sản xuất',
            'kiem-tra-chat-luong' => 'Kiểm tra chất lượng',
            'kho-thanh-pham' => 'Kho Thành phẩm',
            'cong-nhan' => 'Công việc & Báo cáo',
        ];
        return $names[$category] ?? $category;
    }

    /**
     * ✅ Lấy tên tiếng Việt của danh mục (public - dùng cho API)
     */
    public static function getCategoryNamePublic($category) {
        return self::getCategoryName($category);
    }
}
