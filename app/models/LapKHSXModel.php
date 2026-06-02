<?php
// app/models/LapKHSXModel.php
require_once 'ketNoi.php';

class LapKHSXModel
{
    private $conn;

    public function __construct()
    {
        $database = new KetNoi();
        $this->conn = $database->connect();
    }

    /**
     * Lấy danh sách KHSX đã duyệt nhưng CHƯA có phiếu yêu cầu NVL
     */
    public function getAllPlans()
    {
        $sql = "SELECT kh.maKHSX, kh.tenKHSX, kh.thoiGianBatDau, kh.thoiGianKetThuc,
                       nd.hoTen AS tenNguoiTao
                FROM kehoachsanxuat kh
                LEFT JOIN phieuyeucaucungcapnvl pyc ON kh.maKHSX = pyc.maKHSX
                JOIN nguoidung nd ON kh.maND = nd.maND
                WHERE kh.trangThai = 'Đã duyệt' AND pyc.maYCCC IS NULL
                ORDER BY kh.thoiGianBatDau DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getPlanById($maKHSX)
    {
        $sql = "SELECT kh.tenKHSX, kh.thoiGianKetThuc, nd.hoTen AS tenNguoiLap
                FROM kehoachsanxuat kh
                JOIN nguoidung nd ON kh.maND = nd.maND
                WHERE kh.maKHSX = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            return null;

        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $data;
    }

    /**
     * Lấy NVL theo kế hoạch
     */
    public function getMaterialsForPlan($maKHSX)
    {
        $sql = "SELECT ct.maNVL, nvl.tenNVL, nvl.loaiNVL, nvl.donViTinh, ct.soLuongNVL
                FROM chitietkehoachsanxuat ct
                JOIN nvl ON ct.maNVL = nvl.maNVL
                WHERE ct.maKHSX = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            return [];

        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Danh sách KHSX (giới hạn)
     */
    public function getDanhSachKHSX($limit = 20)
    {
        $sql = "SELECT kh.tenKHSX, kh.maDonHang, kh.thoiGianBatDau, kh.thoiGianKetThuc,
                       kh.trangThai, nd.hoTen AS tenNguoiLap
                FROM kehoachsanxuat kh
                LEFT JOIN nguoidung nd ON kh.maND = nd.maND
                ORDER BY kh.thoiGianBatDau DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            return [];

        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Tạo KHSX (bảng cha)
     */
    public function createKHSX($data)
    {
        $sql = "INSERT INTO kehoachsanxuat 
                (tenKHSX, maDonHang, thoiGianBatDau, thoiGianKetThuc, trangThai, maND)
                VALUES (?, ?, ?, ?, 'Chờ duyệt', ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            throw new Exception($this->conn->error);

        $stmt->bind_param(
            "sissi",
            $data['tenKHSX'],
            $data['maDonHang'],
            $data['thoiGianBatDau'],
            $data['thoiGianKetThuc'],
            $data['maND']
        );

        if (!$stmt->execute()) {
            $e = $stmt->error;
            $stmt->close();
            throw new Exception("SQL Error: " . $e);
        }

        $id = $this->conn->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * Tạo CHI TIẾT KHSX (bảng con) - FULL CÁC CỘT
     */
    public function createChiTietKHSX($data)
    {
        // Lấy thông tin NVL
        $nvl = $this->getNvlInfo($data['maNVL']);

        $sql = "INSERT INTO chitietkehoachsanxuat (
                maKHSX, 
                maGNTP, 
                maXuong, 
                maNVL, 
                tenNVL, 
                loaiNVL,
                soLuongNVL, 
                ngayBatDau, 
                ngayKetThuc, 
                KPI,
                soLuongThanhPham, 
                dinhMuc
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Nếu không có maGNTP thì gán NULL
        $maGNTP = $data['maGNTP'] ?? null;

        $stmt->bind_param(
            "iiissdsssidd",
            $data['maKHSX'],               // i
            $maGNTP,                       // i (NULL allowed)
            $data['maXuong'],              // i
            $data['maNVL'],                // i
            $nvl['tenNVL'],                // s
            $nvl['loaiNVL'],               // s
            $data['soLuongNVL'],           // d
            $data['ngayBatDau'],           // s
            $data['ngayKetThuc'],          // s
            $data['KPI'],                  // i
            $data['soLuongThanhPham'],     // d
            $data['dinhMuc']               // d
        );

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            throw new Exception("SQL Error: " . $err);
        }

        $stmt->close();
        return true;
    }


    /**
     * Lấy info NVL
     */
    private function getNvlInfo($maNVL)
    {
        $sql = "SELECT tenNVL, loaiNVL, donViTinh
                FROM nvl WHERE maNVL = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return ['tenNVL' => 'Không rõ', 'loaiNVL' => 'Không rõ', 'donViTinh' => ''];
        }

        $stmt->bind_param("i", $maNVL);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        return $res ? $res->fetch_assoc() : ['tenNVL' => 'Không rõ', 'loaiNVL' => 'Không rõ', 'donViTinh' => ''];
    }

    /**
     * Lấy KHSX đã duyệt
     */
    public function getKHSXDaDuyet()
    {
        $sql = "SELECT maKHSX, tenKHSX 
                FROM kehoachsanxuat 
                WHERE trangThai = 'Đã duyệt'
                ORDER BY maKHSX DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Lấy sản phẩm theo KHSX
     */
    public function getSanPhamTheoKHSX($maKHSX)
    {
        $sql = "SELECT sp.maSanPham, sp.tenSanPham
                FROM san_pham sp
                JOIN donhangsanxuat dh ON sp.maSanPham = dh.maSanPham
                JOIN kehoachsanxuat kh ON dh.maDonHang = kh.maDonHang
                WHERE kh.maKHSX = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            return null;

        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        return $res ? $res->fetch_assoc() : null;
    }
    public function getDonHangChuaLapKHSX()
    {
        $sql = "
        SELECT dh.*
        FROM donhangsanxuat dh
        WHERE dh.trangThai = 'Đang thực hiện'
          AND dh.maDonHang NOT IN (
              SELECT maDonHang FROM kehoachsanxuat
          )
        ORDER BY dh.ngayGiao ASC
    ";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
