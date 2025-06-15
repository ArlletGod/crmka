<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\CompanyService;

class CompanyController
{
    private CompanyService $companyService;

    public function __construct()
    {
        $this->companyService = new CompanyService();
    }

    public function index()
    {
        echo (new View())->render('companies/index');
    }
} 