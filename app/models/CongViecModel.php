<?php
require_once 'app/models/ketNoi.php';

class CongViecModel
{
    private $conn;

    public function __construct()
    {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    // KHSX đã duyệt
    public function getApprovedPlans()
    {
        $sql = "SELECT maKHSX, tenKHSX, thoiGianBatDau, thoiGianKetThuc
                FROM kehoachsanxuat
                WHERE trangThai = 'Đã duyệt'
                ORDER BY maKHSX DESC";

        $rs = $this->conn->query($sql);
        return $rs ? $rs->fetch_all(MYSQLI_ASSOC) : [];
    }

    // KHSX + người lập
    public function getPlanById($maKHSX)
    {
        $sql = "SELECT 
                    kh.tenKHSX,
                    kh.thoiGianBatDau,
                    kh.thoiGianKetThuc,
                    nd.hoTen AS tenNguoiLap
                FROM kehoachsanxuat kh
                JOIN nguoidung nd ON kh.maND = nd.maND
                WHERE kh.maKHSX = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // 👉 ĐƠN HÀNG (BỔ SUNG MỚI)
    public function getDonHangByKHSX($maKHSX)
    {
        $sql = "SELECT 
                    dh.maDonHang,
                    dh.tenDonHang,
                    dh.tenSanPham,
                    dh.soLuongSanXuat,
                    dh.ngayGiao
                FROM donhangsanxuat dh
                JOIN kehoachsanxuat kh ON dh.maDonHang = kh.maDonHang
                WHERE kh.maKHSX = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Chi tiết KHSX
    public function getChiTietByKHSX($maKHSX)
    {
        $sql = "SELECT
                    maXuong,
                    tenNVL,
                    soLuongNVL,
                    ngayBatDau,
                    ngayKetThuc,
                    KPI,
                    dinhMuc
                FROM chitietkehoachsanxuat
                WHERE maKHSX = ?
                ORDER BY maXuong ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
