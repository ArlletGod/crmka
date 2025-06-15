<?php

namespace App\Core;

class View
{
    public function render(string $viewPath, array $data = []): string
    {
        // e.g. 'contacts/index' -> 'path/to/app/Views/contacts/index.php'
        $viewFullPath = __DIR__ . '/../Views/' . $viewPath . '.php';
        $layoutPath = __DIR__ . '/../Views/layouts/main.php';

        if (!file_exists($viewFullPath) || !file_exists($layoutPath)) {
            // В реальном приложении здесь лучше выбрасывать исключение
            return "View or layout file not found.";
        }

        ob_start();
        extract($data);
        require $viewFullPath;
        $content = ob_get_clean();

        ob_start();
        require $layoutPath;
        $layoutContent = ob_get_clean();

        return str_replace('{{content}}', $content, $layoutContent);
    }
} 