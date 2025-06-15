<?php

namespace App\Models;

class Note
{
    public ?int $id = null;
    public string $content;
    public int $user_id;
    public int $notable_id;
    public string $notable_type;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // For JOINs
    public ?string $user_name = null;
} 