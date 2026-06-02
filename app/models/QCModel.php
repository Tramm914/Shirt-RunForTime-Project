<?php
require_once 'ketNoi.php';

class QCModel
{
    private $conn;

    public function __construct()
    {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    public function getDanhSachCanKiemTra()
    {
        $sql = "SELECT 
                p.maYC, 
                ct.tenSanPham, 
                ct.soLuong AS tongSoLuong, 
                p.tenNguoiLap, 
                p.trangThai
            FROM phieuyeucaukiemtrachatluong p
            JOIN chitietphieuyeucaukiemtrachatluong ct 
                ON p.maYC = ct.maYC
            WHERE 
                p.trangThai IN ('Chờ kiểm tra', 'Đã duyệt')
                AND (ct.trangThaiSanPham IS NULL 
                     OR ct.trangThaiSanPham <> 'Đã kiểm tra')
            ORDER BY p.maYC DESC";

        $result = $this->conn->query($sql);

        if (!$result) {
            error_log("Lỗi SQL getDanhSachCanKiemTra: " . $this->conn->error);
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getChiTietPhieu($maYC)
    {
        $sql = "SELECT * FROM phieuyeucaukiemtrachatluong WHERE maYC = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maYC);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function capNhatKetQuaQC($maYC, $slDat, $slHong, $ghiChu, $nguoiKiemTra)
    {
        $sqlChiTiet = "UPDATE chitietphieuyeucaukiemtrachatluong 
                       SET soLuongDat = ?, 
                           soLuongHong = ?, 
                           ghiChu = ?, 
                           ngayKiemTra = NOW(),
                           trangThaiSanPham = 'Đã kiểm tra'
                       WHERE maYC = ?";

        $stmt1 = $this->conn->prepare($sqlChiTiet);
        if (!$stmt1)
            return false;
        $stmt1->bind_param("iisi", $slDat, $slHong, $ghiChu, $maYC);
        $kqChiTiet = $stmt1->execute();

        if ($kqChiTiet) {
            $sqlPhieu = "UPDATE phieuyeucaukiemtrachatluong 
                         SET trangThai = 'Đã duyệt' 
                         WHERE maYC = ?";
            $stmt2 = $this->conn->prepare($sqlPhieu);
            $stmt2->bind_param("i", $maYC);
            return $stmt2->execute();
        }

        return false;
    }
}
