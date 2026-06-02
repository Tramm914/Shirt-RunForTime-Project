<?php
require_once 'ketNoi.php';

class PhieuYeuCauModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    public function createPhieuYeuCau($data) {
        $conn = $this->conn;

        // --- Dữ liệu Phiếu Chính ---
        $tenPhieu = isset($data['tenPhieu']) ? trim($data['tenPhieu']) : 'Phiếu không tên';
        
        // SỬA TẠI ĐÂY: Lấy tên người lập từ data truyền vào
        $nguoiLap = isset($data['nguoiLap']) ? trim($data['nguoiLap']) : 'N/A';
        
        $maKHSX = isset($data['maKHSX']) ? intval($data['maKHSX']) : 0;
        
        // SỬA TẠI ĐÂY: Lấy maND động từ Controller (SESSION) truyền qua
        $maND = isset($data['maND']) ? intval($data['maND']) : 1; 
        
        $ngayLap = date('Y-m-d');
        $trangThai = "Chờ duyệt";

        // Kiểm tra dữ liệu hợp lệ (Thêm kiểm tra maND)
        if (empty($tenPhieu) || $maKHSX <= 0 || $maND <= 0) {
            error_log("Dữ liệu phiếu chính không hợp lệ (Thiếu maND hoặc maKHSX).");
            return false;
        }

        // --- Dữ liệu Chi tiết NVL (Giữ nguyên phần xử lý mảng bên dưới) ---
        $danhSachMaNVL = isset($data['maNVL']) && is_array($data['maNVL']) ? $data['maNVL'] : [];
        $danhSachTenNVL = isset($data['tenNVL']) && is_array($data['tenNVL']) ? $data['tenNVL'] : [];
        $danhSachSoLuong = isset($data['soLuong']) && is_array($data['soLuong']) ? $data['soLuong'] : [];
        $danhSachDonViTinh = isset($data['donViTinh']) && is_array($data['donViTinh']) ? $data['donViTinh'] : [];

        if (empty($danhSachMaNVL) || count($danhSachMaNVL) !== count($danhSachSoLuong)) {
             error_log("Dữ liệu NVL chi tiết không khớp.");
             return false;
        }

        $conn->begin_transaction();

        try {
            // 1. INSERT vào bảng chính
            $sql1 = "INSERT INTO phieuyeucaucungcapnvl (tenPhieu, tenNguoiLap, ngayLap, maKHSX, maND, trangThai)
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            if (!$stmt1) throw new Exception("Lỗi prepare 1: " . $conn->error);
            
            // Kiểu dữ liệu "sssiss": string, string, string, int, int, string
            $stmt1->bind_param("sssiss", $tenPhieu, $nguoiLap, $ngayLap, $maKHSX, $maND, $trangThai);

            if (!$stmt1->execute()) throw new Exception("Lỗi execute 1: " . $stmt1->error);
            $maYCCC = $conn->insert_id;
            $stmt1->close();

            // 2. INSERT vào bảng chi tiết
            $sql2 = "INSERT INTO chitiet_phieuyeucaucapnvl (maYCCC, maNVL, tenNVL, soLuong, donViTinh)
                     VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            if (!$stmt2) throw new Exception("Lỗi prepare 2: " . $conn->error);

            for ($i = 0; $i < count($danhSachMaNVL); $i++) {
                $currentMaNVL = intval($danhSachMaNVL[$i]);
                $currentTenNVL = trim($danhSachTenNVL[$i]);
                $currentSoLuong = intval($danhSachSoLuong[$i]);
                $currentDonViTinh = trim($danhSachDonViTinh[$i]);

                if ($currentMaNVL > 0 && $currentSoLuong > 0) {
                    $stmt2->bind_param("iisis", $maYCCC, $currentMaNVL, $currentTenNVL, $currentSoLuong, $currentDonViTinh);
                    if (!$stmt2->execute()) throw new Exception("Lỗi execute 2: " . $stmt2->error);
                }
            }
            $stmt2->close();

            $conn->commit();
            return true;

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Lỗi: " . $e->getMessage());
            return false;
        }
    }
}
?>