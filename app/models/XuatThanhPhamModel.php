<?php
include_once 'ketNoi.php';

class XuatThanhPhamModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    // 🔹 Lấy danh sách đơn hàng chưa xuất kho
    public function getDonHangChuaXuat() {
        $sql = "SELECT 
                    dh.maDonHang, 
                    dh.tenDonHang, 
                    sp.tenSanPham, 
                    sp.soLuongTon, 
                    dh.soLuongSanXuat
                FROM donhangsanxuat dh
                JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
                WHERE dh.trangThai = 'Chờ xuất kho'";

        $result = $this->conn->query($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // 🔹 Thực hiện xuất kho
    public function xuatKho($maDonHang, $soLuongXuat, $ghiChu = '') {
        // 1️⃣ Lấy thông tin sản phẩm của đơn hàng
        $stmt = $this->conn->prepare("
            SELECT sp.maSanPham, sp.soLuongTon 
            FROM donhangsanxuat dh
            JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
            WHERE dh.maDonHang = ?
        ");
        $stmt->bind_param("i", $maDonHang);
        $stmt->execute();
        $result = $stmt->get_result();
        $sanPham = $result->fetch_assoc();
        $stmt->close();

        if (!$sanPham) return false;
        if ($sanPham['soLuongTon'] < $soLuongXuat) return "Không đủ hàng tồn trong kho";

        // 2️⃣ Giảm số lượng tồn kho sản phẩm
        $stmt = $this->conn->prepare("
            UPDATE san_pham 
            SET soLuongTon = soLuongTon - ? 
            WHERE maSanPham = ?
        ");
        $stmt->bind_param("ii", $soLuongXuat, $sanPham['maSanPham']);
        $stmt->execute();
        $stmt->close();

        // 3️⃣ Ghi phiếu xuất kho
        $stmt = $this->conn->prepare("
            INSERT INTO phieuxuatthanhpham (maDonHang, maSanPham, soLuongXuat, ghiChu)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiis", $maDonHang, $sanPham['maSanPham'], $soLuongXuat, $ghiChu);
        $stmt->execute();
        $stmt->close();

        // 4️⃣ Cập nhật trạng thái đơn hàng
        $stmt = $this->conn->prepare("
            UPDATE donhangsanxuat 
            SET trangThai = 'Đã xuất kho' 
            WHERE maDonHang = ?
        ");
        $stmt->bind_param("i", $maDonHang);
        $stmt->execute();
        $stmt->close();

        return true;
    }
    public function getChiTietDonHang($maDonHang) {
    $stmt = $this->conn->prepare("
        SELECT dh.*, sp.tenSanPham, sp.soLuongTon
        FROM donhangsanxuat dh
        JOIN san_pham sp ON dh.maSanPham = sp.maSanPham
        WHERE dh.maDonHang = ?
    ");
    $stmt->bind_param("i", $maDonHang);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

}
?>
