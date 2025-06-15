<?php

namespace App\Services;

use App\Core\Auth;
use App\Core\Config;
use App\Models\Deal;
use App\Repositories\DealRepository;
use App\Services\CurrencyService;

class DealService
{
    private DealRepository $dealRepository;
    private CurrencyService $currencyService;

    public function __construct()
    {
        $this->dealRepository = new DealRepository();
        $this->currencyService = new CurrencyService();
    }

    public function createDeal(array $data): ?Deal
    {
        $auth = new Auth();
        if (!$auth->check()) {
            return null; // Or throw an exception
        }

        $deal = new Deal();
        $deal->name = htmlspecialchars($data['name']);

        $originalBudget = !empty($data['budget']) ? (float)$data['budget'] : null;
        $currency = $data['currency'] ?? Config::get('base_currency');
        $baseCurrency = Config::get('base_currency');

        $deal->original_budget = $originalBudget;
        $deal->currency = $currency;

        if ($originalBudget !== null && $currency !== $baseCurrency) {
            $rates = $this->currencyService->getRates($currency);
            // Convert submitted budget to the base currency
            $deal->budget = $this->currencyService->convert($originalBudget, $currency, $baseCurrency, $rates);
        } else {
            $deal->budget = $originalBudget;
        }

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

    public function getDealById(int $id): ?object
    {
        return $this->dealRepository->findById($id);
    }
} 