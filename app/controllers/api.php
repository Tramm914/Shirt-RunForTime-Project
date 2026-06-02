<?php
require_once __DIR__ . '/../models/YeuCauKiemTraChatLuongModel.php';


$model = new YeuCauKiemTraChatLuongModel();

if ($_GET['action'] === 'getMaterials') {
    $maKHSX = intval($_GET['maKHSX']);
    echo json_encode($model->getMaterialsByPlan($maKHSX));
}
?>
