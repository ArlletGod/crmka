<?php

namespace App\Controllers\Api;

use App\Services\TaskService;

class TaskController extends ApiController
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        $this->jsonResponse($tasks);
    }

    public function show(int $id)
    {
        $task = $this->taskService->getTaskById($id);
        if (!$task) {
            $this->jsonError('Task not found', 404);
            return;
        }
        $this->jsonResponse($task);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['name']) || empty($data['user_id'])) {
            $this->jsonError('Invalid data: name and user_id are required.', 400);
            return;
        }

        $newTask = $this->taskService->createTask($data);

        if ($newTask) {
            $this->jsonResponse($newTask, 201);
        } else {
            $this->jsonError('Failed to create task', 500);
        }
    }

    public function update(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->jsonError('Invalid data', 400);
            return;
        }
        
        $updatedTask = $this->taskService->updateTask($id, $data);

        if ($updatedTask) {
            $this->jsonResponse($updatedTask);
        } else {
            $this->jsonError('Failed to update task or task not found', 500);
        }
    }

    public function destroy(int $id)
    {
        $success = $this->taskService->deleteTask($id);
        if ($success) {
            $this->jsonResponse(['message' => 'Task deleted successfully']);
        } else {
            $this->jsonError('Failed to delete task', 500);
        }
    }
} 