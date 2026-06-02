<?php
// app/controllers/BaoCaoTongHopController.php
require_once 'app/models/BaoCaoTongHopModel.php';

class BaoCaoTongHopController
{
    private $model;

    public function __construct()
    {
        $this->model = new BaoCaoTongHopModel();
    }

    public function index()
    {
        $model = new BaoCaoTongHopModel();

        // SỬA: Không gán ngày mặc định nữa. Nếu không có POST, để rỗng.
        $loai = $_POST['loai_bao_cao'] ?? 'all';
        $start = $_POST['start_date'] ?? '';
        $end = $_POST['end_date'] ?? '';

        // Gọi Model (Model đã có sẵn logic: nếu $start, $end rỗng thì sẽ không lọc ngày)
        $danhSachBaoCao = $model->getBaoCaoTongHop($loai, $start, $end);

        $filter_values = [
            'loai' => $loai,
            'start' => $start,
            'end' => $end
        ];

        require_once 'app/views/baoCaoTongHop.php';
    }

    public function ajaxGetDetails()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_GET['id'] ?? 0;
        $type = $_GET['type'] ?? '';

        if (empty($id) || empty($type)) {
            echo json_encode(['error' => 'Thiếu ID hoặc loại báo cáo.']);
            exit;
        }

        $model = new BaoCaoTongHopModel();
        $details = $model->getDetail($id, $type);

        echo json_encode($details);
        exit;
    }
}
