<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\ContactService;

class ContactController
{
    private ContactService $contactService;

    public function __construct()
    {
        $this->contactService = new ContactService();
    }

    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        
        $view = new View();
        echo $view->render('contacts/index', ['contacts' => $contacts]);
    }

    public function create()
    {
        $view = new View();
        echo $view->render('contacts/create');
    }

    public function store()
    {
        $success = $this->contactService->createContact($_POST);

        if ($success) {
            header('Location: /contacts');
        } else {
            // Можно добавить сообщение об ошибке
            header('Location: /contacts/create');
        }
        exit;
    }
} 