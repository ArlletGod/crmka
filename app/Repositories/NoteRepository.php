<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Note;
use PDO;

class NoteRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findFor(string $notableType, int $notableId): array
    {
        $stmt = $this->db->prepare("
            SELECT n.*, u.name as user_name
            FROM notes n
            JOIN users u ON u.id = n.user_id
            WHERE n.notable_type = ? AND n.notable_id = ?
            ORDER BY n.created_at DESC
        ");
        $stmt->execute([$notableType, $notableId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Note::class);
    }

    public function create(Note $note): ?Note
    {
        $stmt = $this->db->prepare(
            "INSERT INTO notes (content, user_id, notable_id, notable_type, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $success = $stmt->execute([
            $note->content,
            $note->user_id,
            $note->notable_id,
            $note->notable_type,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);

        if ($success) {
            $id = (int)$this->db->lastInsertId();
            // A simple findById would be better, but for now this is ok
            // We are not returning the object with user_name here, but it's a new note, so not critical.
            $note->id = $id;
            return $note;
        }
        return null;
    }
} 