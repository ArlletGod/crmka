<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\CompanyService;
use App\Services\ContactService;

class ContactController
{
    private ContactService $contactService;
    private CompanyService $companyService;

    public function __construct()
    {
        $this->contactService = new ContactService();
        $this->companyService = new CompanyService();
    }

    public function index()
    {
        $contacts = $this->contactService->getAllContacts();
        
        $view = new View();
        echo $view->render('contacts/index', ['contacts' => $contacts]);
    }

    public function create()
    {
        $companies = $this->companyService->getAllCompanies();
        $view = new View();
        echo $view->render('contacts/create', ['companies' => $companies]);
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

    public function edit(int $id)
    {
        $contact = $this->contactService->getContactById($id);
        if (!$contact) {
            http_response_code(404);
            echo 'Contact not found';
            exit;
        }
        $companies = $this->companyService->getAllCompanies();

        $view = new View();
        echo $view->render('contacts/edit', ['contact' => $contact, 'companies' => $companies]);
    }

    public function update(int $id)
    {
        $this->contactService->updateContact($id, $_POST);
        header('Location: /contacts');
        exit;
    }

    public function delete(int $id)
    {
        $this->contactService->deleteContact($id);
        header('Location: /contacts');
        exit;
    }
} 