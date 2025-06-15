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
} 