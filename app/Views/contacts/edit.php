<h1>Edit Contact: <?= htmlspecialchars($contact->name) ?></h1>

<form action="/contacts/update/<?= $contact->id ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($contact->name) ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($contact->email ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($contact->phone ?? '') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form> 