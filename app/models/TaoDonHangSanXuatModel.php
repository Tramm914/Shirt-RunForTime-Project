<?php
require_once 'ketNoi.php';

class TaoDonHangSanXuatModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    /**
     * Lấy danh sách tất cả sản phẩm
     */
    public function getDanhSachSanPham() {
        $sql = "SELECT maSanPham, tenSanPham, donVi 
                FROM san_pham 
                ORDER BY tenSanPham";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            error_log("Lỗi truy vấn danh sách sản phẩm: " . $this->conn->error);
            return [];
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lưu đơn hàng sản xuất mới
     * @param array $data Dữ liệu từ form (đã validate ở controller)
     * @return bool|int Trả về maDonHang nếu thành công, false nếu thất bại
     */
    public function luuDonHang($data) {
        // ✅ BỎ validate lặp (controller đã làm): chỉ dùng dữ liệu đầu vào đã sạch

        $tenSanPhamInput = trim($data['tenSanPham'] ?? '');
        $maSanPhamInput = (int)($data['sanPhamId'] ?? 0);
        $soLuong = (int)($data['soLuong'] ?? 0);
        $ngayGiao = $data['ngayGiao'] ?? '';
        $diaChiNhan = trim($data['diaChiNhan'] ?? '');
        $ghiChu = trim($data['ghiChu'] ?? '');

        // Lấy thông tin sản phẩm: ưu tiên theo ID, sau đó theo tên, cuối cùng tự thêm mới
        $sanPham = null;
        if ($maSanPhamInput > 0) {
            $sanPham = $this->getSanPhamById($maSanPhamInput);
        }

        if (!$sanPham) {
            $sanPham = $this->getSanPhamByTen($tenSanPhamInput);
        }

        if (!$sanPham) {
            $maSanPhamInput = $this->taoSanPhamMoi($tenSanPhamInput);
            if (!$maSanPhamInput) {
                error_log("Không thể tạo sản phẩm mới: " . $tenSanPhamInput);
                return false;
            }
            $sanPham = [
                'maSanPham' => $maSanPhamInput,
                'tenSanPham' => $tenSanPhamInput,
                'donVi' => 'Cái'
            ];
        }

        $maSanPham = (int)$sanPham['maSanPham'];
        $tenSanPham = $sanPham['tenSanPham'] ?? $tenSanPhamInput;

        // Trạng thái mặc định
        $trangThai = 'Đang thực hiện';

        // Tạo tên đơn hàng tạm thời (sẽ được cập nhật sau khi có mã đơn hàng)
        $tenDonHangTam = 'DHSX_TEMP';

        // Chuẩn bị câu lệnh SQL - thêm tenSanPham và soLuongSanXuat
        $sql = "INSERT INTO donhangsanxuat 
                (tenDonHang, tenSanPham, soLuongSanXuat, donVi, diaChiNhan, trangThai, ngayGiao, maSanPham) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Lỗi chuẩn bị câu lệnh SQL: " . $this->conn->error);
            return false;
        }

        $donVi = $sanPham['donVi'] ?? 'Cái';
        
        $stmt->bind_param("ssissssi", 
            $tenDonHangTam, 
            $tenSanPham,
            $soLuong,
            $donVi, 
            $diaChiNhan, 
            $trangThai, 
            $ngayGiao, 
            $maSanPham
        );

        if (!$stmt->execute()) {
            error_log("Lỗi thực thi lưu đơn hàng: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $maDonHangMoi = $this->conn->insert_id;
        $stmt->close();

        // Tạo tên đơn hàng tự động dựa trên mã đơn hàng tự động tăng
        $tenDonHang = 'DHSX' . $maDonHangMoi;
        
        // Cập nhật tên đơn hàng
        $sqlUpdate = "UPDATE donhangsanxuat SET tenDonHang = ? WHERE maDonHang = ?";
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        if ($stmtUpdate) {
            $stmtUpdate->bind_param("si", $tenDonHang, $maDonHangMoi);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // Nếu có ghi chú, có thể lưu vào bảng khác hoặc thêm cột ghiChu vào bảng
        // Tạm thời bỏ qua vì bảng không có cột ghiChu

        return $maDonHangMoi;
    }

    // Các method private giữ nguyên...
    private function getSanPhamById($maSanPham) {
        $sql = "SELECT maSanPham, tenSanPham, donVi 
                FROM san_pham 
                WHERE maSanPham = ?";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $maSanPham);
        
        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $sanPham = $result->fetch_assoc();
        $stmt->close();

        return $sanPham;
    }

    private function getSanPhamByTen($tenSanPham) {
        $sql = "SELECT maSanPham, tenSanPham, donVi 
                FROM san_pham 
                WHERE LOWER(tenSanPham) = LOWER(?) 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $tenSanPham);
        if (!$stmt->execute()) {
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $sanPham = $result->fetch_assoc();
        $stmt->close();

        return $sanPham;
    }

    private function taoSanPhamMoi($tenSanPham) {
        $sql = "INSERT INTO san_pham (tenSanPham, donVi, soLuongTon) VALUES (?, 'Cái', 0)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Không thể chuẩn bị câu lệnh tạo sản phẩm mới: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $tenSanPham);
        if (!$stmt->execute()) {
            error_log("Không thể tạo sản phẩm mới: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $maSanPhamMoi = $this->conn->insert_id;
        $stmt->close();

        return $maSanPhamMoi;
    }

    public function kiemTraMaDonHangTonTai($maDonHang) {
        $sql = "SELECT COUNT(*) as count 
                FROM donhangsanxuat 
                WHERE tenDonHang LIKE ?";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        $pattern = $maDonHang . '%';
        $stmt->bind_param("s", $pattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row['count'] > 0;
    }
}
?>