<?php
require_once 'ketNoi.php';

class PheDuyetKeHoachSXModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    // ✅ 1. Lấy danh sách kế hoạch sản xuất chờ duyệt
    public function getPendingPlans() {
        $sql = "SELECT maKHSX, tenKHSX, thoiGianBatDau, thoiGianKetThuc, trangThai
        FROM kehoachsanxuat
        WHERE trangThai = 'Chờ duyệt'
        ORDER BY thoiGianBatDau DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ 2. Lấy chi tiết kế hoạch (sản phẩm, NVL, xưởng)
    public function getPlanDetails($maKHSX) {
        $sql = "SELECT 
                    k.maKHSX, 
                    k.maDonHang, 
                    k.thoiGianBatDau AS ngayBatDau, 
                    k.thoiGianKetThuc AS ngayKetThuc, 
                    d.tenDonHang,
                    d.tenSanPham,
                    d.soLuongSanXuat,
                    s.maSanPham
                FROM kehoachsanxuat k
                LEFT JOIN donhangsanxuat d ON k.maDonHang = d.maDonHang
                LEFT JOIN san_pham s ON d.maSanPham = s.maSanPham
                WHERE k.maKHSX = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $maKHSX);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$data) {
            return null;
        }

        // ✅ Lấy chi tiết nguyên vật liệu cho kế hoạch (hiển thị tất cả xưởng được phân công)
        $sqlNVL = "SELECT 
                        c.maNVL, 
                        c.tenNVL, 
                        c.loaiNVL,
                        c.maXuong,
                        x.tenXuong,
                        n.donViTinh, 
                        n.soLuongTonKho, 
                        c.soLuongNVL AS soLuongCan,
                        CASE 
                            WHEN n.soLuongTonKho >= c.soLuongNVL THEN 'Đủ kho'
                            ELSE CONCAT('Thiếu ', c.soLuongNVL - n.soLuongTonKho)
                        END AS ghiChu
                    FROM chitietkehoachsanxuat c
                    JOIN nvl n ON c.maNVL = n.maNVL
                    LEFT JOIN xuong x ON c.maXuong = x.maXuong
                    WHERE c.maKHSX = ?
                    ORDER BY c.maXuong, c.maNVL";
        
        $stmtNVL = $this->conn->prepare($sqlNVL);
        $stmtNVL->bind_param('i', $maKHSX);
        $stmtNVL->execute();
        $result = $stmtNVL->get_result();
        
        $data['nguyenVatLieu'] = $result->fetch_all(MYSQLI_ASSOC);
        
        // Lấy danh sách tất cả xưởng được phân công
        $workshopList = [];
        foreach ($data['nguyenVatLieu'] as $nvl) {
            if (!empty($nvl['tenXuong']) && !in_array($nvl['tenXuong'], $workshopList)) {
                $workshopList[] = $nvl['tenXuong'];
            }
        }
        $data['tenXuong'] = !empty($workshopList) ? implode(', ', $workshopList) : '—';
        
        $stmtNVL->close();

        return $data;
    }

    public function updatePlanStatus($maKHSX, $trangThai, $ghiChu = '') {
        // Chỉ cập nhật trạng thái, không cập nhật ghiChu vì bảng kehoachsanxuat không có cột này
        $sql = "UPDATE kehoachsanxuat SET trangThai = ? WHERE maKHSX = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Lỗi prepare SQL: " . $this->conn->error);
        }

        $stmt->bind_param("si", $trangThai, $maKHSX);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Lỗi khi cập nhật kế hoạch: " . $error);
        }

        // Kiểm tra có bản ghi nào được update
        $affected = $stmt->affected_rows;
        $stmt->close();
        
        if ($affected === 0) {
            throw new Exception("Không tìm thấy kế hoạch với mã: " . $maKHSX);
        }

        return true;
    }

    public function addApprovalHistory($maKHSX, $hanhDong, $ghiChu, $nguoiThucHien) {
        $sql = "INSERT INTO lichsupheduyet (maKHSX, hanhDong, ghiChu, nguoiThucHien) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Lỗi prepare SQL lichsupheduyet: " . $this->conn->error);
        }
        
        $stmt->bind_param("isss", $maKHSX, $hanhDong, $ghiChu, $nguoiThucHien);
        
        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            throw new Exception("Lỗi khi lưu lịch sử: " . $error);
        }
        
        $stmt->close();
        return true;
    }

public function getApprovalHistory($maKHSX) {
    $sql = "SELECT hanhDong, ghiChu, nguoiThucHien, thoiGian 
            FROM lichsupheduyet 
            WHERE maKHSX = ? 
            ORDER BY thoiGian DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $maKHSX);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


}
?>
