<?php
class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy thông tin người dùng theo mã tài khoản
    public function getUserById($maTK) {
        $sql = "SELECT maTK, hoTen, gioiTinh, ngaySinh, chucVu, soDienThoai, email, diaChi, hinhAnh
                FROM nguoidung 
                WHERE maTK = ?";
        
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $maTK);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user ?: null; // Trả về null nếu không tìm thấy
        } else {
            // Ghi log lỗi hoặc ném exception nếu cần
            error_log("Lỗi truy vấn: " . $this->conn->error);
            return null;
        }
    }
}
?>
