<?php
require_once 'ketNoi.php';

class YeuCauKiemTraChatLuongModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    // 🔹 Lấy danh sách kế hoạch từ đơn hàng "Hoàn thành" (chưa có phiếu KTCL)
    // Thời hạn kiểm tra = ngayGiao của đơn hàng + 3 ngày
    public function getApprovedPlans() {
        // Lấy kế hoạch dựa trên trạng thái của chính kehoachsanxuat = 'Hoàn thành'
        // Thời hạn kiểm tra mặc định = thoiGianKetThuc (kế hoạch) + 3 ngày
        $sql = "SELECT kh.maKHSX, kh.tenKHSX, kh.thoiGianBatDau, kh.thoiGianKetThuc AS ngayKetThuc,
                       sp.maSanPham, sp.tenSanPham, dh.soLuongSanXuat, dh.tenDonHang,
                       kh.trangThai AS trangThaiKHSX,
                       DATE_ADD(kh.thoiGianKetThuc, INTERVAL 3 DAY) as thoiHanKiemTraMacDinh
                FROM kehoachsanxuat kh
                JOIN donhangsanxuat dh ON kh.maDonHang = dh.maDonHang
                JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
                WHERE kh.trangThai = 'Hoàn thành'
                  AND kh.maKHSX NOT IN (
                      SELECT DISTINCT maKHSX
                      FROM phieuyeucaukiemtrachatluong
                      WHERE maKHSX IS NOT NULL
                  )
                ORDER BY kh.maKHSX DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    // 🔹 Lấy thông tin sản phẩm từ kế hoạch sản xuất
    // Thời hạn kiểm tra = ngayGiao của đơn hàng + 3 ngày
    public function getProductByPlan($maKHSX) {
        // Trả về thông tin sản phẩm + ngày kết thúc kế hoạch (ngayKetThuc)
        // và thời hạn kiểm tra mặc định = ngayKetThuc + 3 ngày
        $sql = "SELECT kh.maKHSX, kh.tenKHSX, kh.thoiGianKetThuc AS ngayKetThuc,
                       sp.maSanPham, sp.tenSanPham, sp.donVi,
                       dh.soLuongSanXuat, dh.tenDonHang,
                       kh.trangThai AS trangThaiKHSX,
                       DATE_ADD(kh.thoiGianKetThuc, INTERVAL 3 DAY) as thoiHanKiemTraMacDinh
                FROM kehoachsanxuat kh
                JOIN donhangsanxuat dh ON kh.maDonHang = dh.maDonHang
                JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
                WHERE kh.maKHSX = ? AND kh.trangThai = 'Hoàn thành'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    // 🔹 Thêm phiếu yêu cầu kiểm tra chất lượng
    public function themPhieuYeuCau($tenNguoiLap, $tenPhieu, $maSanPham, $maKHSX, $thoiHanHoanThanh = null) {
        // Lấy maND từ session (không gọi session_start() ở đây)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $maND = isset($_SESSION['user']['maND']) ? $_SESSION['user']['maND'] : 1;
        
        $ngayLap = date('Y-m-d');
        $trangThai = 'Chờ duyệt'; // ✅ String "Chờ duyệt" không phải số 0
        
        // Nếu không có thời hạn, mặc định là 3 ngày sau
        if (!$thoiHanHoanThanh) {
            $thoiHanHoanThanh = date('Y-m-d', strtotime('+3 days'));
        }
        
        $sql = "INSERT INTO phieuyeucaukiemtrachatluong 
                (tenPhieu, maSanPham, trangThai, ngayLap, tenNguoiLap, maND, maKHSX, thoiHanHoanThanh)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        // ✅ Đảm bảo bind_param đúng: s=string, i=int, s=string, s=string (ngayLap), s=string, i=int, i=int, s=string
        $stmt->bind_param("sisssiis", $tenPhieu, $maSanPham, $trangThai, $ngayLap, $tenNguoiLap, $maND, $maKHSX, $thoiHanHoanThanh);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }


    // 🔹 Thêm chi tiết phiếu yêu cầu kiểm tra
    public function themChiTietPhieu($maYC, $maSanPham, $tenSanPham, $soLuong, $donVi) {
        $trangThaiSanPham = 'Chờ kiểm tra';
        
        $sql = "INSERT INTO chitietphieuyeucaukiemtrachatluong 
                (maYC, maSanPham, tenSanPham, soLuong, donViTinh, trangThaiSanPham)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisiss", $maYC, $maSanPham, $tenSanPham, $soLuong, $donVi, $trangThaiSanPham);
        return $stmt->execute();
    }
}
?>