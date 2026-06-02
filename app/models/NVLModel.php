<?php
// app/models/NVLModel.php

class NVLModel {
    private $conn;
    public function __construct() {
        // Giả sử tệp ketNoi.php định nghĩa class Database với hàm connect()
        $this->conn = (new KetNoi())->connect();
    }

    /**
     * Lấy tất cả NVL từ bảng 'nvl'
     *
     */
    public function getAllNVL() {
        $sql = 'SELECT maNVL, tenNVL, donViTinh FROM nvl WHERE soLuongTonKho > 0'; // Chỉ lấy NVL còn tồn kho
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>