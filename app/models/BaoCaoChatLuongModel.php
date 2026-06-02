<?php
require_once 'ketNoi.php';

class BaoCaoChatLuongModel
{
    private $conn;

    public function __construct()
    {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    // Lấy danh sách báo cáo đã kiểm tra
    public function getAllReports()
    {
        $sql = "SELECT 
                    c.maYC,
                    c.tenSanPham,
                    c.soLuong,
                    c.soLuongDat,
                    c.soLuongHong,
                    p.ngayLap,
                    p.tenNguoiLap
                FROM chitietphieuyeucaukiemtrachatluong c
                JOIN phieuyeucaukiemtrachatluong p ON c.maYC = p.maYC
                WHERE c.trangThaiSanPham = 'Đã kiểm tra'
                ORDER BY p.ngayLap  DESC";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log("Lỗi SQL getAllReports: " . $this->conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy chi tiết 1 báo cáo
    public function getReportDetail($maYC)
{
    $sql = "SELECT 
                c.*,
                p.ngayLap,
                p.tenNguoiLap
            FROM chitietphieuyeucaukiemtrachatluong c
            JOIN phieuyeucaukiemtrachatluong p ON c.maYC = p.maYC
            WHERE c.maYC = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $maYC);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

}
