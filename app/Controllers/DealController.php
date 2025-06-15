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
        // Custom error handler to ensure JSON response on fatal error
        set_error_handler(function($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return;
            }
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'A server error occurred during deal creation.',
                'error' => [
                    'message' => $message,
                    'file' => $file,
                    'line' => $line,
                ]
            ]);
            exit;
        });

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['name']) || empty($data['contact_id']) || empty($data['stage_id'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Missing required fields.']);
                exit;
            }

            $newDeal = $this->dealService->createDeal($data);

            if ($newDeal) {
                header('Content-Type: application/json');
                http_response_code(201);
                echo json_encode($newDeal);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to create deal. The service returned a null response.']);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'An internal server error occurred.',
                'error' => $e->getMessage()
            ]);
        } finally {
            // Restore the previous error handler
            restore_error_handler();
        }
        exit;
    }

    public function move(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $newStageId = $data['new_stage_id'] ?? null;

        if (!$newStageId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'New stage ID is required.']);
            return;
        }

        $success = $this->dealService->updateDealStage($id, (int)$newStageId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
} 