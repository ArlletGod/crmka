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
} 