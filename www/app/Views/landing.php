<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?><?= lang('App.landing_title') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="text-center py-5">
        <h1 class="display-3 fw-bold mb-3"><?= lang('App.landing_title') ?></h1>
        <p class="lead text-secondary mb-5"><?= lang('App.landing_subtitle') ?></p>

        <div class="row justify-content-center g-4 mb-5">
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2"><?= lang('App.landing_feature_posts_title') ?></h5>
                    <p class="text-secondary small mb-0"><?= lang('App.landing_feature_posts_body') ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2"><?= lang('App.landing_feature_interact_title') ?></h5>
                    <p class="text-secondary small mb-0"><?= lang('App.landing_feature_interact_body') ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-2"><?= lang('App.landing_feature_ai_title') ?></h5>
                    <p class="text-secondary small mb-0"><?= lang('App.landing_feature_ai_body') ?></p>
                </div>
            </div>
        </div>

        <a href="/sign-in" class="btn btn-primary me-2 px-4"><?= lang('App.landing_btn_signin') ?></a>
        <a href="/sign-up" class="btn btn-outline-light px-4"><?= lang('App.landing_btn_register') ?></a>
    </div>
<?= $this->endSection() ?>