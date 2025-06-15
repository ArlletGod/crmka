<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\ContactService;
use App\Services\CompanyService;

class ContactController
{
    public function __construct()
    {
        // Dependencies could be injected here in a real app
    }

    public function index()
    {
        echo (new View())->render('contacts/index');
    }
} 