<?php

namespace App\Models;

class Deal
{
    public ?int $id = null;
    public ?string $name = null;
    public ?float $budget = null;
    public ?float $original_budget = null;
    public ?string $currency = null;
    public ?int $contact_id = null;
    public ?int $user_id = null;
    public string $status;
    public int $stage_id;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // For JOINs
    public ?string $contact_name = null;
    public ?string $user_name = null;
} 