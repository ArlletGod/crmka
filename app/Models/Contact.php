<?php

namespace App\Models;

class Contact
{
    public ?int $id = null;
    public string $name;
    public ?string $email = null;
    public ?string $phone = null;
    public ?int $company_id = null;
    public ?string $company_name = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
} 