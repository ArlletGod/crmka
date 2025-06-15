<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Task;
use PDO;

class TaskRepository
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
                t.*, 
                u.name as user_name, 
                c.name as contact_name, 
                d.name as deal_name
            FROM tasks t
            JOIN users u ON u.id = t.user_id
            LEFT JOIN contacts c ON c.id = t.contact_id
            LEFT JOIN deals d ON d.id = t.deal_id
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(Task $task): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks (name, description, due_date, status, user_id, contact_id, deal_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $task->name,
            $task->description,
            $task->due_date,
            $task->status,
            $task->user_id,
            $task->contact_id,
            $task->deal_id,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task ?: null;
    }

    public function update(Task $task): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE tasks SET 
                name = ?, 
                description = ?, 
                due_date = ?, 
                status = ?, 
                contact_id = ?, 
                deal_id = ?, 
                updated_at = ?
            WHERE id = ?"
        );

        return $stmt->execute([
            $task->name,
            $task->description,
            $task->due_date,
            $task->status,
            $task->contact_id,
            $task->deal_id,
            date('Y-m-d H:i:s'),
            $task->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 