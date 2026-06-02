<?php
require_once 'app/models/ThongKeKhoTPModel.php';

class ThongKeKhoTPController {
    private $model;

    public function __construct() {
        $this->model = new ThongKeKhoTPModel();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $from   = $_GET['from'] ?? null;
        $to     = $_GET['to'] ?? null;

        $data = $this->model->thongKe($search, $from, $to);
        require 'app/views/thongkekhotp.php';
    }

    public function export() {
        $search = $_GET['search'] ?? '';
        $from   = $_GET['from'] ?? null;
        $to     = $_GET['to'] ?? null;

        $this->model->exportCSV($search, $from, $to);
    }
}
