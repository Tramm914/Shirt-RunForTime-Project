<?php
// app/models/GhiNhanThanhPhamModel.php
require_once('ketNoi.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class GhiNhanThanhPhamModel
{
    private $conn;

    public function __construct()
    {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    /**
     * Lưu danh sách ghi nhận và chỉ cập nhật trạng thái KHSX 
     * dựa trên sản lượng của Xưởng May
     */
    public function luuDanhSach($header, $details)
    {
        $this->conn->begin_transaction();

        try {
            // 1. Thực hiện lưu tất cả chi tiết ghi nhận vào bảng
            $sql = "INSERT INTO ghinhanthanhphamtheongay 
                    (maNhanVien, maSanPham, soLuongSPHoanThanh, ngayLam, maKHSX) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            $maSP_bind = (int) $header['maSanPham'];
            $ngayLam_bind = $header['ngayLam'];
            $maKHSX_bind = (int) $header['maKHSX'];

            foreach ($details as $item) {
                if (empty($item['maNhanVien']) || !isset($item['soLuong']) || $item['soLuong'] <= 0) {
                    throw new Exception("Dữ liệu chi tiết không hợp lệ");
                }
                $maNV_i = (int) $item['maNhanVien'];
                $soLuong_i = (int) $item['soLuong'];
                
                $stmt->bind_param("iiisi", $maNV_i, $maSP_bind, $soLuong_i, $ngayLam_bind, $maKHSX_bind);
                $stmt->execute();
            }
            $stmt->close();

            // 2. Tính TỔNG sản lượng lũy kế của riêng XƯỞNG MAY cho KHSX này
            // Sử dụng n.phongBan để lọc chính xác đội May
            $sqlTongMay = "SELECT COALESCE(SUM(g.soLuongSPHoanThanh), 0) AS tongMay
                           FROM ghinhanthanhphamtheongay g
                           JOIN nguoidung n ON g.maNhanVien = n.maND
                           WHERE g.maKHSX = ? 
                           AND n.phongBan LIKE '%Xưởng may%'"; 
            
            $stmtTong = $this->conn->prepare($sqlTongMay);
            $stmtTong->bind_param("i", $maKHSX_bind);
            $stmtTong->execute();
            $ketQuaTong = $stmtTong->get_result()->fetch_assoc();
            $tongDaMay = (int) $ketQuaTong['tongMay'];
            $stmtTong->close();

            // 3. Lấy số lượng mục tiêu cần sản xuất từ Đơn hàng liên kết với KHSX
            $sqlKH = "SELECT d.soLuongSanXuat 
                      FROM kehoachsanxuat k
                      JOIN donhangsanxuat d ON k.maDonHang = d.maDonHang 
                      WHERE k.maKHSX = ?";
            $stmtKH = $this->conn->prepare($sqlKH);
            $stmtKH->bind_param("i", $maKHSX_bind);
            $stmtKH->execute();
            $resultKH = $stmtKH->get_result()->fetch_assoc();
            $stmtKH->close();

            // 4. So sánh: Nếu sản lượng Xưởng May >= Mục tiêu đơn hàng -> Cập nhật trạng thái
            if ($resultKH) {
                $soLuongCanLam = (int) $resultKH['soLuongSanXuat'];
                if ($soLuongCanLam > 0 && $tongDaMay >= $soLuongCanLam) {
                    $sqlUpdate = "UPDATE kehoachsanxuat 
                                  SET trangThai = 'Hoàn thành' 
                                  WHERE maKHSX = ?";
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $stmtUpdate->bind_param("i", $maKHSX_bind);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("❌ Lỗi Model: " . $e->getMessage());
            return $e->getMessage(); 
        }
    }

    /**
     * Lấy lịch sử hiển thị ra View (Tách cột BTP và TP bằng PIVOT SQL)
     */
    public function getLichSuGhiNhan($limit = 5)
    {
        $sql = "SELECT 
                    g.ngayLam,
                    g.maKHSX,
                    k.tenKHSX,
                    s.tenSanPham,
                    -- Bán thành phẩm (Xưởng cắt)
                    SUM(CASE WHEN n.phongBan LIKE '%Xưởng cắt%' THEN g.soLuongSPHoanThanh ELSE 0 END) as slBanThanhPham,
                    -- Thành phẩm (Xưởng may)
                    SUM(CASE WHEN n.phongBan LIKE '%Xưởng may%' THEN g.soLuongSPHoanThanh ELSE 0 END) as slThanhPham,
                    COUNT(DISTINCT g.maNhanVien) as soNhanVien
                FROM ghinhanthanhphamtheongay g
                LEFT JOIN kehoachsanxuat k ON g.maKHSX = k.maKHSX
                LEFT JOIN san_pham s ON g.maSanPham = s.maSanPham
                LEFT JOIN nguoidung n ON g.maNhanVien = n.maND
                GROUP BY g.ngayLam, g.maKHSX, k.tenKHSX, s.tenSanPham
                ORDER BY g.ngayLam DESC, g.maKHSX DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getChiTietPhieu($ngayLam, $maKHSX)
    {
        $sql = "SELECT 
                    n.hoTen,
                    n.phongBan,
                    g.soLuongSPHoanThanh
                FROM ghinhanthanhphamtheongay g
                JOIN nguoidung n ON g.maNhanVien = n.maND
                WHERE g.ngayLam = ? AND g.maKHSX = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $ngayLam, $maKHSX);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSoLuongTrungBinh()
    {
        $sql = "SELECT AVG(soLuongSPHoanThanh) as sanLuongTB FROM ghinhanthanhphamtheongay";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row ? (int) $row['sanLuongTB'] : 0;
    }

    public function getSoLuongTrungBinhTheoXuong($tenXuongKeyword)
    {
        $sql = "SELECT AVG(tong_ngay) as sanLuongTB FROM (
                    SELECT SUM(g.soLuongSPHoanThanh) as tong_ngay
                    FROM ghinhanthanhphamtheongay g
                    JOIN nguoidung nd ON g.maNhanVien = nd.maND
                    WHERE nd.phongBan LIKE ? 
                    GROUP BY g.ngayLam
                    ORDER BY g.ngayLam DESC
                    LIMIT 5
                ) as bang_tam";

        $stmt = $this->conn->prepare($sql);
        $keyword = "%" . $tenXuongKeyword . "%";
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? (int) $row['sanLuongTB'] : 0;
    }
}