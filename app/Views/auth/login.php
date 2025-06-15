<h1><?= __('login') ?></h1>

<form action="/login" method="POST">
    <div class="mb-3">
        <label for="email" class="form-label"><?= __('email_address') ?></label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label"><?= __('password') ?></label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('login') ?></button>
</form> 