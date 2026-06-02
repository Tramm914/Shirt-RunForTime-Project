<?php
class DanhMucModel {
    public function getDanhMuc() {
        return [
            "Tổng quan" => [
                ["name" => "Đăng nhập", "route" => "login"],
                ["name" => "Đăng xuất", "route" => "logout"],
                ["name" => "Thông tin cá nhân", "route" => "thong-tin-ca-nhan"],
                ["name" => "Báo cáo tổng hợp", "route" => "lap-bao-cao"],
            ],
            "Nhân sự" => [
                ["name" => "Thêm nhân viên", "route" => "themnhanvien"],
                ["name" => "Xem nhân viên", "route" => "xemnhanvien"],
                ["name" => "Xóa nhân viên", "route" => "xoanhanvien"],
                ["name" => "Sửa nhân viên", "route" => "suanhanvien"],
            ],
            "Sản xuất" => [
                ["name" => "Tạo đơn hàng sản xuất", "route" => "tao-don-hang-san-xuat"],
                ["name" => "Lập kế hoạch sản xuất", "route" => "lap-ke-hoach"],
                ["name" => "Duyệt kế hoạch sản xuất", "route" => "phe-duyet-ke-hoach-sx"],
            ],
            "Kho NVL" => [
                ["name" => "Tạo yêu cầu nhập NVL", "route" => "tao-yeu-cau-nhap-kho"],
                ["name" => "Nhập kho NVL", "route" => "nhap-kho-nvl"],
                ["name" => "Xuất NVL", "route" => "xuat-kho-nvl"],
                ["name" => "Thống kê kho NVL", "route" => "thongke-khonvl"],
            ],
            "Xưởng" => [
                ["name" => "Xem công việc", "route" => "xemcongviec"],
                ["name" => "Theo dõi tiến độ", "route" => "theo-doi-tien-do"],
                ["name" => "Yêu cầu NVL", "route" => "tao-yeu-cau-nvl"],
                ["name" => "Yêu cầu kiểm tra chất lượng", "route" => "tao-yeu-cau-kiem-tra-chat-luong"],
            ],
            "QC" => [
                ["name" => "Cập nhật thành phẩm", "route" => "cap-nhat-thanh-pham"],
                ["name" => "Báo cáo chất lượng", "route" => "bao-cao-chat-luong"],
            ],
            "Kho TP" => [
                ["name" => "Nhập kho TP", "route" => "nhap-kho-tp-da-check-qc"],
                ["name" => "Xuất kho TP", "route" => "xuatthanhpham"],
                ["name" => "Thống kê kho TP", "route" => "thong-ke-kho-tp"],
            ],
            "Công nhân" => [
                ["name" => "Lịch làm việc", "route" => "xem-lich-lam-viec"],
                ["name" => "Báo cáo sự cố", "route" => "baocaosuco"],
            ]
        ];
    }
}
