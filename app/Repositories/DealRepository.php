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

    public function findById(int $id): ?Deal
    {
        $stmt = $this->db->prepare("
            SELECT 
                d.*, 
                c.name as contact_name,
                u.name as user_name
            FROM deals d
            JOIN contacts c ON d.contact_id = c.id
            JOIN users u ON d.user_id = u.id
            WHERE d.id = ?
        ");
        $stmt->execute([$id]);
        $deal = $stmt->fetchObject(Deal::class);
        return $deal ?: null;
    }

    public function create(Deal $deal): ?Deal
    {
        $stmt = $this->db->prepare(
            "INSERT INTO deals (name, budget, original_budget, currency, status, contact_id, user_id, stage_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $success = $stmt->execute([
            $deal->name,
            $deal->budget,
            $deal->original_budget,
            $deal->currency,
            $deal->status,
            $deal->contact_id,
            $deal->user_id,
            $deal->stage_id,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);

        if ($success) {
            $id = (int)$this->db->lastInsertId();
            return $this->findById($id);
        }
        return null;
    }

    public function updateStageAndStatus(int $dealId, int $newStageId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE deals SET stage_id = ?, status = ?, updated_at = ? WHERE id = ?");
        return $stmt->execute([$newStageId, $status, date('Y-m-d H:i:s'), $dealId]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE deals SET name = ?, budget = ?, original_budget = ?, currency = ?, status = ?, contact_id = ?, user_id = ?, stage_id = ? WHERE id = ?");
        $stmt->execute([
            $data['name'],
            $data['budget'],
            $data['original_budget'],
            $data['currency'],
            $data['status'],
            $data['contact_id'],
            $data['user_id'],
            $data['stage_id'],
            $id
        ]);
        return $stmt->rowCount() > 0;
    }

    public function search(string $query)
    {
        $sql = "SELECT d.*, c.name as company_name 
                FROM deals d
                LEFT JOIN contacts contact ON d.contact_id = contact.id
                LEFT JOIN companies c ON contact.company_id = c.id
                WHERE d.name LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll();
    }
}
