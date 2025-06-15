<?php

namespace App\Controllers\Api;

class ApiController
{
    protected function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function jsonError(string $message, int $statusCode = 404): void
    {
        $this->jsonResponse(['error' => $message], $statusCode);
    }
} 