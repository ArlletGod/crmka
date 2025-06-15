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
        return $stmt->fetchAll(PDO::FETCH_CLASS, Task::class);
    }

    public function create(Task $task): ?Task
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks (name, description, due_date, status, user_id, contact_id, deal_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $success = $stmt->execute([
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

        if ($success) {
            $id = (int)$this->db->lastInsertId();
            return $this->findById($id);
        }
        return null;
    }

    public function findById(int $id): ?Task
    {
        $stmt = $this->db->prepare("
            SELECT
                t.*,
                u.name as user_name,
                c.name as contact_name,
                d.name as deal_name
            FROM tasks t
            JOIN users u ON u.id = t.user_id
            LEFT JOIN contacts c ON c.id = t.contact_id
            LEFT JOIN deals d ON d.id = t.deal_id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        $task = $stmt->fetchObject(Task::class);
        return $task ?: null;
    }

    public function update(Task $task): ?Task
    {
        $stmt = $this->db->prepare(
            "UPDATE tasks SET 
                name = ?, 
                description = ?, 
                due_date = ?, 
                status = ?, 
                contact_id = ?, 
                deal_id = ?,
                user_id = ?,
                updated_at = ?
            WHERE id = ?"
        );

        $success = $stmt->execute([
            $task->name,
            $task->description,
            $task->due_date,
            $task->status,
            $task->contact_id,
            $task->deal_id,
            $task->user_id,
            date('Y-m-d H:i:s'),
            $task->id
        ]);

        if ($success) {
            return $this->findById($task->id);
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM tasks")->fetchAll();
    }

    public function search(string $query)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE name LIKE ? OR description LIKE ?");
        $stmt->execute(["%$query%", "%$query%"]);
        return $stmt->fetchAll();
    }
} 