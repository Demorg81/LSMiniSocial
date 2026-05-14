<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $this->renderSection('title') ?> – LSMiniSocial</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">LSMiniSocial</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->get('user_id')): ?>
                        <li class="nav-item"><a class="nav-link" href="/home">Feed</a></li>
                        <li class="nav-item"><a class="nav-link" href="/post/create">New Post</a></li>
                        <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="/sign-out">Sign out</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/sign-in">Sign in</a></li>
                        <li class="nav-item"><a class="nav-link" href="/sign-up">Sign up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc($error) ?></div>
        <?php endif; ?>

        <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc($success) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <footer class="text-center py-3 mt-auto">
        <small>LSMiniSocial © 2026</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>