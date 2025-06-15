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

    public function pipelineFunnel()
    {
        $reportData = $this->reportService->getDealsCountByStageReport();

        // Prepare data for Chart.js
        $labels = array_column($reportData, 'stage_name');
        $data = array_column($reportData, 'deal_count');

        echo (new View())->render('reports/pipeline_funnel', [
            'chartLabels' => json_encode($labels),
            'chartData' => json_encode($data)
        ]);
    }
} 