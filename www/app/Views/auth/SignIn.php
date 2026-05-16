<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Sign In<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="mb-4 text-center">Sign in</h2>

                <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger"><?= esc($errors['general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="/sign-in">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                                type="text"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= esc($old_email ?? '') ?>"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input
                                type="password"
                                name="password"
                                class="form-control"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign in</button>
                </form>

                <p class="text-center mt-3 text-secondary">
                    Don't have an account? <a href="/sign-up" class="text-decoration-none">Sign up</a>
                </p>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>