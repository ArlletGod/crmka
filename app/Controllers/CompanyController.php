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
        $companies = $this->companyService->getAllCompanies();
        echo (new View())->render('companies/index', ['companies' => $companies]);
    }

    public function create()
    {
        echo (new View())->render('companies/create');
    }

    public function store()
    {
        $this->companyService->createCompany($_POST);
        header('Location: /companies');
        exit;
    }

    public function edit(int $id)
    {
        $company = $this->companyService->getCompanyById($id);
        echo (new View())->render('companies/edit', ['company' => $company]);
    }

    public function update(int $id)
    {
        $this->companyService->updateCompany($id, $_POST);
        header('Location: /companies');
        exit;
    }

    public function delete(int $id)
    {
        $this->companyService->deleteCompany($id);
        header('Location: /companies');
        exit;
    }
} 