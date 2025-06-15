<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ContactRepository;
use App\Repositories\DealRepository;
use App\Repositories\PipelineStageRepository;
use App\Services\DealService;

class DealController
{
    private DealRepository $dealRepository;
    private PipelineStageRepository $stageRepository;
    private ContactRepository $contactRepository;
    private DealService $dealService;

    public function __construct()
    {
        $this->dealRepository = new DealRepository();
        $this->stageRepository = new PipelineStageRepository();
        $this->contactRepository = new ContactRepository();
        $this->dealService = new DealService();
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

    public function create()
    {
        $contacts = $this->contactRepository->findAll();
        $stages = $this->stageRepository->findAll();
        echo (new View())->render('deals/create', [
            'contacts' => $contacts,
            'stages' => $stages
        ]);
    }

    public function store()
    {
        $this->dealService->createDeal($_POST);
        header('Location: /deals');
        exit;
    }
} 