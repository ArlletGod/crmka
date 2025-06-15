<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\ContactRepository;
use App\Repositories\DealRepository;
use App\Repositories\TaskRepository;
use App\Services\TaskService;

class TaskController
{
    private TaskRepository $taskRepository;
    private TaskService $taskService;
    private ContactRepository $contactRepository;
    private DealRepository $dealRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
        $this->taskService = new TaskService();
        $this->contactRepository = new ContactRepository();
        $this->dealRepository = new DealRepository();
    }

    public function index()
    {
        $tasks = $this->taskRepository->findAll();
        echo (new View())->render('tasks/index', ['tasks' => $tasks]);
    }

    public function create()
    {
        $contacts = $this->contactRepository->findAll();
        $deals = $this->dealRepository->findAll();
        echo (new View())->render('tasks/create', [
            'contacts' => $contacts,
            'deals' => $deals
        ]);
    }

    public function store()
    {
        $this->taskService->createTask($_POST);
        header('Location: /tasks');
        exit;
    }

    public function edit(int $id)
    {
        $task = $this->taskService->getTaskById($id);
        if (!$task) {
            // Handle not found, maybe a 404 page
            header("Location: /tasks");
            exit;
        }

        $contacts = $this->contactRepository->findAll();
        $deals = $this->dealRepository->findAll();

        echo (new View())->render('tasks/edit', [
            'task' => $task,
            'contacts' => $contacts,
            'deals' => $deals
        ]);
    }

    public function update(int $id)
    {
        $this->taskService->updateTask($id, $_POST);
        header('Location: /tasks');
        exit;
    }

    public function destroy(int $id)
    {
        $this->taskService->deleteTask($id);
        header('Location: /tasks');
        exit;
    }
} 