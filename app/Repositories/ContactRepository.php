<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Contact;
use PDO;

class ContactRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * @return Contact[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC");
        
        $contacts = [];
        while ($row = $stmt->fetch()) {
            $contact = new Contact();
            $contact->id = (int) $row['id'];
            $contact->name = $row['name'];
            $contact->email = $row['email'];
            $contact->phone = $row['phone'];
            $contact->created_at = $row['created_at'];
            $contact->updated_at = $row['updated_at'];
            $contacts[] = $contact;
        }

        return $contacts;
    }

    public function create(Contact $contact): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO contacts (name, email, phone, created_at, updated_at) VALUES (?, ?, ?, ?, ?)"
        );
        
        return $stmt->execute([
            $contact->name,
            $contact->email,
            $contact->phone,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
        ]);
    }
} 