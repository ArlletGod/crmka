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
        $stmt = $this->db->query("
            SELECT c.*, co.name as company_name 
            FROM contacts c
            LEFT JOIN companies co ON c.company_id = co.id
            ORDER BY c.created_at DESC
        ");
        
        $contactsData = $stmt->fetchAll();
        $contacts = [];

        foreach ($contactsData as $row) {
            $contact = new Contact();
            $contact->id = (int) $row['id'];
            $contact->name = $row['name'];
            $contact->email = $row['email'];
            $contact->phone = $row['phone'];
            $contact->company_id = $row['company_id'] ? (int)$row['company_id'] : null;
            $contact->created_at = $row['created_at'];
            $contact->updated_at = $row['updated_at'];
            $contact->company_name = $row['company_name'];
            $contacts[] = $contact;
        }

        return $contacts;
    }

    public function create(Contact $contact): ?Contact
    {
        $stmt = $this->db->prepare(
            "INSERT INTO contacts (name, email, phone, company_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        $success = $stmt->execute([
            $contact->name,
            $contact->email,
            $contact->phone,
            $contact->company_id,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
        ]);

        if ($success) {
            $id = (int)$this->db->lastInsertId();
            return $this->findById($id);
        }
        return null;
    }

    public function findById(int $id): ?Contact
    {
        $stmt = $this->db->prepare("
            SELECT c.*, co.name as company_name 
            FROM contacts c
            LEFT JOIN companies co ON c.company_id = co.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        $contact = new Contact();
        $contact->id = (int) $data['id'];
        $contact->name = $data['name'];
        $contact->email = $data['email'];
        $contact->phone = $data['phone'];
        $contact->company_id = isset($data['company_id']) ? (int)$data['company_id'] : null;
        $contact->created_at = $data['created_at'];
        $contact->updated_at = $data['updated_at'];
        $contact->company_name = $data['company_name'];
        
        return $contact;
    }

    public function update(Contact $contact): ?Contact
    {
        $stmt = $this->db->prepare(
            "UPDATE contacts SET name = ?, email = ?, phone = ?, company_id = ?, updated_at = ? WHERE id = ?"
        );

        $success = $stmt->execute([
            $contact->name,
            $contact->email,
            $contact->phone,
            $contact->company_id,
            date('Y-m-d H:i:s'),
            $contact->id
        ]);

        if ($success) {
            return $this->findById($contact->id);
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM contacts")->fetchAll();
    }

    public function search(string $query)
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE name LIKE ? OR email LIKE ?");
        $stmt->execute(["%$query%", "%$query%"]);
        return $stmt->fetchAll();
    }
} 