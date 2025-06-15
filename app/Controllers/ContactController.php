<?php

namespace App\Controllers;

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
        
        // Временный вывод, пока не созданы представления
        header('Content-Type: application/json');
        echo json_encode($contacts);
    }
} 