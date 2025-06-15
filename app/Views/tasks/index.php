<h1>Tasks</h1>
<a href="/tasks/create" class="btn btn-primary mb-3">Add Task</a>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Assigned To</th>
        <th>Contact</th>
        <th>Deal</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= $task['id'] ?></td>
            <td><?= htmlspecialchars($task['name']) ?></td>
            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($task['status']) ?></span></td>
            <td><?= $task['due_date'] ? date('Y-m-d H:i', strtotime($task['due_date'])) : 'N/A' ?></td>
            <td><?= htmlspecialchars($task['user_name']) ?></td>
            <td>
                <?php if ($task['contact_id']): ?>
                    <a href="/contacts/<?= $task['contact_id'] ?>"><?= htmlspecialchars($task['contact_name']) ?></a>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($task['deal_id']): ?>
                    <?= htmlspecialchars($task['deal_name']) ?>
                <?php endif; ?>
            </td>
            <td>
                <a href="/tasks/<?= $task['id'] ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                <form action="/tasks/<?= $task['id'] ?>/delete" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 