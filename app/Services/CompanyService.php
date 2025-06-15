<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;

class CompanyService
{
    private CompanyRepository $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
    }

    public function getAllCompanies(): array
    {
        return $this->companyRepository->findAll();
    }

    public function getCompanyById(int $id): ?Company
    {
        return $this->companyRepository->findById($id);
    }

    public function createCompany(array $data): bool
    {
        $company = new Company();
        $company->name = htmlspecialchars($data['name']);
        $company->address = !empty($data['address']) ? htmlspecialchars($data['address']) : null;
        $company->phone = !empty($data['phone']) ? htmlspecialchars($data['phone']) : null;

        return $this->companyRepository->create($company);
    }

    public function updateCompany(int $id, array $data): bool
    {
        $company = $this->companyRepository->findById($id);
        if (!$company) {
            return false;
        }

        $company->name = htmlspecialchars($data['name'] ?? $company->name);
        $company->address = isset($data['address']) ? htmlspecialchars($data['address']) : $company->address;
        $company->phone = isset($data['phone']) ? htmlspecialchars($data['phone']) : $company->phone;

        return $this->companyRepository->update($company);
    }

    public function deleteCompany(int $id): bool
    {
        return $this->companyRepository->delete($id);
    }
} 