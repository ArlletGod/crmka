<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">CRM</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if ((new \App\Core\Auth())->check()): ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/contacts">Contacts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/companies">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/deals">Deals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tasks">Tasks</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/reports/sales-by-manager">Sales by Manager</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                        $auth = new \App\Core\Auth();
                        $user = $auth->user();
                    ?>
                    <?php if ($user): ?>
                        <?php if (isset($user['name'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?= htmlspecialchars($user['name']) ?></a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/logout" class="btn btn-outline-secondary">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <a href="/login" class="btn btn-primary me-2">Login</a>
                <a href="/register" class="btn btn-secondary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 