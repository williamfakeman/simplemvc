<?php App\View::view('header', $data); ?>
<div class="container">
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <h1>Authentication</h1>
        <?php if($error): ?>
            <div class="errors mt-4 mb-4">
                Invalid username or password.
            </div>
        <?php endif; ?>
            <form action="<?= ROOT ?>/auth/login" method="POST">
                <div class="form-group">
                    <label for="login">Username</label>
                    <input type="text" class="form-control" id="login" name="login" value="" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<?php App\View::view('footer', $data); ?>
