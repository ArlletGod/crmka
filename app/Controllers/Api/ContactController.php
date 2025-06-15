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
            $this->jsonError('Contact not found', 404);
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

        $newContact = $this->contactService->createContact($data);

        if ($newContact) {
            // After creating, we need the version with the company_name
            $contactWithDetails = $this->contactService->getContactById($newContact->id);
            $this->jsonResponse($contactWithDetails, 201);
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

        $updatedContact = $this->contactService->updateContact($id, $data);

        if ($updatedContact) {
            // After updating, we need the version with the company_name
            $contactWithDetails = $this->contactService->getContactById($updatedContact->id);
            $this->jsonResponse($contactWithDetails);
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