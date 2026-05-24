<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row g-4">

    <!-- ── LEFT: profile info ────────────────────────────────────────────── -->
    <div class="col-md-4">
        <div class="card p-4">

            <!-- Avatar -->
            <div class="text-center mb-4">
                <?php if (!empty($user['profile_pic'])): ?>
                    <img
                        src="/<?= esc($user['profile_pic']) ?>"
                        alt="avatar"
                        class="rounded-circle mb-3"
                        style="width:100px; height:100px; object-fit:cover;"
                    >
                <?php else: ?>
                    <div
                        class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:100px; height:100px; background:#e94560; font-size:2.5rem; font-weight:bold;"
                    >
                        <?= esc(strtoupper(substr($user['username'], 0, 1))) ?>
                    </div>
                <?php endif; ?>
                <h5 class="mb-0"><?= esc($user['username']) ?></h5>
                <small class="text-secondary"><?= esc($user['email']) ?></small>
            </div>

            <!-- Edit form -->
            <form method="POST" action="/profile" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update">

                <div class="mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="username"
                        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                        value="<?= esc($user['username']) ?>"
                    >
                    <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">New password <span class="text-secondary small">(leave blank to keep current)</span></label>
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
                    <label class="form-label">Repeat new password</label>
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
                    <label class="form-label">Profile picture</label>
                    <input type="file" name="profile_pic" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary w-100">Save changes</button>
            </form>

            <hr class="my-4">

            <!-- Logout -->
            <a href="/sign-out" class="btn btn-outline-light w-100 mb-2">Sign out</a>

            <!-- Delete account -->
            <form method="POST" action="/profile" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-outline-danger w-100">Delete account</button>
            </form>

        </div>
    </div>

    <!-- ── RIGHT: user posts ─────────────────────────────────────────────── -->
    <div class="col-md-8">
        <h4 class="mb-3">My posts</h4>

        <?php if (empty($posts)): ?>
            <p class="text-secondary">You haven't published anything yet. <a href="/post/create">Create your first post</a>.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 p-3">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <small class="text-secondary"><?= esc($post['created_at']) ?></small>
                        <div class="d-flex gap-2">
                            <a href="/post/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-light">Edit</a>
                            <form method="POST" action="/post/delete/<?= $post['id'] ?>" onsubmit="return confirm('Delete this post?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>

                    <p class="mb-2"><?= esc($post['content']) ?></p>

                    <?php if (!empty($post['image'])): ?>
                        <img
                            src="/<?= esc($post['image']) ?>"
                            alt="post image"
                            class="img-fluid rounded mb-2"
                            style="max-height:200px; width:100%; object-fit:contain;"
                        >
                    <?php endif; ?>

                    <div class="d-flex gap-3 text-secondary small">
                        <span>&#9825; <?= (int)$post['like_count'] ?> likes</span>
                        <span>&#128172; <?= (int)$post['comment_count'] ?> comments</span>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
