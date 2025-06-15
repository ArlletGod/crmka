<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Company;
use PDO;

class CompanyRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM companies ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Company::class);
    }

    public function findById(int $id): ?Company
    {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $company = new Company();
        $company->id = (int)$data['id'];
        $company->name = $data['name'];
        $company->address = $data['address'];
        $company->phone = $data['phone'];
        return $company;
    }

    public function create(Company $company): ?Company
    {
        $stmt = $this->db->prepare("INSERT INTO companies (name, address, phone, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        $success = $stmt->execute([$company->name, $company->address, $company->phone, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        if ($success) {
            $id = (int)$this->db->lastInsertId();
            return $this->findById($id);
        }
        return null;
    }

    public function update(Company $company): ?Company
    {
        $stmt = $this->db->prepare("UPDATE companies SET name = ?, address = ?, phone = ?, updated_at = ? WHERE id = ?");
        $success = $stmt->execute([$company->name, $company->address, $company->phone, date('Y-m-d H:i:s'), $company->id]);
        if ($success) {
            return $this->findById($company->id);
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM companies WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 