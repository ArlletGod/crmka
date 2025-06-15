<h1>Create Contact</h1>

<form action="/contacts" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <div class="mb-3">
        <label for="company_id" class="form-label">Company</label>
        <select class="form-select" id="company_id" name="company_id">
            <option value="">Select a company</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company->id ?>"><?= htmlspecialchars($company->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form> 