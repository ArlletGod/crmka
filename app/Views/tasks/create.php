<h1>Create Task</h1>

<form action="/tasks" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Task Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="due_date" class="form-label">Due Date</label>
        <input type="datetime-local" class="form-control" id="due_date" name="due_date">
    </div>
    <div class="mb-3">
        <label for="contact_id" class="form-label">Related Contact (optional)</label>
        <select class="form-select" id="contact_id" name="contact_id">
            <option value="">None</option>
            <?php foreach ($contacts as $contact): ?>
                <option value="<?= $contact->id ?>"><?= htmlspecialchars($contact->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="deal_id" class="form-label">Related Deal (optional)</label>
        <select class="form-select" id="deal_id" name="deal_id">
            <option value="">None</option>
            <?php foreach ($deals as $deal): ?>
                <option value="<?= $deal->id ?>"><?= htmlspecialchars($deal->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Task</button>
</form> 