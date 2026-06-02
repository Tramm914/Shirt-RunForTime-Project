<?php
require_once 'ketNoi.php';

class HomeModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    public function layThongKeTongQuan() {
        $data = [];
        $data['tongKHSX'] = $this->getCount("SELECT COUNT(*) FROM kehoachsanxuat");
        $data['tongNVL'] = $this->getCount("SELECT COUNT(*) FROM nvl");
        $data['tongDonHang'] = $this->getCount("SELECT COUNT(*) FROM donhangsanxuat");
        $data['tongThietBi'] = $this->getCount("SELECT COUNT(*) FROM thietbi");
        $data['daDuyet'] = $this->getCount("SELECT COUNT(*) FROM kehoachsanxuat WHERE trangThai='Đã duyệt'");
        $data['choDuyet'] = $this->getCount("SELECT COUNT(*) FROM kehoachsanxuat WHERE trangThai='Chờ duyệt'");
        return $data;
    }

    private function getCount($sql) {
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_row()[0] : 0;
    }

    // 🔹 Lấy dữ liệu cho biểu đồ năng suất theo ngày
    public function layNangSuatTheoNgay($soNgay = 7) {
    $sql = "
        SELECT 
            DATE(ngayLam) AS ngay,
            SUM(soLuongSPHoanThanh) AS tongSoLuong
        FROM ghinhanthanhphamtheongay
        WHERE ngayLam >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE(ngayLam)
        ORDER BY ngay ASC
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $soNgay);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}
    // ✅ Lấy KHSX đang chạy
// ✅ KHSX đang triển khai
public function layKHSXDangTrienKhai() {
    $sql = "
        SELECT 
            tenKHSX,
            maDonHang,
            thoiGianBatDau,
            thoiGianKetThuc,
            trangThai
        FROM kehoachsanxuat
        WHERE trangThai IN ('Đang sản xuất', 'Đã duyệt')
        ORDER BY thoiGianBatDau DESC
        LIMIT 10
    ";
    return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

// ✅ KHSX đã thực hiện
public function layKHSXDaThucHien() {
    $sql = "
        SELECT 
            tenKHSX,
            maDonHang,
            thoiGianBatDau,
            thoiGianKetThuc,
            trangThai
        FROM kehoachsanxuat
        WHERE trangThai IN ('Hoàn thành', 'Đã kết thúc')
        ORDER BY thoiGianKetThuc DESC
        LIMIT 10
    ";
    return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}


// ✅ Lấy tồn kho NVL để vẽ biểu đồ
public function layTonKhoNVL() {
    $sql = "
        SELECT tenNVL, soLuongTonKho
        FROM nvl
        ORDER BY soLuongTonKho DESC
        LIMIT 5
    ";
    return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

}
