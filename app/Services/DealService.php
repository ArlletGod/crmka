<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\Deal;
use App\Repositories\DealRepository;

class DealService
{
    private DealRepository $dealRepository;

    public function __construct()
    {
        $this->dealRepository = new DealRepository();
    }

    public function createDeal(array $data): ?Deal
    {
        $auth = new Auth();
        if (!$auth->check()) {
            return null; // Or throw an exception
        }

        $deal = new Deal();
        $deal->name = htmlspecialchars($data['name']);
        $deal->budget = !empty($data['budget']) ? (float)$data['budget'] : null;
        $deal->contact_id = (int)$data['contact_id'];
        $deal->stage_id = (int)$data['stage_id'];
        $deal->user_id = (int)$data['user_id'];

        $status = 'in_progress';
        if ($deal->stage_id === 4) {
            $status = 'won';
        } elseif ($deal->stage_id === 5) {
            $status = 'lost';
        }
        $deal->status = $status;

        return $this->dealRepository->create($deal);
    }

    public function updateDealStage(int $dealId, int $newStageId): bool
    {
        $status = 'in_progress';
        if ($newStageId === 4) { // Assuming stage ID 4 is 'Won'
            $status = 'won';
        } elseif ($newStageId === 5) { // Assuming stage ID 5 is 'Lost'
            $status = 'lost';
        }

        return $this->dealRepository->updateStageAndStatus($dealId, $newStageId, $status);
    }
} 