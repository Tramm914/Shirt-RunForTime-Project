<?php
require_once 'ketNoi.php';

class XemCaLamViecModel {
    private $conn;

    public function __construct() {
        $this->conn = (new KetNoi())->connect();
    }

    /**
     * Lấy mã người dùng (maND) từ mã tài khoản (maTK)
     * @param int $maTK Mã tài khoản
     * @return int|null Mã người dùng
     */
    private function getMaNDFromMaTK($maTK) {
        if (!$maTK) {
            return null;
        }

        $sql = "SELECT maND FROM nguoidung WHERE maTK = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn lấy maND: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $maTK);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row['maND'];
        }
        
        $stmt->close();
        return null;
    }

    /**
     * Chuyển đổi số thứ tự thành tên thứ trong tuần (tiếng Việt)
     * @param int $dayOfWeek Số thứ tự (0=Chủ nhật, 1=Thứ 2, ..., 6=Thứ 7)
     * @return string Tên thứ
     */
    private function getThuTrongTuan($dayOfWeek) {
        $thu = [
            0 => 'Chủ nhật',
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7'
        ];
        return $thu[$dayOfWeek] ?? 'Không xác định';
    }

    /**
     * Lấy tên ca làm việc từ mã ca
     * @param string $maCa Mã ca (CA_SANG, CA_CHIEU, CA_TOI)
     * @return string Tên ca
     */
    private function getTenCa($maCa) {
        $tenCa = [
            'CA_SANG' => 'Ca Sáng',
            'CA_CHIEU' => 'Ca Chiều',
            'CA_TOI' => 'Ca Tối'
        ];
        return $tenCa[$maCa] ?? $maCa;
    }

    /**
     * Lấy thứ tự ưu tiên của ca để sắp xếp
     * @param string $maCa Mã ca
     * @return int Thứ tự ưu tiên (1=Sáng, 2=Chiều, 3=Tối)
     */
    private function getThuTuCa($maCa) {
        $thuTu = [
            'CA_SANG' => 1,
            'CA_CHIEU' => 2,
            'CA_TOI' => 3
        ];
        return $thuTu[$maCa] ?? 99;
    }

    /**
     * Lấy lịch làm việc của nhân viên hiện tại trong tuần
     * @param int $maND Mã người dùng
     * @param int $weekOffset Số tuần offset (0 = tuần này, 1 = tuần tới)
     * @return array Danh sách ca làm việc
     */
    public function getLichLamViec($maND = null, $weekOffset = 0) {
        // Tính toán tuần dựa trên offset (0 = tuần này, 1 = tuần tới)
        $monday = date('Y-m-d', strtotime('monday this week +' . $weekOffset . ' week'));
        $sunday = date('Y-m-d', strtotime('sunday this week +' . $weekOffset . ' week'));
        
        // Query lấy dữ liệu từ 3 bảng: chitiet_lichlamviec, calamviec, nguoidung
        $sql = "SELECT 
                    ctl.ngayLam,
                    ctl.maCa,
                    c.gioBatDau,
                    c.gioKetThuc
                FROM chitiet_lichlamviec ctl
                INNER JOIN calamviec c ON ctl.maCa = c.maCa
                WHERE ctl.ngayLam BETWEEN ? AND ?";
        
        // Nếu có mã người dùng, lọc theo mã người dùng
        if ($maND) {
            $sql .= " AND ctl.maND = ?";
        }
        
        $sql .= " ORDER BY ctl.ngayLam ASC, c.gioBatDau ASC";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Lỗi chuẩn bị truy vấn lịch làm việc: " . $this->conn->error);
            return [];
        }

        if ($maND) {
            // Convert maND sang string vì trong database chitiet_lichlamviec.maND là char(10)
            $maNDStr = (string)$maND;
            $stmt->bind_param("sss", $monday, $sunday, $maNDStr);
        } else {
            $stmt->bind_param("ss", $monday, $sunday);
        }
        
        if (!$stmt->execute()) {
            error_log("Lỗi thực thi truy vấn lịch làm việc: " . $stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $tempData = [];
        
        // Lấy tất cả dữ liệu và lưu tạm
        while ($row = $result->fetch_assoc()) {
            $ngayKey = $row['ngayLam']; // Dùng làm key để group
            
            if (!isset($tempData[$ngayKey])) {
                $tempData[$ngayKey] = [
                    'ngayLam' => $row['ngayLam'],
                    'cas' => []
                ];
            }
            
            // Thêm ca vào mảng
            $tempData[$ngayKey]['cas'][] = [
                'maCa' => $row['maCa'],
                'gioBatDau' => $row['gioBatDau'],
                'gioKetThuc' => $row['gioKetThuc']
            ];
        }
        
        $stmt->close();
        
        // Xử lý và group dữ liệu theo ngày
        $data = [];
        foreach ($tempData as $ngayKey => $ngayData) {
            // Sắp xếp các ca theo thứ tự: Sáng → Chiều → Tối
            usort($ngayData['cas'], function($a, $b) {
                $thuTuA = $this->getThuTuCa($a['maCa']);
                $thuTuB = $this->getThuTuCa($b['maCa']);
                return $thuTuA - $thuTuB;
            });
            
            // Format ngày
            $ngayFormat = date('d/m/Y', strtotime($ngayData['ngayLam']));
            
            // Tính thứ trong tuần
            $dayOfWeek = date('w', strtotime($ngayData['ngayLam'])); // 0=Chủ nhật, 1=Thứ 2, ...
            $thu = $this->getThuTrongTuan($dayOfWeek);
            
            // Gộp các ca và thời gian thành chuỗi, cách nhau bằng dấu gạch ngang
            $caLamArray = [];
            $thoiGianArray = [];
            
            foreach ($ngayData['cas'] as $ca) {
                $caLamArray[] = $this->getTenCa($ca['maCa']);
                $thoiGianArray[] = date('H:i', strtotime($ca['gioBatDau'])) . '-' . date('H:i', strtotime($ca['gioKetThuc']));
            }
            
            $data[] = [
                'ngay' => $ngayFormat,
                'thu' => $thu,
                'caLam' => implode(' - ', $caLamArray),
                'thoiGian' => implode(' - ', $thoiGianArray)
            ];
        }
        
        // Sắp xếp lại theo ngày (đã được sắp xếp từ query, nhưng đảm bảo chắc chắn)
        usort($data, function($a, $b) {
            // Convert dd/mm/yyyy sang timestamp để so sánh
            $dateA = DateTime::createFromFormat('d/m/Y', $a['ngay']);
            $dateB = DateTime::createFromFormat('d/m/Y', $b['ngay']);
            if ($dateA && $dateB) {
                return $dateA->getTimestamp() - $dateB->getTimestamp();
            }
            return 0;
        });
        
        return $data;
    }

    /**
     * Lấy tóm tắt lịch làm việc
     * @param int $maND Mã người dùng
     * @param int $weekOffset Số tuần offset (0 = tuần này, 1 = tuần tới)
     */
    public function getTomTatLichLamViec($maND = null, $weekOffset = 0) {
        // Tính toán tuần dựa trên offset
        $monday = date('Y-m-d', strtotime('monday this week +' . $weekOffset . ' week'));
        $sunday = date('Y-m-d', strtotime('sunday this week +' . $weekOffset . ' week'));
        
        // Query để đếm tổng số ca
        $sql = "SELECT COUNT(*) as tongSoCa
                FROM chitiet_lichlamviec ctl
                WHERE ctl.ngayLam BETWEEN ? AND ?";
        
        if ($maND) {
            $maNDStr = (string)$maND;
            $sql .= " AND ctl.maND = ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            if ($maND) {
                $stmt->bind_param("sss", $monday, $sunday, $maNDStr);
            } else {
                $stmt->bind_param("ss", $monday, $sunday);
            }
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $tongSoCa = $row['tongSoCa'] ?? 0;
            } else {
                $tongSoCa = 0;
            }
            $stmt->close();
        } else {
            $tongSoCa = 0;
        }
        
        // Lấy lịch làm việc để tìm ca gần nhất
        $lichLamViec = $this->getLichLamViec($maND, $weekOffset);
        
        // Tìm ca gần nhất (ca đầu tiên trong danh sách đã sắp xếp)
        $caGanNhat = null;
        if (!empty($lichLamViec)) {
            $caGanNhat = $lichLamViec[0];
        }

        return [
            'tongSoCa' => $tongSoCa,
            'caGanNhat' => $caGanNhat
        ];
    }

    /**
     * Lấy tuần hiện tại (ngày bắt đầu và kết thúc)
     * @param int $weekOffset Số tuần offset (0 = tuần này, 1 = tuần tới)
     */
    public function getTuanHienTai($weekOffset = 0) {
        $monday = date('d/m/Y', strtotime('monday this week +' . $weekOffset . ' week'));
        $sunday = date('d/m/Y', strtotime('sunday this week +' . $weekOffset . ' week'));
        return [
            'batDau' => $monday,
            'ketThuc' => $sunday
        ];
    }

    /**
     * Lấy mã người dùng từ mã tài khoản (public method để controller sử dụng)
     */
    public function getMaNDFromMaTKPublic($maTK) {
        return $this->getMaNDFromMaTK($maTK);
    }
}
?>

