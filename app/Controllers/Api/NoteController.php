<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Services\NoteService;

class NoteController
{
    private NoteService $noteService;

    public function __construct()
    {
        $this->noteService = new NoteService();
    }

    public function getNotes(Request $request)
    {
        $params = $request->getJsonBody();
        $notableType = $params['notable_type'] ?? null;
        $notableId = isset($params['notable_id']) ? (int)$params['notable_id'] : null;

        if (!$notableType || !$notableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $notes = $this->noteService->getNotesFor($notableType, $notableId);
        echo json_encode($notes);
    }

    public function createNote(Request $request)
    {
        $data = $request->getJsonBody();
        $content = $data['content'] ?? null;
        $notableType = $data['notable_type'] ?? null;
        $notableId = isset($data['notable_id']) ? (int)$data['notable_id'] : null;

        if (!$content || !$notableType || !$notableId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $note = $this->noteService->createNote($content, $notableType, $notableId);

        if ($note) {
            http_response_code(201);
            echo json_encode($note);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Could not create note']);
        }
    }
} 