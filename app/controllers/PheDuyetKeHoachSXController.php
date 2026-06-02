<?php
require_once './app/models/PheDuyetKeHoachSXModel.php';

class PheDuyetKeHoachSXController {
    private $model;

    public function __construct() {
        $this->model = new PheDuyetKeHoachSXModel();
    }

    public function index() {
        $plans = $this->model->getPendingPlans();
        require_once __DIR__ . '/../views/pheDuyetKeHoachSX.php';
    }

    // ✅ Xử lý phê duyệt / từ chối
    public function duyetKeHoach() {
        header('Content-Type: application/json; charset=utf-8');
        
        // Debug: Log thông tin request
        error_log("POST data: " . print_r($_POST, true));
        error_log("Session user: " . print_r($_SESSION['user'] ?? 'No session', true));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
            exit;
        }
        
        $maKH = intval($_POST['maKeHoach'] ?? 0);
        $ghiChu = $_POST['ghiChu'] ?? '';
        $trangThai = $_POST['trangThai'] ?? 'Đã duyệt';
        $nguoiThucHien = $_SESSION['user']['hoTen'] ?? 'Hệ thống';

        if ($maKH <= 0) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã kế hoạch hoặc mã không hợp lệ: ' . $maKH]);
            exit;
        }

        try {
            // ✅ Cập nhật trạng thái kế hoạch
            $result = $this->model->updatePlanStatus($maKH, $trangThai, $ghiChu);
            
            if (!$result) {
                throw new Exception("Không thể cập nhật trạng thái kế hoạch");
            }

            // ✅ Ghi lịch sử phê duyệt
            $this->model->addApprovalHistory($maKH, $trangThai, $ghiChu, $nguoiThucHien);

            echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
        } catch (Exception $e) {
            error_log("Error in duyetKeHoach: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }


    // ✅ AJAX chi tiết kế hoạch
    public function ajaxGetPlanDetail() {
        header('Content-Type: application/json; charset=utf-8');

        $maKHSX = intval($_GET['maKHSX'] ?? 0);
        
        if ($maKHSX <= 0) {
            echo json_encode(['error' => 'Mã kế hoạch không hợp lệ'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $planDetails = $this->model->getPlanDetails($maKHSX);
        
        if (!$planDetails) {
            echo json_encode(['error' => 'Không tìm thấy kế hoạch'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($planDetails, JSON_UNESCAPED_UNICODE);
        exit;
    }
    public function ajaxGetApprovalHistory() {
    header('Content-Type: application/json; charset=utf-8');
    $maKHSX = intval($_GET['maKHSX'] ?? 0);
    echo json_encode($this->model->getApprovalHistory($maKHSX), JSON_UNESCAPED_UNICODE);
    exit;
}

}
?>
