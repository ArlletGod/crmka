<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\DealRepository;
use App\Repositories\PipelineStageRepository;

class DealController
{
    private DealRepository $dealRepository;
    private PipelineStageRepository $stageRepository;

    public function __construct()
    {
        $this->dealRepository = new DealRepository();
        $this->stageRepository = new PipelineStageRepository();
    }

    public function index()
    {
        $deals = $this->dealRepository->findAll();
        $stages = $this->stageRepository->findAll();

        // Group deals by stage
        $dealsByStage = [];
        foreach ($stages as $stage) {
            $dealsByStage[$stage->id] = [
                'name' => $stage->name,
                'deals' => []
            ];
        }

        foreach ($deals as $deal) {
            $dealsByStage[$deal->stage_id]['deals'][] = $deal;
        }

        echo (new View())->render('deals/index', ['stages' => $stages, 'dealsByStage' => $dealsByStage]);
    }
} 