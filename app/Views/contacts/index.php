<h1>Contacts</h1>
<a href="/contacts/create" class="btn btn-primary mb-3">Add Contact</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
        <tr>
            <td><?= htmlspecialchars($contact->id) ?></td>
            <td><?= htmlspecialchars($contact->name) ?></td>
            <td><?= htmlspecialchars($contact->email ?? '') ?></td>
            <td><?= htmlspecialchars($contact->phone ?? '') ?></td>
            <td>
                <a href="/contacts/edit/<?= $contact->id ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <a href="/contacts/delete/<?= $contact->id ?>" class="btn btn-sm btn-outline-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 