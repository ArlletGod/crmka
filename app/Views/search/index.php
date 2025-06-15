<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Results for "<?= htmlspecialchars($query) ?>"</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($results['contacts']) && empty($results['companies']) && empty($results['deals']) && empty($results['tasks'])): ?>
                        <p>No results found.</p>
                    <?php else: ?>

                        <?php if (!empty($results['contacts'])): ?>
                            <h4>Contacts</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['contacts'] as $contact): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($contact['name']) ?></td>
                                        <td><?= htmlspecialchars($contact['email']) ?></td>
                                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($results['companies'])): ?>
                            <h4 class="mt-4">Companies</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Industry</th>
                                    <th>City</th>
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
                            <h4 class="mt-4">Deals</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Company</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['deals'] as $deal): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($deal['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($deal['budget'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($deal['status'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($deal['company_name'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if (!empty($results['tasks'])): ?>
                            <h4 class="mt-4">Tasks</h4>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($results['tasks'] as $task): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($task['name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($task['due_date'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($task['status'] ?? '') ?></td>
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