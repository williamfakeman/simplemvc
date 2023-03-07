<?php App\View::view('header', $data);
      App\View::view('auth/button', $data); ?>
<div class="row mb-4 mt-4">
    <div class="col-md-12">
    <?php if($record['id']): ?>
        <h1><a href="<?= ROOT ?>/">Tasks</a> &gt; Edit task #<?= $record['id'] ?></h1>
        <form action="<?= ROOT ?>/task/<?= $record['id'] ?>/edit" method="POST">
    <?php else: ?>
        <h1><a href="<?= ROOT ?>/">Tasks</a> &gt; Create new task</h1>
        <form action="<?= ROOT ?>/task/create" method="POST">
    <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($record['username']) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($record['email']) ?>">
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <textarea class="form-control" id="text" name="text"><?= htmlspecialchars($record['text']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="new"<?= ($record['status'] == 'new' ? ' selected' : '') ?>>
                        new
                    </option>
                    <option value="in progress"<?= ($record['status'] == 'in progress' ? ' selected' : '') ?>>
                        in progress
                    </option>
                    <option value="done"<?= ($record['status'] == 'done' ? ' selected' : '') ?>>
                        done
                    </option>
                    <option value="canceled"<?= ($record['status'] == 'canceled' ? ' selected' : '') ?>>
                        canceled
                    </option>
                </select>
            </div>
            <div class="errors"></div>
            <button type="submit" class="btn btn-primary submit">Submit</button>
        </form>
    </div>
</div>
<?php App\View::view('footer', $data); ?>
