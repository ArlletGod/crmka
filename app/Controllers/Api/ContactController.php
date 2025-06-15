<?php

namespace App\Controllers\Api;

use App\Services\ContactService;

class ContactController extends ApiController
{
    private ContactService $contactService;

    public function __construct()
    {
        $this->contactService = new ContactService();
    }

    public function index(): void
    {
        $contacts = $this->contactService->getAllContacts();
        $this->jsonResponse($contacts);
    }

    public function show(int $id): void
    {
        $contact = $this->contactService->getContactById($id);
        if (!$contact) {
            $this->jsonError('Contact not found');
            return;
        }
        $this->jsonResponse($contact);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->jsonError('Invalid JSON', 400);
            return;
        }

        $contactId = $this->contactService->createContact($data);

        if ($contactId) {
            $this->jsonResponse(['id' => $contactId, 'message' => 'Contact created successfully'], 201);
        } else {
            $this->jsonError('Failed to create contact', 400);
        }
    }

    public function update(int $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->jsonError('Invalid JSON', 400);
            return;
        }

        $success = $this->contactService->updateContact($id, $data);

        if ($success) {
            $this->jsonResponse(['message' => 'Contact updated successfully']);
        } else {
            $this->jsonError('Failed to update contact or contact not found', 400);
        }
    }

    public function destroy(int $id): void
    {
        $success = $this->contactService->deleteContact($id);

        if ($success) {
            $this->jsonResponse(['message' => 'Contact deleted successfully']);
        } else {
            $this->jsonError('Failed to delete contact or contact not found', 400);
        }
    }
} 