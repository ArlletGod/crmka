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
        <a class="navbar-brand" href="/"><?= __('app_name') ?></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php
            $auth = new \App\Core\Auth();
            if ($auth->check()):
            ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/contacts"><?= __('contacts') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/companies"><?= __('companies') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/deals"><?= __('deals') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/tasks"><?= __('tasks') ?></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= __('reports') ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/reports/sales-by-manager"><?= __('sales_by_manager') ?></a></li>
                        <li><a class="dropdown-item" href="/reports/pipeline-funnel"><?= __('pipeline_funnel') ?></a></li>
                    </ul>
                </li>
            </ul>
            <?php endif; ?>

            <!-- Search form in the middle -->
            <form class="d-flex mx-auto" action="/search" method="GET">
                <input class="form-control me-2" type="search" name="q" placeholder="<?= __('search_placeholder') ?>" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"><?= __('search') ?></button>
            </form>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php
                // --- Currency Setup ---
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $currencies = \App\Core\Config::get('currencies', ['USD' => '$']);
                $current_currency = $_SESSION['currency'] ?? \App\Core\Config::get('base_currency');
                // --- End Currency Setup ---
                ?>
                <!-- Currency Switcher -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarCurrencyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $current_currency ?> (<?= $currencies[$current_currency] ?? '' ?>)
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarCurrencyDropdown">
                        <?php foreach ($currencies as $code => $symbol): ?>
                            <li><a class="dropdown-item" href="/currency?code=<?= $code ?>"><?= $code ?> (<?= $symbol ?>)</a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <!-- Language Switcher -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarLangDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= strtoupper(\App\Core\Translator::getLang()) ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarLangDropdown">
                        <li><a class="dropdown-item" href="/lang?lang=en">EN</a></li>
                        <li><a class="dropdown-item" href="/lang?lang=ru">RU</a></li>
                        <li><a class="dropdown-item" href="/lang?lang=ro">RO</a></li>
                    </ul>
                </li>
                <?php if ($user = $auth->user()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= htmlspecialchars($user['name']) ?></a>
                    </li>
                    <li class="nav-item">
                         <a href="/logout" class="btn btn-outline-secondary"><?= __('logout') ?></a>
                    </li>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary me-2"><?= __('login') ?></a>
                    <a href="/register" class="btn btn-secondary"><?= __('register') ?></a>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 