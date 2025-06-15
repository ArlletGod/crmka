<h1>Sales by Manager Report</h1>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Manager Name</th>
        <th>Won Deals Count</th>
        <th>Total Budget (Won Deals)</th>
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