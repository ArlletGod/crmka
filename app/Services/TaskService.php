<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }

    public function getAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }
    
    public function createTask(array $data): ?Task
    {
        $auth = new Auth();
        if (!$auth->check()) {
            return null;
        }

        $task = new Task();
        $task->name = htmlspecialchars($data['name']);
        $task->description = !empty($data['description']) ? htmlspecialchars($data['description']) : null;
        $task->due_date = !empty($data['due_date']) ? $data['due_date'] : null;
        $task->status = 'pending'; // Default status
        $task->user_id = (int)($data['user_id'] ?? $auth->id());
        $task->contact_id = !empty($data['contact_id']) ? (int)$data['contact_id'] : null;
        $task->deal_id = !empty($data['deal_id']) ? (int)$data['deal_id'] : null;

        return $this->taskRepository->create($task);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    public function updateTask(int $id, array $data): ?Task
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            return null;
        }

        $task->name = htmlspecialchars($data['name'] ?? $task->name);
        $task->description = isset($data['description']) ? htmlspecialchars($data['description']) : $task->description;
        $task->due_date = isset($data['due_date']) ? $data['due_date'] : $task->due_date;
        $task->status = htmlspecialchars($data['status'] ?? $task->status);
        $task->user_id = (int)($data['user_id'] ?? $task->user_id);
        $task->contact_id = isset($data['contact_id']) ? (int)$data['contact_id'] : $task->contact_id;
        $task->deal_id = isset($data['deal_id']) ? (int)$data['deal_id'] : $task->deal_id;
        
        return $this->taskRepository->update($task);
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }
} 