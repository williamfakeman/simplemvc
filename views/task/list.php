<?php App\View::view('header', $data);
      App\View::view('auth/button', $data); ?>
<div class="row mb-4 mt-4">
    <div class="col-md-12">
        <h1>Tasks</h1>
        <div class="row">
            <div class="col-md-6">
                <a href="<?= ROOT ?>/task/create" class="btn btn-primary">Create new task</a>
            </div>
        </div>
        <table class="table table-striped mb-4 mt-4">
            <thead>
                <tr>
                    <th scope="col" class="w-5"><a href="<?= ROOT ?>/?page=<?= $page ?>&order=id&direction=<?= $newDirection ?>">#</a></th>
                    <th scope="col" class="w-10"><a href="<?= ROOT ?>/?page=<?= $page ?>&order=username&direction=<?= $newDirection ?>">Username</a></th>
                    <th scope="col" class="w-15"><a href="<?= ROOT ?>/?page=<?= $page ?>&order=email&direction=<?= $newDirection ?>">Email</a></th>
                    <th scope="col" class="w-40">Text</th>
                    <th scope="col" class="w-10"><a href="<?= ROOT ?>/?page=<?= $page ?>&order=status&direction=<?= $newDirection ?>">Status</a></th>
                <?php if($isLoggedIn): ?>
                    <th scope="col" class="w-20"></th>
                <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach($records as $record): ?>
                <tr>
                    <th scope="row"><?= $record['id'] ?></th>
                    <td><?= htmlspecialchars($record['username']) ?></td>
                    <td><?= htmlspecialchars($record['email']) ?></td>
                    <td><a href="<?= ROOT ?>/task/<?= $record['id'] ?>"><?= htmlspecialchars($record['text']) ?></a></td>
                    <td><?= $record['is_updated'] ? '[updated] ' : '' ?><?= htmlspecialchars($record['status']) ?></td>
                <?php if($isLoggedIn): ?>
                    <td class="text-right">
                        <a href="<?= ROOT ?>/task/<?= $record['id'] ?>/edit" class="btn btn-primary">Edit</a>
                        <a href="<?= ROOT ?>/task/<?= $record['id'] ?>/delete" class="btn btn-danger">Delete</a>
                    </td>
                <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($records)): ?>
                <tr>
                    <td colspan="6" class="text-center">No records</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    <?php foreach($pagingLinks as $link): ?>
        <a href="<?= $link['url'] ?>" class="btn btn-secondary"><?= $link['text'] ?></a>
    <?php endforeach; ?>
    </div>
</div>
<?php App\View::view('footer', $data); ?>
