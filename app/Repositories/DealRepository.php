<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Deal;
use PDO;

class DealRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT 
                d.*, 
                c.name as contact_name,
                u.name as user_name
            FROM deals d
            JOIN contacts c ON d.contact_id = c.id
            JOIN users u ON d.user_id = u.id
            ORDER BY d.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_CLASS, Deal::class);
    }

    public function create(Deal $deal): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO deals (name, budget, contact_id, user_id, stage_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $deal->name,
            $deal->budget,
            $deal->contact_id,
            $deal->user_id,
            $deal->stage_id,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }
} 