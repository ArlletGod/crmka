<h1>Create Deal</h1>

<form action="/deals" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Deal Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="budget" class="form-label">Budget</label>
        <input type="number" step="0.01" class="form-control" id="budget" name="budget">
    </div>
    <div class="mb-3">
        <label for="contact_id" class="form-label">Contact</label>
        <select class="form-select" id="contact_id" name="contact_id" required>
            <option value="">Select a contact</option>
            <?php foreach ($contacts as $contact): ?>
                <option value="<?= $contact->id ?>"><?= htmlspecialchars($contact->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="stage_id" class="form-label">Initial Stage</label>
        <select class="form-select" id="stage_id" name="stage_id" required>
            <option value="">Select a stage</option>
            <?php foreach ($stages as $stage): ?>
                <option value="<?= $stage->id ?>"><?= htmlspecialchars($stage->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Deal</button>
</form> 