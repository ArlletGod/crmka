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
        <div class="collapse navbar-collapse">
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
            </ul>
            <div class="d-flex">
                <?php $auth = new \App\Core\Auth(); ?>
                <?php if ($auth->check()): ?>
                    <span class="navbar-text me-3">
                        Welcome, <?= htmlspecialchars($auth->user()['id']) ?> (<?= htmlspecialchars($auth->user()['role'] ?? 'user') ?>)
                    </span>
                    <a href="/logout" class="btn btn-outline-secondary">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary me-2">Login</a>
                    <a href="/register" class="btn btn-secondary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    {{content}}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 