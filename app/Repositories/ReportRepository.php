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

    public function getDashboardStats(): array
    {
        $stats = [];
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        // New contacts in last 30 days
        $stmt = $this->db->prepare("SELECT COUNT(id) as count FROM contacts WHERE created_at >= ?");
        $stmt->execute([$thirtyDaysAgo]);
        $stats['new_contacts_last_30_days'] = $stmt->fetchColumn();

        // Won deals stats in last 30 days
        $stmt = $this->db->prepare("SELECT COUNT(id) as count, SUM(budget) as sum FROM deals WHERE status = 'won' AND updated_at >= ?");
        $stmt->execute([$thirtyDaysAgo]);
        $wonDeals = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['won_deals_last_30_days_count'] = $wonDeals['count'] ?? 0;
        $stats['won_deals_last_30_days_sum'] = $wonDeals['sum'] ?? 0;

        return $stats;
    }

    public function getUpcomingTasks(int $userId, int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks 
            WHERE user_id = ? AND status = 'pending' AND due_date IS NOT NULL
            ORDER BY due_date ASC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 