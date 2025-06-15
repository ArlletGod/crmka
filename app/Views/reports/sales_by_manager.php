<h1><?= __('sales_by_manager_report') ?></h1>

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
            <td>$<?= number_format($row['total_budget'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 