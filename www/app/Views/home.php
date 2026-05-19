<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Feed<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Feed</h2>
    <a href="/post/create" class="btn btn-primary">New Post</a>
</div>

<?php if (empty($posts)): ?>
    <p class="text-secondary text-center mt-5">No posts yet. Be the first to post something!</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="card mb-4 p-4" id="post-<?= $post['id'] ?>">

            <div class="d-flex align-items-center mb-3">
                <?php if (!empty($post['profile_pic'])): ?>
                    <img
                        src="<?= esc($post['profile_pic']) ?>"
                        alt="avatar"
                        class="rounded-circle me-2"
                        style="width:40px; height:40px; object-fit:cover;"
                    >
                <?php else: ?>
                    <div
                        class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                        style="width:40px; height:40px; background:#e94560; font-weight:bold; font-size:1rem;"
                    >
                        <?= esc(strtoupper(substr($post['username'], 0, 1))) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <strong><?= esc($post['username']) ?></strong>
                    <br>
                    <small class="text-secondary"><?= esc($post['created_at']) ?></small>
                </div>

                <?php if (session()->get('user_id') == $post['user_id']): ?>
                    <div class="ms-auto d-flex gap-2">
                        <a href="/post/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-light">Edit</a>
                        <form method="POST" action="/posts/<?= $post['id'] ?>" onsubmit="return confirm('Delete this post?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <p class="mb-3"><?= esc($post['content']) ?></p>

            <?php if (!empty($post['image'])): ?>
                <img
                    src="<?= esc($post['image']) ?>"
                    alt="post image"
                    class="img-fluid rounded mb-3"
                    style="max-height:400px; object-fit:cover;"
                >
            <?php endif; ?>

            <div class="d-flex gap-3 text-secondary">
                <span id="like-count-<?= $post['id'] ?>">
                    &#9825; <?= (int)$post['like_count'] ?> likes
                </span>
                <span>
                    &#128172; <?= (int)$post['comment_count'] ?> comments
                </span>
            </div>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
