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

    public function createDeal(array $data): bool
    {
        $auth = new Auth();
        if (!$auth->check()) {
            return false; // Or throw an exception
        }

        $deal = new Deal();
        $deal->name = htmlspecialchars($data['name']);
        $deal->budget = !empty($data['budget']) ? (float)$data['budget'] : null;
        $deal->contact_id = (int)$data['contact_id'];
        $deal->stage_id = (int)$data['stage_id'];
        $deal->user_id = $auth->id();

        return $this->dealRepository->create($deal);
    }
} 