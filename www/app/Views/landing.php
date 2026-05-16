<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>Welcome<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="text-center py-5">
        <h1 class="display-3 fw-bold mb-3">LSMiniSocial</h1>
        <p class="lead text-secondary mb-5">The La Salle community social network. Share your thoughts, connect with others.</p>

        <div class="row justify-content-center g-4 mb-5">
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2">Share posts</h5>
                    <p class="text-secondary small mb-0">Write and publish content for the whole community to see.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2">Like & comment</h5>
                    <p class="text-secondary small mb-0">Interact with other users' posts through likes and comments.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2">AI improvement</h5>
                    <p class="text-secondary small mb-0">Polish your posts with AI assistance before publishing.</p>
                </div>
            </div>
        </div>

        <a href="/sign-in" class="btn btn-primary me-2 px-4">Sign in</a>
        <a href="/sign-up" class="btn btn-outline-light px-4">Register</a>
    </div>
<?= $this->endSection() ?>