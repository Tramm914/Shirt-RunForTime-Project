<?php
require_once 'ketNoi.php';

class ThietBiModel {
    private $conn;

    public function __construct() {
        $database = new KetNoi();
        $this->conn = $database->connect(); // MySQLi connection
    }

    // ✅ Tìm thiết bị theo từ khóa (tự do)
    public function timThietBiTheoTuKhoa($keyword) {
        $sql = "SELECT maThietBi, tenThietBi 
                FROM thietbi 
                WHERE maThietBi LIKE ? OR tenThietBi LIKE ?
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $like = "%{$keyword}%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    // ✅ Tìm thiết bị theo từ khóa và mã xưởng (lọc theo xưởng)
    public function timThietBiTheoTuKhoaVaXuong($keyword, $maXuong) {
        $sql = "SELECT maThietBi, tenThietBi 
                FROM thietbi 
                WHERE maXuong = ? 
                AND (maThietBi LIKE ? OR tenThietBi LIKE ?)
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $like = "%{$keyword}%";
        $stmt->bind_param("iss", $maXuong, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }
        // ✅ Lấy toàn bộ thiết bị theo mã xưởng
    public function layThietBiTheoXuong($maXuong) {
        $sql = "SELECT maThietBi, tenThietBi FROM thietbi WHERE maXuong = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param("i", $maXuong);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

}
?>
