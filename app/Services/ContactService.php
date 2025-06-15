<?php

namespace App\Services;

use App\Repositories\ContactRepository;

class ContactService
{
    private ContactRepository $contactRepository;

    public function __construct()
    {
        $this->contactRepository = new ContactRepository();
    }

    public function getAllContacts(): array
    {
        return $this->contactRepository->findAll();
    }

    public function createContact(array $data): ?\App\Models\Contact
    {
        // Здесь должна быть более серьезная валидация
        if (empty($data['name'])) {
            return null;
        }

        $contact = new \App\Models\Contact();
        $contact->name = htmlspecialchars($data['name']);
        $contact->email = !empty($data['email']) ? htmlspecialchars($data['email']) : null;
        $contact->phone = !empty($data['phone']) ? htmlspecialchars($data['phone']) : null;
        $contact->company_id = !empty($data['company_id']) ? (int)$data['company_id'] : null;

        return $this->contactRepository->create($contact);
    }

    public function getContactById(int $id): ?\App\Models\Contact
    {
        return $this->contactRepository->findById($id);
    }

    public function updateContact(int $id, array $data): ?\App\Models\Contact
    {
        $contact = $this->contactRepository->findById($id);
        if (!$contact) {
            return null;
        }

        $contact->name = htmlspecialchars($data['name'] ?? $contact->name);
        $contact->email = isset($data['email']) ? htmlspecialchars($data['email']) : $contact->email;
        $contact->phone = isset($data['phone']) ? htmlspecialchars($data['phone']) : $contact->phone;
        $contact->company_id = !empty($data['company_id']) ? (int)$data['company_id'] : $contact->company_id;

        return $this->contactRepository->update($contact);
    }

    public function deleteContact(int $id): bool
    {
        return $this->contactRepository->delete($id);
    }
} 