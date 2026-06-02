<?php
require_once 'app/models/PhieuYeuCauNhapKhoModel.php';

class PheDuyetYeuCauNhapKhoController
{
    private $model;

    public function __construct()
    {
        $this->model = new PhieuYeuCauNhapKhoModel();
    }

    /**
     * Hiển thị trang phê duyệt phiếu yêu cầu nhập kho NVL
     */
    public function index()
    {
        // Lấy danh sách các phiếu ở trạng thái "Chờ duyệt"
        $pendingRequests = $this->model->getPendingRequests();
        
        require_once 'app/views/pheDuyetYeuCauNhapKho.php';
    }

    /**
     * Xử lý phê duyệt phiếu
     */
    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');

        $maYCNK = $_POST['maYCNK'] ?? null;
        
        // Lấy tên người duyệt từ session
        $nguoiDuyet = isset($_SESSION['user']['hoTen']) ? $_SESSION['user']['hoTen'] : 
                      (isset($_SESSION['user']['tenNV']) ? $_SESSION['user']['tenNV'] : 'Admin');

        if (!$maYCNK) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu yêu cầu']);
            return;
        }

        $result = $this->model->updateStatus($maYCNK, 'Đã duyệt', $nguoiDuyet);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Phê duyệt phiếu thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi khi phê duyệt']);
        }
    }

    /**
     * Xử lý từ chối phiếu
     */
    public function reject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');

        $maYCNK = $_POST['maYCNK'] ?? null;
        $lyDoTuChoi = $_POST['lyDoTuChoi'] ?? '';
        
        // Lấy tên người duyệt từ session
        $nguoiDuyet = isset($_SESSION['user']['hoTen']) ? $_SESSION['user']['hoTen'] : 
                      (isset($_SESSION['user']['tenNV']) ? $_SESSION['user']['tenNV'] : 'Admin');

        if (!$maYCNK) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu yêu cầu']);
            return;
        }

        $result = $this->model->updateStatus($maYCNK, 'Từ chối', $nguoiDuyet, $lyDoTuChoi);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã từ chối phiếu']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi khi từ chối']);
        }
    }

    /**
     * Lấy chi tiết phiếu để xem trước khi duyệt
     */
    public function getDetails()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $maYCNK = $_GET['maYCNK'] ?? null;

        if (!$maYCNK) {
            echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu']);
            return;
        }

        $details = $this->model->getDetailsByRequest($maYCNK);
        
        if ($details) {
            echo json_encode(['success' => true, 'data' => $details]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy chi tiết phiếu']);
        }
    }
}
