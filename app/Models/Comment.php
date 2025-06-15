<?php

namespace App\Models;

class Comment
{
    public int $id;
    public string $body;
    public int $user_id;
    public int $commentable_id;
    public string $commentable_type;
    public string $created_at;
    public string $updated_at;
} 