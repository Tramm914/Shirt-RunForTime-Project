<?php
require_once 'app/models/CongViecModel.php';

class CongViecController
{
    private $model;

    public function __construct()
    {
        $this->model = new CongViecModel();
    }

    public function index()
    {
        $plans = $this->model->getApprovedPlans();
        require 'app/views/xemcongviec.php';
    }

    // Xem chi tiết KHSX (READ ONLY)
    public function detail()
    {
        if (!isset($_GET['id'])) {
            die('Thiếu mã kế hoạch');
        }

        $maKHSX = (int)$_GET['id'];

        // KHSX + người lập
        $plan = $this->model->getPlanById($maKHSX);

        // Đơn hàng gắn với KHSX
        $donHang = $this->model->getDonHangByKHSX($maKHSX);

        // Chi tiết theo xưởng
        $chiTiet = $this->model->getChiTietByKHSX($maKHSX);

        require 'app/views/chitietkehoach.php';
    }
}
