<?php
function get_status_badge_class($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'bg-secondary';
        case 'in_progress':
            return 'bg-primary';
        case 'completed':
        case 'won':
            return 'bg-success';
        case 'lost':
            return 'bg-danger';
        default:
            return 'bg-light text-dark';
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= __('search_results_for') ?> "<?= htmlspecialchars($query) ?>"</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($results['contacts']) && empty($results['companies']) && empty($results['deals']) && empty($results['tasks'])): ?>
                        <p><?= __('no_results_found') ?></p>
                    <?php else: ?>

                        <?php if (!empty($results['contacts'])): ?>
                            <h4><?= __('contacts') ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th><?= __('name') ?></th>
                                    <th><?= __('email') ?></th>
                                    <th><?= __('phone') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['contacts'] as $contact): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($contact['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($contact['email'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($contact['phone'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($results['companies'])): ?>
                            <h4 class="mt-4"><?= __('companies') ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th><?= __('name') ?></th>
                                    <th><?= __('industry') ?></th>
                                    <th><?= __('city') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['companies'] as $company): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($company['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($company['industry'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($company['city'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($results['deals'])): ?>
                            <h4 class="mt-4"><?= __('deals') ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th><?= __('name') ?></th>
                                    <th><?= __('budget') ?></th>
                                    <th><?= __('status') ?></th>
                                    <th><?= __('company') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['deals'] as $deal): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($deal['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($deal['budget'] ?? '') ?></td>
                                        <td>
                                            <span class="badge <?= get_status_badge_class($deal['status']) ?>">
                                                <?= htmlspecialchars($deal['status'] ?? '') ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($deal['company_name'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($results['tasks'])): ?>
                            <h4 class="mt-4"><?= __('tasks') ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th><?= __('name') ?></th>
                                    <th><?= __('due_date') ?></th>
                                    <th><?= __('status') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['tasks'] as $task): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($task['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($task['due_date'] ?? '') ?></td>
                                        <td>
                                            <span class="badge <?= get_status_badge_class($task['status']) ?>">
                                                <?= htmlspecialchars($task['status'] ?? '') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 