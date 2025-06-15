<?php

namespace App\Models;

class Task
{
    public int $id;
    public string $name;
    public ?string $description;
    public ?string $due_date;
    public string $status;
    public int $user_id;
    public ?int $contact_id;
    public ?int $deal_id;
    public string $created_at;
    public ?string $updated_at;
} 