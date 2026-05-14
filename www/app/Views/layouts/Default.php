<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= $this->renderSection('title') ?> – MovieWatchlist</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body { background: #0f0f1a; color: #e0e0e0; }
            .navbar { background: #1a1a2e; }
            .navbar-brand, .nav-link { color: #e0e0e0 !important; }
            .nav-link:hover { color: #e94560 !important; }
            .card { background: #16213e; border: none; color: #e0e0e0; }
            .btn-primary { background: #e94560; border-color: #e94560; }
            .btn-primary:hover { background: #c73652; border-color: #c73652; }
            .form-control { background: #0f3460; border-color: #333; color: #e0e0e0; }
            .form-control:focus { background: #0f3460; color: #e0e0e0; border-color: #e94560; box-shadow: none; }
            footer { background: #1a1a2e; color: #888; }
        </style>
    </head>

    <body class="d-flex flex-column min-vh-100">

        <nav class="navbar navbar-expand-lg mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="/">MovieWatchlist</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav ms-auto">
                        <?php if (session()->get('user_id')): ?>
                            <li class="nav-item"><a class="nav-link" href="/movies">Movies</a></li>
                            <li class="nav-item"><a class="nav-link" href="/favorites">Favorites</a></li>
                            <li class="nav-item"><a class="nav-link" href="/shared">Shared</a></li>
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
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success = session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>

        <footer class="text-center py-3 mt-auto">
            <small>MovieWatchlist © 2026</small>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
