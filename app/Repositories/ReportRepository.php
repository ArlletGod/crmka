<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ReportRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getSalesByManager(): array
    {
        $stmt = $this->db->query("
            SELECT
                u.name as manager_name,
                COUNT(d.id) as deal_count,
                SUM(d.budget) as total_budget
            FROM users u
            JOIN deals d ON u.id = d.user_id
            WHERE d.status = 'won' -- Assuming you have a 'won' status for deals
            GROUP BY u.name
            ORDER BY total_budget DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDealsCountByStage(): array
    {
        $stmt = $this->db->query("
            SELECT
                ps.name as stage_name,
                COUNT(d.id) as deal_count
            FROM pipeline_stages ps
            LEFT JOIN deals d ON ps.id = d.stage_id
            GROUP BY ps.id, ps.name
            ORDER BY ps.sort_order ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 