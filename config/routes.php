<?php

use App\Controllers\ContactController;
use App\Controllers\HomeController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/contacts', [ContactController::class, 'index']]
]; 