<?php
require_once 'ketNoi.php';

class NguyenVatLieuModel {
    private $conn;

    public function __construct() {
        $database = new KetNoi();
        $this->conn = $database->connect();
    }

    // ✅ Tìm NVL theo từ khóa (tên, mã, loại)
    public function timNVLTheoTuKhoa($keyword) {
        $sql = "SELECT maNVL, tenNVL, loaiNVL 
                FROM nvl 
                WHERE maNVL LIKE ? OR tenNVL LIKE ? OR loaiNVL LIKE ?
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die('❌ Lỗi prepare: ' . $this->conn->error);
        }

        $like = "%{$keyword}%";
        $stmt->bind_param("sss", $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    // ✅ Gợi ý loại nguyên vật liệu (danh sách loại duy nhất)
    public function goiYLLoaiNVL($keyword = '') {
        $sql = "SELECT DISTINCT loaiNVL 
                FROM nvl 
                WHERE loaiNVL LIKE ?
                LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die('❌ Lỗi prepare: ' . $this->conn->error);
        }

        $like = "%{$keyword}%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['loaiNVL']; // chỉ lấy tên loại
        }

        $stmt->close();
        return $data;
    }
    public function goiYNVL($keyword) {
    $sql = "SELECT DISTINCT tenNVL AS label, 'Tên NVL' AS type
            FROM nvl WHERE tenNVL LIKE ?
            UNION
            SELECT DISTINCT loaiNVL AS label, 'Loại NVL' AS type
            FROM nvl WHERE loaiNVL LIKE ?
            LIMIT 10";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        die('❌ Lỗi prepare: ' . $this->conn->error);
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

}
?>
