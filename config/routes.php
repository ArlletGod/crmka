<?php

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/contacts', [ContactController::class, 'index']],
    ['GET', '/contacts/create', [ContactController::class, 'create']],
    ['POST', '/contacts', [ContactController::class, 'store']],
    ['GET', '/register', [AuthController::class, 'create']],
    ['POST', '/register', [AuthController::class, 'store']],
]; 