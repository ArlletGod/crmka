<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Repositories\CommentRepository;

class CommentController
{
    public function store()
    {
        // Basic validation
        if (empty($_POST['body']) || empty($_POST['commentable_id']) || empty($_POST['commentable_type'])) {
            // Handle error, maybe redirect back with an error message
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $auth = new Auth();
        if (!$user = $auth->user()) {
            // Not logged in
            header('Location: /login');
            exit;
        }

        $data = [
            'body' => $_POST['body'],
            'user_id' => $user['id'],
            'commentable_id' => (int)$_POST['commentable_id'],
            'commentable_type' => $_POST['commentable_type'],
        ];

        (new CommentRepository())->create($data);

        // Redirect back to the previous page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} 