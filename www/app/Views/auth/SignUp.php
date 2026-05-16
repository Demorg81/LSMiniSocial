<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Sign Up<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h2 class="mb-4 text-center">Create account</h2>

                <form method="POST" action="/sign-up" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-secondary">(optional)</span></label>
                        <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="<?= esc($old_username ?? '') ?>"
                                placeholder="Leave blank to use email prefix"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
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

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input
                                type="password"
                                name="password"
                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                        >
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Repeat password <span class="text-danger">*</span></label>
                        <input
                                type="password"
                                name="repeat_password"
                                class="form-control <?= isset($errors['repeat_password']) ? 'is-invalid' : '' ?>"
                        >
                        <?php if (isset($errors['repeat_password'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['repeat_password']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Profile picture <span class="text-secondary">(optional)</span></label>
                        <input type="file" name="profile_pic" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign up</button>
                </form>

                <p class="text-center mt-3 text-secondary">
                    Already have an account? <a href="/sign-in" class="text-decoration-none">Sign in</a>
                </p>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>