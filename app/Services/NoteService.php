<?php

namespace App\Services;

use App\Core\Auth;
use App\Models\Note;
use App\Repositories\NoteRepository;

class NoteService
{
    private NoteRepository $noteRepository;
    private Auth $auth;

    public function __construct()
    {
        $this->noteRepository = new NoteRepository();
        $this->auth = new Auth();
    }

    public function getNotesFor(string $notableType, int $notableId): array
    {
        return $this->noteRepository->findFor($notableType, $notableId);
    }

    public function createNote(string $content, string $notableType, int $notableId): ?Note
    {
        if (!$this->auth->check()) {
            return null; // Or throw exception
        }

        $note = new Note();
        $note->content = htmlspecialchars($content);
        $note->notable_type = $notableType;
        $note->notable_id = $notableId;
        $note->user_id = $this->auth->id();

        $newNote = $this->noteRepository->create($note);
        
        if ($newNote) {
            // Attach user name for immediate display
            $newNote->user_name = $this->auth->user()['name'];
        }

        return $newNote;
    }
} 