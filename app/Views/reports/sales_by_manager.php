<h1><?= __('sales_by_manager_report') ?></h1>

<?php
    use App\Core\Config;
    // --- Currency Conversion Setup ---
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    $currencyService = new \App\Services\CurrencyService();
    $baseCurrency = Config::get('base_currency');
    $displayCurrency = $_SESSION['currency'] ?? $baseCurrency;
    $currencies = Config::get('currencies');
    $displaySymbol = $currencies[$displayCurrency] ?? '$';

    $rates = $_SESSION['currency_rates'] ?? null;
    if ($rates === null || ($_SESSION['currency_rates_base'] ?? '') !== $baseCurrency) {
        $rates = $currencyService->getRates($baseCurrency);
        $_SESSION['currency_rates'] = $rates;
        $_SESSION['currency_rates_base'] = $baseCurrency;
    }

    function formatDisplayCurrency($amountInBase, $displayCurrency, $displaySymbol, $rates) {
        if (empty($rates) || !isset($rates[$displayCurrency])) { return $displaySymbol . number_format($amountInBase, 2); }
        $convertedAmount = $amountInBase * $rates[$displayCurrency];
        return $displaySymbol . number_format($convertedAmount, 2);
    }
    // --- End Currency Setup ---
?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th><?= __('manager_name') ?></th>
        <th><?= __('won_deals_count') ?></th>
        <th><?= __('total_budget_won_deals') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($reportData as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['manager_name']) ?></td>
            <td><?= $row['deal_count'] ?></td>
            <td><?= formatDisplayCurrency($row['total_budget'], $displayCurrency, $displaySymbol, $rates) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 