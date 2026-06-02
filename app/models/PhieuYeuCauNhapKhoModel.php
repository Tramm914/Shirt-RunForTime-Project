<?php
require_once 'ketNoi.php';

class PhieuYeuCauNhapKhoModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    // 🔹 Lấy danh sách phiếu yêu cầu nhập kho (đã cải tiến)
    public function getAll() {
        $sql = "SELECT p.maYCNK, p.tenPhieu, p.ngayLap, p.trangThai, 
                       COUNT(DISTINCT c.maNVL) AS soLoaiNVL,
                       p.tenNguoiLap
                FROM phieuyeucaunhapkhonvl p
                LEFT JOIN chitiet_phieuyeucaunhapkhonvl c ON p.maYCNK = c.maYCNK
                GROUP BY p.maYCNK, p.tenPhieu, p.ngayLap, p.trangThai, p.tenNguoiLap
                ORDER BY p.ngayLap DESC, p.maYCNK DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function getAllPlansForNhapKho() {
    $sql = "
        SELECT kh.maKHSX, kh.tenKHSX, kh.thoiGianBatDau, kh.thoiGianKetThuc
        FROM kehoachsanxuat kh
        WHERE kh.trangThai = 'Đã duyệt'
          AND kh.maKHSX NOT IN (
              SELECT DISTINCT p.maKHSX
              FROM phieuyeucaunhapkhonvl p
              WHERE p.maKHSX IS NOT NULL
          )
        ORDER BY kh.maKHSX DESC
    ";
    
    $result = $this->conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

    // 🔹 Lấy danh sách NVL từ kế hoạch sản xuất
    public function getMaterialsByPlan($maKHSX) {
        $sql = "SELECT c.maNVL, c.tenNVL, c.soLuongNVL AS soLuongCan,
                       n.soLuongTonKho, n.donViTinh
                FROM chitietkehoachsanxuat c
                JOIN nvl n ON c.maNVL = n.maNVL
                WHERE c.maKHSX = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $maKHSX);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    
    // 🔹 Kiểm tra kế hoạch đã có phiếu chưa
    public function existsByKeHoach($maKHSX) {
        $sql = "SELECT COUNT(*) AS total FROM phieuyeucaunhapkhonvl WHERE maKHSX = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $maKHSX);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }

    // 🔹 Tạo mới phiếu nhập kho từ kế hoạch sản xuất (Đơn giản - không tính toán)
public function createPhieuYeuCauNhapKho($data) {
    $maKHSX = intval($data['maKHSX'] ?? 0);
    $ngayLap = $data['ngayLap'] ?? date('Y-m-d');
    $ghiChu = trim($data['ghiChu'] ?? '');
    $dsNVL = $data['nvl'] ?? [];
    $nhaCungCap = $data['nhaCungCap'] ?? [];

    if ($maKHSX <= 0 || empty($dsNVL)) {
        error_log("❌ Thiếu dữ liệu: maKHSX hoặc danh sách NVL rỗng");
        return false;
    }

    $this->conn->begin_transaction();
    try {
        // Tạo mã phiếu
        $sqlMaxID = "SELECT COALESCE(MAX(maYCNK), 0) + 1 AS nextID FROM phieuyeucaunhapkhonvl";
        $result = $this->conn->query($sqlMaxID);
        $maPhieu = $result->fetch_assoc()['nextID'];
        
        $tenPhieu = 'Phiếu yêu cầu nhập kho NVL - KHSX ' . $maKHSX;
        
        // Lấy thông tin người dùng từ session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $tenNguoiLap = $_SESSION['user']['hoTen'] ?? ($_SESSION['user']['tenDangNhap'] ?? 'Không xác định');
        $maND = $_SESSION['user']['maND'] ?? null;

        // Lưu phiếu chính
        $sqlPhieu = "INSERT INTO phieuyeucaunhapkhonvl (maYCNK, tenPhieu, maKHSX, ngayLap, tenNguoiLap, maND, trangThai)
                     VALUES (?, ?, ?, ?, ?, ?, 'Chờ duyệt')";
        $stmt = $this->conn->prepare($sqlPhieu);
        $stmt->bind_param('issssi', $maPhieu, $tenPhieu, $maKHSX, $ngayLap, $tenNguoiLap, $maND);
        $stmt->execute();

        // Lưu chi tiết phiếu: tất cả NVL được chọn
        // ✅ Công thức: Số lượng = Số lượng cần nhập - (Số lượng tồn kho * 10%)
        foreach ($dsNVL as $maNVL) {
            $nccForThisNVL = $nhaCungCap[$maNVL] ?? '';
            
            $sqlCT = "
                INSERT INTO chitiet_phieuyeucaunhapkhonvl (maYCNK, maNVL, tenNVL, soLuong, donViTinh, nhaCungCap, soLuongTonKho, soLuongCanNhap)
                SELECT ?, c.maNVL, c.tenNVL, 
                       GREATEST(0, ROUND(c.soLuongNVL - (n.soLuongTonKho * 0.1), 2)), 
                       n.donViTinh, ?,
                       n.soLuongTonKho, c.soLuongNVL
                FROM chitietkehoachsanxuat c
                JOIN nvl n ON c.maNVL = n.maNVL
                WHERE c.maKHSX = ?
                  AND c.maNVL = ?
            ";
            $stmtCT = $this->conn->prepare($sqlCT);
            $stmtCT->bind_param('isii', $maPhieu, $nccForThisNVL, $maKHSX, $maNVL);
            $stmtCT->execute();
        }

        $this->conn->commit();
        return true;
    } catch (Exception $e) {
        $this->conn->rollback();
        error_log("❌ Lỗi lưu phiếu nhập kho: " . $e->getMessage());
        return false;
    }
}


    public function getChiTietPhieu($maYCNK) {
        $sql = "SELECT c.*, n.tenNVL, n.donViTinh
                FROM chitiet_phieuyeucaunhapkhonvl c
                JOIN nvl n ON c.maNVL = n.maNVL
                WHERE c.maYCNK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $maYCNK);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // 🔹 Lấy danh sách phiếu chờ duyệt
    public function getPendingRequests() {
        $sql = "SELECT p.maYCNK, p.tenPhieu, p.ngayLap, p.tenNguoiLap, p.trangThai,
                       GROUP_CONCAT(DISTINCT c.nhaCungCap SEPARATOR ', ') as nhaCungCap
                FROM phieuyeucaunhapkhonvl p
                LEFT JOIN chitiet_phieuyeucaunhapkhonvl c ON p.maYCNK = c.maYCNK
                WHERE p.trangThai = 'Chờ duyệt'
                GROUP BY p.maYCNK, p.tenPhieu, p.ngayLap, p.tenNguoiLap, p.trangThai
                ORDER BY p.ngayLap DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // 🔹 Cập nhật trạng thái phiếu (Duyệt / Từ chối)
    public function updateStatus($maYCNK, $trangThai, $nguoiDuyet, $lyDoTuChoi = null) {
        $sql = "UPDATE phieuyeucaunhapkhonvl 
                SET trangThai = ?, nguoiDuyet = ?, ngayDuyet = NOW(), lyDoTuChoi = ?
                WHERE maYCNK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssi', $trangThai, $nguoiDuyet, $lyDoTuChoi, $maYCNK);
        return $stmt->execute();
    }

    // 🔹 Lấy chi tiết phiếu bao gồm thông tin header
    public function getDetailsByRequest($maYCNK) {
        $sql = "SELECT p.maYCNK, p.tenPhieu, p.ngayLap, p.tenNguoiLap, p.trangThai,
                       c.maNVL, c.tenNVL, c.soLuong, c.donViTinh, c.nhaCungCap
                FROM phieuyeucaunhapkhonvl p
                LEFT JOIN chitiet_phieuyeucaunhapkhonvl c ON p.maYCNK = c.maYCNK
                WHERE p.maYCNK = ?
                ORDER BY c.maNVL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $maYCNK);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

}
