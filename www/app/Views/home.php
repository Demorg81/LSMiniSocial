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
        <?php
        $isOwner  = (int)session()->get('user_id') === (int)$post['user_id'];
        $hasLiked = !empty($post['user_has_liked']);
        ?>

        <div class="card mb-4 p-4" id="post-<?= $post['id'] ?>">

            <div class="d-flex align-items-center mb-3">
                <?php if (!empty($post['profile_pic'])): ?>
                    <img
                            src="/<?= esc($post['profile_pic']) ?>"
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

                <?php if ($isOwner): ?>
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
                        src="/<?= esc($post['image']) ?>"
                        alt="post image"
                        class="img-fluid rounded mb-3"
                        style="max-height:400px; width:100%; object-fit:contain;"
                >
            <?php endif; ?>

            <div class="d-flex align-items-center gap-3 mt-2">

                <button
                        class="btn btn-sm like-btn <?= $hasLiked ? 'liked btn-outline-danger' : 'btn-outline-light' ?>"
                        data-post-id="<?= $post['id'] ?>"
                        data-liked="<?= $hasLiked ? '1' : '0' ?>"
                >
                    <?= $hasLiked ? '♥ Unlike' : '♡ Like' ?>
                </button>

                <span class="text-secondary small">
                    <span id="like-count-<?= $post['id'] ?>"><?= (int)$post['like_count'] ?></span> likes
                </span>

                <button
                        class="btn btn-sm btn-outline-light comments-toggle-btn"
                        data-post-id="<?= $post['id'] ?>"
                >
                    &#128172; <span id="comment-count-<?= $post['id'] ?>"><?= (int)$post['comment_count'] ?></span> comments
                </button>

            </div>

            <div id="comments-section-<?= $post['id'] ?>" class="d-none mt-3">

                <div id="comments-list-<?= $post['id'] ?>" class="mb-3"></div>

                <form class="comment-form d-flex gap-2" data-post-id="<?= $post['id'] ?>">
                    <input
                            type="text"
                            class="form-control form-control-sm comment-input"
                            placeholder="Write a comment..."
                            autocomplete="off"
                    >
                    <button type="submit" class="btn btn-primary btn-sm">Post</button>
                </form>

            </div>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const currentUserId = <?= (int)session()->get('user_id') ?>;
    </script>
    <script src="/js/interactions.js"></script>
<?= $this->endSection() ?>