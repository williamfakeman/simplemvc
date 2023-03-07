<?php App\View::view('header', $data);
      App\View::view('auth/button', $data); ?>
<div class="row mb-4 mt-4">
    <div class="col-md-12">
        <h1><a href="<?= ROOT ?>/">Tasks</a> &gt; Task #<?= $record['id'] ?></h1>
    <?php if($isLoggedIn): ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="<?= ROOT ?>/task/<?= $record['id'] ?>/edit" class="btn btn-primary">Edit</a>
                <a href="<?= ROOT ?>/task/<?= $record['id'] ?>/delete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    <?php endif; ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Username</h6>
                <div class="username"><?= htmlspecialchars($record['username']) ?></div>
            </div>
            <div class="col-md-6">
                <h6>Email</h6>
                <div class="email"><?= htmlspecialchars($record['email']) ?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h6>Text</h6>
                <div class="text"><?= nl2br(htmlspecialchars($record['text'])) ?></div>
            </div>
            <div class="col-md-6">
                <h6>Status</h6>
                <div class="status"><?= $record['is_updated'] ? '[updated] ' : '' ?><?= htmlspecialchars($record['status']) ?></div>
            </div>
        </div>
    </div>
</div>
<?php App\View::view('footer', $data); ?>
