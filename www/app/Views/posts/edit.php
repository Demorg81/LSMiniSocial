<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Edit Post<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h2 class="mb-4">Edit Post</h2>

            <form method="POST" action="/post/edit/<?= esc($post['id']) ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea
                        name="content"
                        id="postContent"
                        rows="5"
                        class="form-control <?= isset($errors['content']) ? 'is-invalid' : '' ?>"
                    ><?= esc($post['content']) ?></textarea>
                    <?php if (isset($errors['content'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['content']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-outline-light btn-sm" id="improveBtn">
                        Improve with AI
                    </button>
                    <span id="aiSpinner" class="ms-2 text-secondary small d-none">Improving...</span>
                </div>

                <div id="aiSuggestion" class="mb-3 d-none">
                    <label class="form-label text-secondary">AI suggestion</label>
                    <div class="card p-3 mb-2">
                        <p id="aiSuggestionText" class="mb-0"></p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm me-2" id="acceptBtn">Accept</button>
                    <button type="button" class="btn btn-outline-light btn-sm" id="rejectBtn">Reject</button>
                </div>

                <div class="mb-4">
                    <label class="form-label">Replace image (optional)</label>
                    <?php if ($post['image']): ?>
                        <div class="mb-2">
                            <img src="/<?= esc($post['image']) ?>" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Save changes</button>
                    <a href="/home" class="btn btn-outline-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/js/ai.js"></script>
<?= $this->endSection() ?>
