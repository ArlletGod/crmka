<h1>Edit Company: <?= htmlspecialchars($company->name) ?></h1>

<form action="/companies/update/<?= $company->id ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($company->name) ?>" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($company->address ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($company->phone ?? '') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form> 