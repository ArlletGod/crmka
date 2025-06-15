<?php

namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    private ReportRepository $reportRepository;

    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
    }

    public function getSalesByManagerReport(): array
    {
        return $this->reportRepository->getSalesByManager();
    }

    public function getDealsCountByStageReport(): array
    {
        return $this->reportRepository->getDealsCountByStage();
    }
} 