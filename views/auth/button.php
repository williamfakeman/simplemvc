<div class="row mb-4 mt-4">
    <div class="col-md-12 text-right">
    <?php if($isLoggedIn): ?>
        <a href="<?= ROOT ?>/auth/logout" class="btn btn-danger">Logout</a>
    <?php else: ?>
        <a href="<?= ROOT ?>/auth/login" class="btn btn-primary">Login</a>
    <?php endif; ?>
    </div>
</div>
