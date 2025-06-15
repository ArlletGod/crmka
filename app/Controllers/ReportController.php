<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\ReportService;

class ReportController
{
    private ReportService $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function salesByManager()
    {
        $reportData = $this->reportService->getSalesByManagerReport();
        echo (new View())->render('reports/sales_by_manager', ['reportData' => $reportData]);
    }
} 