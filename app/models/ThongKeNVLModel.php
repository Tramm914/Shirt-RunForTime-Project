<?php
include_once 'ketNoi.php';

class ThongKeNVLModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    /**
     * Lấy thống kê kho NVL theo khoảng thời gian, tên NVL, loại (nhap/xuat)
     * Sử dụng:
     *  - phieunhapnvl: tổng nhập
     *  - chitietphieuxuatnvl: tổng xuất
     */
    public function layThongKeKhoNVL($start, $end, $tenNVL = '', $loai = '') {
        if (empty($start) || empty($end)) {
            return [];
        }

        $query = "
            SELECT 
                nvl.maNVL,
                nvl.tenNVL,
                nvl.donViTinh,
                -- Tổng nhập (từ phieunhapnvl)
                COALESCE(nhap.tongNhap, 0) AS tongNhap,
                -- Tổng xuất (từ chitietphieuxuatnvl)
                COALESCE(xuat.tongXuat, 0) AS tongXuat,
                nvl.soLuongTonKho AS tonKho
            FROM nvl
            LEFT JOIN (
                SELECT maNVL, SUM(soLuongNhap) AS tongNhap
                FROM phieunhapnvl
                WHERE ngayNhap BETWEEN ? AND ?
                GROUP BY maNVL
            ) AS nhap ON nhap.maNVL = nvl.maNVL
            LEFT JOIN (
                SELECT c.maNVL, SUM(c.soLuong) AS tongXuat
                FROM chitietphieuxuatnvl c
                JOIN phieuxuatnvl p ON p.maPhieu = c.maPhieu
                WHERE p.ngayLap BETWEEN ? AND ?
                GROUP BY c.maNVL
            ) AS xuat ON xuat.maNVL = nvl.maNVL
            WHERE 1=1
        ";

        $types = "ssss";
        $params = [$start, $end, $start, $end];

        // Lọc theo tên NVL
        if (!empty($tenNVL)) {
            $query .= " AND nvl.tenNVL LIKE ?";
            $types .= "s";
            $params[] = "%{$tenNVL}%";
        }

        $query .= " GROUP BY nvl.maNVL, nvl.tenNVL, nvl.donViTinh, nvl.soLuongTonKho";

        // Lọc theo loại báo cáo
        if ($loai === 'nhap') {
            $query .= " HAVING (COALESCE(tongNhap,0)) > 0";
        } elseif ($loai === 'xuat') {
            $query .= " HAVING (COALESCE(tongXuat,0)) > 0";
        }

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die('❌ Lỗi prepare: ' . $this->conn->error . "<br>⚠️ SQL: " . $query);
        }

        $stmt->bind_param($types, ...$params);
        if (!$stmt->execute()) {
            die('❌ Lỗi execute: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['tongNhap'] = (int)$row['tongNhap'];
            $row['tongXuat'] = (int)$row['tongXuat'];
            $row['tonKho'] = (int)$row['tonKho'];
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    // Xuất CSV toàn bộ NVL
    // public function layTatCaKhoNVL() {
    //     $sql = "SELECT maNVL, tenNVL, donViTinh, soLuongTonKho AS tonKho FROM nvl";
    //     $result = $this->conn->query($sql);
    //     $data = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $data[] = $row;
    //     }
    //     return $data;
    // }
}
?>
