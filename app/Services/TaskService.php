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

    public function createTask(array $data): bool
    {
        $auth = new Auth();
        if (!$auth->check()) {
            return false;
        }

        $task = new Task();
        $task->name = htmlspecialchars($data['name']);
        $task->description = !empty($data['description']) ? htmlspecialchars($data['description']) : null;
        $task->due_date = !empty($data['due_date']) ? $data['due_date'] : null;
        $task->status = 'pending';
        $task->user_id = $auth->id();
        $task->contact_id = !empty($data['contact_id']) ? (int)$data['contact_id'] : null;
        $task->deal_id = !empty($data['deal_id']) ? (int)$data['deal_id'] : null;

        return $this->taskRepository->create($task);
    }

    public function getTaskById(int $id): ?array
    {
        return $this->taskRepository->findById($id);
    }

    public function updateTask(int $id, array $data): bool
    {
        $taskData = $this->taskRepository->findById($id);
        if (!$taskData) {
            return false;
        }

        $task = new Task();
        $task->id = $id;
        $task->name = htmlspecialchars($data['name']);
        $task->description = !empty($data['description']) ? htmlspecialchars($data['description']) : null;
        $task->due_date = !empty($data['due_date']) ? $data['due_date'] : null;
        $task->status = htmlspecialchars($data['status']);
        $task->contact_id = !empty($data['contact_id']) ? (int)$data['contact_id'] : null;
        $task->deal_id = !empty($data['deal_id']) ? (int)$data['deal_id'] : null;
        
        // user_id is not updated as the assignee should not change on simple edit
        
        return $this->taskRepository->update($task);
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }
} 