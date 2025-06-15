<h1>Companies</h1>
<a href="/companies/create" class="btn btn-primary mb-3">Add Company</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($companies as $company): ?>
        <tr>
            <td><?= htmlspecialchars($company->id) ?></td>
            <td><?= htmlspecialchars($company->name) ?></td>
            <td><?= htmlspecialchars($company->address ?? '') ?></td>
            <td><?= htmlspecialchars($company->phone ?? '') ?></td>
            <td>
                <a href="/companies/edit/<?= $company->id ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <?php if ((new \App\Core\Auth())->user()['role'] === 'admin'): ?>
                <a href="/companies/delete/<?= $company->id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 