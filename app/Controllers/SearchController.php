<?php

namespace App\Controllers;

use App\Repositories\CompanyRepository;
use App\Repositories\ContactRepository;
use App\Repositories\DealRepository;
use App\Repositories\TaskRepository;
use App\Core\View;

class SearchController
{
    public function index()
    {
        $query = $_GET['q'] ?? '';

        $results = [];

        if ($query) {
            $results['contacts'] = (new ContactRepository())->search($query);
            $results['companies'] = (new CompanyRepository())->search($query);
            $results['deals'] = (new DealRepository())->search($query);
            $results['tasks'] = (new TaskRepository())->search($query);
        }

        echo (new View())->render('search/index', [
            'query' => $query,
            'results' => $results
        ]);
    }
} 