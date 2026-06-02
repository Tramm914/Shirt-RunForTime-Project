<?php
require_once 'ketNoi.php';

class BaoCaoTongHopModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new KetNoi())->connect();
    }


    public function getBaoCaoTongHop($loai, $start, $end)
    {

        $sql_baocaoloi = "SELECT 
        bcl.maBaoCao AS id, 
        bcl.tenBaoCao AS ten_phieu, 
        'Báo cáo lỗi' AS loai_phieu_text, 
        'baocaoloi' AS loai_phieu_key,
        bcl.thoiGian AS ngay_tao, 
        nd.hoTen AS nguoi_lap, 
        'Đã báo cáo' AS trang_thai
    FROM baocaoloi bcl
    JOIN nguoidung nd ON bcl.maND = nd.maND";

        $sql_phieunhapnvl = "SELECT 
            maPNVL AS id, 
            tenPNVL AS ten_phieu, 
            'Phiếu nhập NVL' AS loai_phieu_text,
            'phieunhapnvl' AS loai_phieu_key,
            ngayNhap AS ngay_tao,
            nguoiLap AS nguoi_lap,
            'Đã nhập kho' AS trang_thai
        FROM phieunhapnvl";

        $sql_phieuxuatnvl = "SELECT 
            maPhieu AS id, 
            tenPhieu AS ten_phieu, 
            'Phiếu xuất NVL' AS loai_phieu_text,
            'phieuxuatnvl' AS loai_phieu_key,
            ngayLap AS ngay_tao,
            tenNguoiLap AS nguoi_lap,
            'Đã xuất kho' AS trang_thai
        FROM phieuxuatnvl";

        $sql_phieuxuattp = "SELECT 
            maPhieuXuat AS id, 
            CONCAT('Phiếu xuất cho ĐH ', maDonHang) AS ten_phieu, 
            'Phiếu xuất TP' AS loai_phieu_text,
            'phieuxuattp' AS loai_phieu_key,
            ngayXuat AS ngay_tao,
            'Hệ thống' AS nguoi_lap,
            'Đã xuất kho' AS trang_thai
        FROM phieuxuatthanhpham";


        $sql_yeucaunvl = "SELECT 
            maYCCC AS id, 
            tenPhieu AS ten_phieu, 
            'Yêu cầu cung cấp NVL' AS loai_phieu_text,
            'yeucaunvl' AS loai_phieu_key,
            ngayLap AS ngay_tao,
            tenNguoiLap AS nguoi_lap,
            trangThai AS trang_thai
        FROM phieuyeucaucungcapnvl";

        $sql_yeucauqc = "SELECT 
            maYC AS id, 
            tenPhieu AS ten_phieu,
            'Yêu cầu QC' AS loai_phieu_text,
            'yeucauqc' AS loai_phieu_key,
            NULL AS ngay_tao, 
            tenNguoiLap AS nguoi_lap,
            trangThai AS trang_thai
        FROM phieuyeucaukiemtrachatluong";

        $sql_yeucaunhapkho = "SELECT 
            maYCNK AS id, 
            CONCAT('YCNK cho KHSX ', maKHSX) AS ten_phieu, 
            'Yêu cầu nhập kho NVL' AS loai_phieu_text,
            'yeucaunhapkho' AS loai_phieu_key,
            ngayLap AS ngay_tao,
            tenNguoiLap AS nguoi_lap,
            trangThai AS trang_thai
        FROM phieuyeucaunhapkhonvl";

        $sql_khsx = "SELECT 
            kh.maKHSX AS id, 
            kh.tenKHSX AS ten_phieu, 
            'Kế hoạch sản xuất' AS loai_phieu_text,
            'khsx' AS loai_phieu_key,
            kh.thoiGianBatDau AS ngay_tao,
            nd.hoTen AS nguoi_lap,
            kh.trangThai AS trang_thai
        FROM kehoachsanxuat kh
        LEFT JOIN nguoidung nd ON kh.maND = nd.maND";

        $sql_donhang = "SELECT 
            maDonHang AS id, 
            tenDonHang AS ten_phieu, 
            'Đơn hàng sản xuất' AS loai_phieu_text,
            'donhang' AS loai_phieu_key,
            ngayGiao AS ngay_tao, 
            'Kinh doanh' AS nguoi_lap,
            trangThai AS trang_thai
        FROM donhangsanxuat";

        $sql_ghinhanthanhpham = "SELECT 
            g.maGhiNhan AS id, 
            CONCAT('Ghi nhận cho KHSX ', g.maKHSX) AS ten_phieu, 
            'Ghi nhận thành phẩm' AS loai_phieu_text,
            'ghinhanthanhpham' AS loai_phieu_key,
            g.ngayLam AS ngay_tao,
            nd.hoTen AS nguoi_lap,
            'Đã ghi nhận' AS trang_thai
        FROM ghinhanthanhphamtheongay g
        LEFT JOIN nguoidung nd ON g.maNhanVien = nd.maND";

        $sql_union = "
            ($sql_baocaoloi)
            UNION ALL
            ($sql_phieunhapnvl)
            UNION ALL
            ($sql_phieuxuatnvl)
            UNION ALL
            ($sql_phieuxuattp)
            UNION ALL
            ($sql_yeucaunvl)
            UNION ALL
            ($sql_yeucauqc)
            UNION ALL
            ($sql_yeucaunhapkho)
            UNION ALL
            ($sql_khsx)
            UNION ALL
            ($sql_donhang)
            UNION ALL
            ($sql_ghinhanthanhpham)
        ";

        $sql_final = "SELECT * FROM ($sql_union) AS TongHop WHERE 1=1";

        $params = [];
        $types = "";

        if (!empty($start) && !empty($end)) {
            $sql_final .= " AND ngay_tao BETWEEN ? AND ?";
            $params[] = $start;
            $params[] = $end;
            $types .= "ss";
        }

        if ($loai != 'all' && !empty($loai)) {
            $sql_final .= " AND loai_phieu_key = ?";
            $params[] = $loai;
            $types .= "s";
        }

        $sql_final .= " ORDER BY ngay_tao DESC";

        $stmt = $this->conn->prepare($sql_final);

        if (!$stmt) {
            error_log("Lỗi prepare SQL tổng hợp: " . $this->conn->error);
            return [];
        }

        if (count($params) > 0) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getDetail($id, $type)
    {
        $data = ['info' => null, 'items' => []];

        try {
            switch ($type) {
                case 'baocaoloi':
                    $stmt = $this->conn->prepare("SELECT * FROM baocaoloi WHERE maBaoCao = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();
                    break;

                case 'phieunhapnvl':
                    $stmt = $this->conn->prepare(
                        "SELECT pn.*, n.tenNVL, n.donViTinh 
                         FROM phieunhapnvl pn 
                         LEFT JOIN nvl n ON pn.maNVL = n.maNVL 
                         WHERE pn.maPNVL = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();
                    break;

                case 'phieuxuatnvl':
                    $stmt = $this->conn->prepare("SELECT * FROM phieuxuatnvl WHERE maPhieu = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();

                    $stmt_items = $this->conn->prepare(
                        "SELECT ct.*, x.tenXuong 
                         FROM chitietphieuxuatnvl ct
                         LEFT JOIN xuong x ON ct.maXuong = x.maXuong
                         WHERE ct.maPhieu = ?"
                    );
                    $stmt_items->bind_param("i", $id);
                    $stmt_items->execute();
                    $data['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;

                case 'phieuxuattp':
                    $stmt = $this->conn->prepare(
                        "SELECT pxtp.*, sp.tenSanPham 
                         FROM phieuxuatthanhpham pxtp 
                         LEFT JOIN san_pham sp ON pxtp.maSanPham = sp.maSanPham 
                         WHERE pxtp.maPhieuXuat = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();
                    break;

                case 'yeucaunvl':
                    $stmt = $this->conn->prepare("SELECT * FROM phieuyeucaucungcapnvl WHERE maYCCC = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();

                    $stmt_items = $this->conn->prepare("SELECT * FROM chitiet_phieuyeucaucapnvl WHERE maYCCC = ?");
                    $stmt_items->bind_param("i", $id);
                    $stmt_items->execute();
                    $data['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;

                case 'yeucauqc':
                    $stmt = $this->conn->prepare("SELECT * FROM phieuyeucaukiemtrachatluong WHERE maYC = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();

                    $stmt_items = $this->conn->prepare("SELECT * FROM chitietphieuyeucaukiemtrachatluong WHERE maYC = ?");
                    $stmt_items->bind_param("i", $id);
                    $stmt_items->execute();
                    $data['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;

                case 'yeucaunhapkho':
                    $stmt = $this->conn->prepare("SELECT * FROM phieuyeucaunhapkhonvl WHERE maYCNK = ?");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();

                    $stmt_items = $this->conn->prepare(
                        "SELECT ct.*, n.tenNVL, n.donViTinh 
                         FROM chitiet_phieuyeucaunhapkhonvl ct 
                         JOIN nvl n ON ct.maNVL = n.maNVL 
                         WHERE ct.maYCNK = ?"
                    );
                    $stmt_items->bind_param("s", $id);
                    $stmt_items->execute();
                    $data['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;

                case 'khsx':
                    $stmt = $this->conn->prepare(
                        "SELECT kh.*, nd.hoTen AS tenNguoiLap
                         FROM kehoachsanxuat kh
                         LEFT JOIN nguoidung nd ON kh.maND = nd.maND
                         WHERE kh.maKHSX = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();

                    $stmt_items = $this->conn->prepare(
                        "SELECT ct.*, n.tenNVL 
                         FROM chitietkehoachsanxuat ct 
                         LEFT JOIN nvl n ON ct.maNVL = n.maNVL
                         WHERE ct.maKHSX = ?"
                    );
                    $stmt_items->bind_param("i", $id);
                    $stmt_items->execute();
                    $data['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
                    break;

                case 'donhang':
                    $stmt = $this->conn->prepare(
                        "SELECT dh.*, sp.tenSanPham 
                         FROM donhangsanxuat dh 
                         LEFT JOIN san_pham sp ON dh.maSanPham = sp.maSanPham 
                         WHERE dh.maDonHang = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();
                    break;

                case 'ghinhanthanhpham':
                    $stmt = $this->conn->prepare(
                        "SELECT g.*, k.tenKHSX, sp.tenSanPham, nd.hoTen AS tenNhanVien 
                         FROM ghinhanthanhphamtheongay g
                         LEFT JOIN kehoachsanxuat k ON g.maKHSX = k.maKHSX
                         LEFT JOIN san_pham sp ON g.maSanPham = sp.maSanPham
                         LEFT JOIN nguoidung nd ON g.maNhanVien = nd.maND
                         WHERE g.maGhiNhan = ?"
                    );
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $data['info'] = $stmt->get_result()->fetch_assoc();
                    break;

                default:
                    $data['info'] = ['Lỗi' => 'Loại báo cáo này không hợp lệ hoặc chưa được hỗ trợ xem chi tiết.'];
            }
        } catch (Exception $e) {
            $data['info'] = ['Lỗi CSDL' => $e->getMessage()];
        }

        return $data;
    }
}
