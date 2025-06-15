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

    public function createContact(array $data): bool
    {
        // Здесь должна быть более серьезная валидация
        if (empty($data['name'])) {
            return false;
        }

        $contact = new \App\Models\Contact();
        $contact->name = htmlspecialchars($data['name']);
        $contact->email = !empty($data['email']) ? htmlspecialchars($data['email']) : null;
        $contact->phone = !empty($data['phone']) ? htmlspecialchars($data['phone']) : null;

        return $this->contactRepository->create($contact);
    }
} 