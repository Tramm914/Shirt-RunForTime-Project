<?php
require_once 'app/models/ThietBiModel.php';
require_once 'app/models/NguyenVatLieuModel.php';

class SearchController {
    public function search() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $xuong = isset($_GET['xuong']) ? (int)$_GET['xuong'] : 0;

        header('Content-Type: application/json; charset=utf-8');

        if ($type === 'thietbi') {
            $model = new ThietBiModel();

            // ✅ Nếu chỉ có xưởng (không nhập keyword)
            if ($xuong > 0 && empty($keyword)) {
                $result = $model->layThietBiTheoXuong($xuong);
                echo json_encode($result);
                return;
            }

            // ✅ Nếu có keyword và xưởng
            if ($xuong > 0 && !empty($keyword)) {
                $result = $model->timThietBiTheoTuKhoaVaXuong($keyword, $xuong);
                echo json_encode($result);
                return;
            }

            // ✅ Nếu chỉ có keyword (tìm tự do)
            if (!empty($keyword)) {
                $result = $model->timThietBiTheoTuKhoa($keyword);
                echo json_encode($result);
                return;
            }

            echo json_encode([]);
            return;
        }

        if ($type === 'nvl') {
            $model = new NguyenVatLieuModel();
            $result = $model->goiYNVL($keyword);
            echo json_encode($result);
            return;
        }

        echo json_encode(["status" => "error", "message" => "Type không hợp lệ"]);
    }
}
?>
