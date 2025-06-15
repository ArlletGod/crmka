<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Services\ReportService;

class HomeController
{
    private ReportService $reportService;
    private Auth $auth;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->auth = new Auth();
    }

    public function index()
    {
        $userId = $this->auth->id();
        if (!$userId) {
            // Should not happen due to AuthMiddleware, but as a safeguard
            header('Location: /login');
            exit;
        }

        $dashboardData = $this->reportService->getDashboardData($userId);

        // Prepare chart data
        $labels = array_column($dashboardData['pipeline'], 'stage_name');
        $data = array_column($dashboardData['pipeline'], 'deal_count');

        $viewData = [
            'stats' => $dashboardData['stats'],
            'tasks' => $dashboardData['tasks'],
            'chartLabels' => json_encode($labels),
            'chartData' => json_encode($data),
        ];

        echo (new View())->render('dashboard/index', $viewData);
    }
} 