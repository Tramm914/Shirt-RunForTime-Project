<?php

class SanPhamModel {
    private $conn;
    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    public function getAllSanPham() {
        $sql = 'SELECT maSanPham, tenSanPham FROM san_pham WHERE trangThaiSanPham = 1';
        $query = $this->conn->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSoLuongTon($maSanPham, $soLuongThemVao) {
        $sql = "UPDATE san_pham 
                SET soLuongTon = soLuongTon + ?
                WHERE maSanPham = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $soLuongThemVao, $maSanPham);
        return $stmt->execute();
    }
}
?>