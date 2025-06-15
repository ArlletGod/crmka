<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\PipelineStage;
use PDO;

class PipelineStageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM pipeline_stages ORDER BY sort_order ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, PipelineStage::class);
    }
} 