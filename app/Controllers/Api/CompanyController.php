<?php

namespace App\Controllers\Api;

use App\Services\CompanyService;

class CompanyController
{
    private CompanyService $companyService;

    public function __construct()
    {
        $this->companyService = new CompanyService();
    }

    private function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    public function index()
    {
        $companies = $this->companyService->getAllCompanies();
        $this->jsonResponse($companies);
    }

    public function show(int $id)
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            $this->jsonResponse(['message' => 'Company not found'], 404);
        }
        $this->jsonResponse($company);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['name'])) {
            $this->jsonResponse(['message' => 'Name is required'], 400);
        }
        
        $newCompany = $this->companyService->createCompany($data);

        if ($newCompany) {
            $this->jsonResponse($newCompany, 201);
        } else {
            $this->jsonResponse(['message' => 'Failed to create company'], 500);
        }
    }

    public function update(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $updatedCompany = $this->companyService->updateCompany($id, $data);
        
        if ($updatedCompany) {
            $this->jsonResponse($updatedCompany);
        } else {
            $this->jsonResponse(['message' => 'Failed to update company or company not found'], 500);
        }
    }

    public function destroy(int $id)
    {
        $success = $this->companyService->deleteCompany($id);
        if ($success) {
            $this->jsonResponse(['message' => 'Company deleted successfully']);
        } else {
            $this->jsonResponse(['message' => 'Failed to delete company or company not found'], 500);
        }
    }
} 