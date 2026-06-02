<?php
require_once 'ketNoi.php';

class ThongKeKhoTPModel {
    private $conn;

    public function __construct() {
        $db = new KetNoi();
        $this->conn = $db->connect();
    }

    /**
     * Lấy dữ liệu thống kê kho (có lọc ngày)
     */
    public function thongKe($search = "", $from = null, $to = null) {

        $sql = "
            SELECT DISTINCT
                sp.maSanPham,
                sp.tenSanPham,
                sp.donVi,

                COALESCE(nk.tongNhap, 0) AS tongNhap,
                COALESCE(px.tongXuat, 0) AS tongXuat,
                sp.soLuongTon AS tonKho

            FROM san_pham sp

            LEFT JOIN (
                SELECT maSanPham, SUM(soLuong) AS tongNhap
                FROM chitiet_nhapkhotp
                GROUP BY maSanPham
            ) nk ON sp.maSanPham = nk.maSanPham

            LEFT JOIN (
                SELECT maSanPham, SUM(soLuongXuat) AS tongXuat
                FROM phieuxuatthanhpham
                GROUP BY maSanPham
            ) px ON sp.maSanPham = px.maSanPham

            WHERE sp.tenSanPham LIKE ?
        ";

        $types  = "s";
        $params = ["%$search%"];

        // Lọc theo khoảng ngày (nhập HOẶC xuất)
        if ($from && $to) {
            $sql .= "
                AND (
                    EXISTS (
                        SELECT 1
                        FROM chitiet_nhapkhotp c
                        JOIN nhapkhotp n ON c.maPhieu = n.maPhieu
                        WHERE c.maSanPham = sp.maSanPham
                          AND n.ngayKiemTra BETWEEN ? AND ?
                    )
                    OR
                    EXISTS (
                        SELECT 1
                        FROM phieuxuatthanhpham px2
                        WHERE px2.maSanPham = sp.maSanPham
                          AND px2.ngayXuat BETWEEN ? AND ?
                    )
                )
            ";
            $types .= "ssss";
            array_push($params, $from, $to, $from, $to);
        }

        $sql .= " ORDER BY sp.maSanPham ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Xuất CSV (dùng chung bộ lọc)
     */
    public function exportCSV($search = "", $from = null, $to = null) {
        $data = $this->thongKe($search, $from, $to);

        if (headers_sent()) {
            die("Headers already sent");
        }

        header("Content-Type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=ThongKeKhoTP_" . date("Ymd_His") . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        // BOM UTF-8 cho Excel
        echo chr(0xEF).chr(0xBB).chr(0xBF);

        $fp = fopen("php://output", "w");
        fputcsv($fp, ['Mã SP', 'Tên SP', 'Đơn vị', 'Tổng nhập', 'Tổng xuất', 'Tồn kho']);

        foreach ($data as $row) {
            fputcsv($fp, [
                $row['maSanPham'],
                $row['tenSanPham'],
                $row['donVi'],
                $row['tongNhap'],
                $row['tongXuat'],
                $row['tonKho']
            ]);
        }

        fclose($fp);
        exit;
    }
}
