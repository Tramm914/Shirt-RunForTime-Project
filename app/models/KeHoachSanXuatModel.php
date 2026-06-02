<?php
// Đường dẫn đúng từ /app/models/
require_once 'ketNoi.php';

class KeHoachSanXuatModel
{
    // Dùng khoảng trắng thường để thụt lề
    private $conn;

    public function __construct()
    {
        $database = new KetNoi();
        $this->conn = $database->connect();
    }

    /* === SỬA HÀM NÀY ĐỂ LỌC KHSX ĐÃ YÊU CẦU NVL === */
   public function getAllPlans()
    {
        $sql = "SELECT
                    kh.maKHSX,
                    kh.tenKHSX,
                    kh.thoiGianBatDau,
                    kh.thoiGianKetThuc,      -- <-- THÊM CỘT NÀY
                    nd.hoTen AS tenNguoiTao  -- <-- THÊM CỘT NÀY (lấy từ bảng nguoidung)
                FROM
                    kehoachsanxuat kh
                LEFT JOIN                       -- Giữ LEFT JOIN để lọc KHSX chưa có phiếu
                    phieuyeucaucungcapnvl pyc ON kh.maKHSX = pyc.maKHSX
                JOIN                            -- Thêm JOIN để lấy tên người tạo
                    nguoidung nd ON kh.maND = nd.maND
                WHERE
                    kh.trangThai = 'Đã duyệt'
                    AND pyc.maYCCC IS NULL";

        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Lỗi truy vấn KHSX chưa yêu cầu NVL (chi tiết): " . $this->conn->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPlanById($maKHSX)
    {
        $sql = "SELECT kh.tenKHSX, kh.thoiGianKetThuc, nd.hoTen AS tenNguoiLap
                FROM kehoachsanxuat kh
                JOIN nguoidung nd ON kh.maND = nd.maND
                WHERE kh.maKHSX = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $data;
    }

    // Hàm getMaterialsForPlan đã lấy loaiNVL rồi, cầnthêm  donViTinh
    public function getMaterialsForPlan($maKHSX)
    {
        $sql = "SELECT
                    ct.maNVL,
                    nvl.tenNVL,
                    nvl.loaiNVL,
                    nvl.donViTinh, -- <-- THÊM CỘT NÀY TỪ BẢNG NVL
                    ct.soLuongNVL
                FROM
                    chitietkehoachsanxuat ct
                JOIN
                    NVL nvl ON ct.maNVL = nvl.maNVL
                WHERE
                    ct.maKHSX = ?";
        // ... (phần còn lại của hàm giữ nguyên) ...
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn NVL cho KHSX: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $maKHSX);
        if (!$stmt->execute()) {
            error_log("Lỗi thực thi truy vấn NVL cho KHSX: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        if (!$result) {
            error_log("Lỗi lấy kết quả NVL cho KHSX: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }
    public function getAllPlansForNhapKho()
    {
        $sql = "SELECT maKHSX, tenKHSX, thoiGianBatDau, thoiGianKetThuc, trangThai
            FROM kehoachsanxuat
            WHERE trangThai = 'Đã duyệt'";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die('❌ Lỗi prepare: ' . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function getAll()
    {
        $sql = "SELECT * FROM kehoachsanxuat ORDER BY maKHSX DESC";
        $stmt = $this->conn->prepare($sql);

        $data = [];
        if ($stmt && $stmt->num_rows > 0) {
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getDanhSachKHSX($limit = 20)
    {
        // Sắp xếp theo thoiGianBatDau GIẢM DẦN (thay cho "ngày lập" vì cột đó không có)
        $sql = "SELECT 
                    kh.tenKHSX, 
                    kh.maDonHang, 
                    kh.thoiGianBatDau, 
                    kh.thoiGianKetThuc, 
                    kh.trangThai, 
                    nd.hoTen AS tenNguoiLap
                FROM kehoachsanxuat kh
                LEFT JOIN nguoidung nd ON kh.maND = nd.maND
                ORDER BY kh.thoiGianBatDau DESC
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Lỗi prepare getDanhSachKHSX: " . $this->conn->error);
            return [];
        }
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function createKHSX($data)
    {
        $sql = "INSERT INTO kehoachsanxuat (tenKHSX, maDonHang, thoiGianBatDau, thoiGianKetThuc, trangThai, maND)
                VALUES (?, ?, ?, ?, 'Chờ duyệt', ?)";

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Lỗi SQL Prepare (createKHSX): " . $this->conn->error);
        }

        // Thay $data['maDHSX'] bằng $data['maDonHang']
        $stmt->bind_param(
            "sissi",
            $data['tenKHSX'],
            $data['maDonHang'], // Sửa ở đây
            $data['thoiGianBatDau'],
            $data['thoiGianKetThuc'],
            $data['maND']
        );

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            throw new Exception("Lỗi SQL Execute (createKHSX): " . $stmt->error);
        }
    }
    public function createChiTietKHSX($dataChiTiet)
    {

        // Lấy tenNVL và loaiNVL từ bảng nvl
        $nvlInfo = $this->getNvlInfo($dataChiTiet['maNVL']);

        // Sửa SQL: Bỏ maSanPham, thêm tenNVL, loaiNVL
        $sql = "INSERT INTO chitietkehoachsanxuat 
                (maKHSX, maXuong, maNVL, tenNVL, loaiNVL, soLuongNVL, maGNTP)
                VALUES (?, ?, ?, ?, ?, ?, 0)"; // Giả định maGNTP là 0

        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Lỗi SQL Prepare (createChiTietKHSX): " . $this->conn->error);
        }

        $stmt->bind_param(
            "iiissi", // (int, int, int, string, string, int)
            $dataChiTiet['maKHSX'],
            $dataChiTiet['maXuong'],
            $dataChiTiet['maNVL'],
            $nvlInfo['tenNVL'],      // Thêm tenNVL
            $nvlInfo['loaiNVL'],     // Thêm loaiNVL
            $dataChiTiet['soLuongNVL']
        );

        return $stmt->execute();
    }

    private function getNvlInfo($maNVL)
    {
        $sql = "SELECT tenNVL, loaiNVL FROM nvl WHERE maNVL = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maNVL);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ? $data : ['tenNVL' => 'Không rõ', 'loaiNVL' => 'Không rõ'];
    }


    public function getDHSXbyDateRange($ngayBatDau, $ngayKetThuc)
    {
        $sql = "SELECT * FROM donhangsanxuat 
                WHERE ngayTao BETWEEN :ngayBatDau AND :ngayKetThuc
                ORDER BY ngayTao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':ngayBatDau' => $ngayBatDau,
            ':ngayKetThuc' => $ngayKetThuc
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKHSXDaDuyet()
    {
        $sql = "SELECT maKHSX, tenKHSX 
                FROM kehoachsanxuat 
                WHERE trangThai = 'Đã duyệt'
                ORDER BY maKHSX DESC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getSanPhamTheoKHSX($maKHSX)
    {
        // Lấy maSanPham từ đơn hàng liên kết với KHSX
        $sql = "SELECT 
                    sp.maSanPham, 
                    sp.tenSanPham
                FROM san_pham sp
                JOIN donhangsanxuat dh ON sp.maSanPham = dh.maSanPham
                JOIN kehoachsanxuat kh ON dh.maDonHang = kh.maDonHang
                WHERE kh.maKHSX = ?
                LIMIT 1"; // Giả định 1 KHSX chỉ cho 1 sản phẩm chính

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $maKHSX);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
}
