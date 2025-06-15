<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRMKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            flex-shrink: 0;
        }
        .content {
            flex-grow: 1;
        }
    </style>
</head>
<body>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-person-workspace me-2"></i>
        <span class="fs-4">CRMKA</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/" class="nav-link text-white <?= ($_SERVER['REQUEST_URI'] == '/') ? 'active' : '' ?>" aria-current="page">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/deals" class="nav-link text-white <?= (str_starts_with($_SERVER['REQUEST_URI'], '/deals')) ? 'active' : '' ?>">
                <i class="bi bi-kanban me-2"></i>
                Deals
            </a>
        </li>
        <li>
            <a href="/tasks" class="nav-link text-white <?= (str_starts_with($_SERVER['REQUEST_URI'], '/tasks')) ? 'active' : '' ?>">
                <i class="bi bi-card-checklist me-2"></i>
                Tasks
            </a>
        </li>
        <li>
            <a href="/contacts" class="nav-link text-white <?= (str_starts_with($_SERVER['REQUEST_URI'], '/contacts')) ? 'active' : '' ?>">
                <i class="bi bi-person-lines-fill me-2"></i>
                Contacts
            </a>
        </li>
        <li>
            <a href="/companies" class="nav-link text-white <?= (str_starts_with($_SERVER['REQUEST_URI'], '/companies')) ? 'active' : '' ?>">
                <i class="bi bi-building me-2"></i>
                Companies
            </a>
        </li>
        <li>
             <a href="/reports/sales-by-manager" class="nav-link text-white <?= (str_starts_with($_SERVER['REQUEST_URI'], '/reports')) ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-line me-2"></i>
                Reports
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?php echo $_SESSION['user_name'] ?? 'User'; ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
        </ul>
    </div>
</div>

<main class="content p-4">
    <?php echo $content; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 