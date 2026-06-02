<?php

namespace App\Controllers;

class GioiThieuController {
    public function show($danhMuc) {
        $allowed = ['tong-quan', 'nhan-su', 'san-xuat', 'kho-nvl', 'xuong', 'kiem-tra-chat-luong', 'kho-thanh-pham', 'cong-nhan'];
        
        if (!in_array($danhMuc, $allowed)) {
            http_response_code(404);
            echo "Trang không tồn tại";
            return;
        }

        // Chuẩn hóa tên view
        $viewFile = str_replace('-', '', $danhMuc); // "san-xuat" → "sanxuat"
        $viewPath = __DIR__ . "/../../views/gioi-thieu/{$viewFile}.php";

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "Nội dung giới thiệu chưa được tạo.";
            return;
        }

        // Truyền dữ liệu nếu cần
        $title = $this->getTitle($danhMuc);
        include __DIR__ . "/../../views/layouts/main.php";
    }

    private function getTitle($danhMuc) {
        $titles = [
            'tong-quan' => 'Giới thiệu tổng quan',
            'nhan-su' => 'Giới thiệu Nhân sự',
            'san-xuat' => 'Giới thiệu Sản xuất',
            'kho-nvl' => 'Giới thiệu Kho Nguyên Vật Liệu',
            'xuong' => 'Giới thiệu Xưởng',
            'kiem-tra-chat-luong' => 'Giới thiệu Kiểm tra Chất lượng',
            'kho-thanh-pham' => 'Giới thiệu Kho Thành Phẩm',
            'cong-nhan' => 'Giới thiệu Công nhân'
        ];
        return $titles[$danhMuc] ?? 'Giới thiệu';
    }
}