<?php

namespace App\Models;

class Deal
{
    public ?int $id = null;
    public string $name;
    public ?float $budget = null;
    public string $status;
    public int $contact_id;
    public int $user_id;
    public int $stage_id;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // For JOINs
    public ?string $contact_name = null;
    public ?string $user_name = null;
} 