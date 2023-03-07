<?php

namespace App\Controllers;

use App\ {
    Models\TaskModel,
    View,
    Controller,
};

class TaskController extends Controller 
{
    /**
     * Sorting fields
     * @var string $orderBy - order by
     * @var string $direction - order direction
     */
    private string $orderBy = 'id';
    private string $direction = 'DESC';
    
    /**
     * Paging fields
     * @var int $countPages - count of pages
     * @var int $page - page number
     */
    private int $countPages = 0;
    private int $page = 1;

    /**
     * Shows list of tasks
     * @return void
     */
    public function list(): void
    {
        $this->countPages = TaskModel::countPages();

        $this->page = intval($_GET['page'] ?? $this->page);
        if($this->page < 1) {
            $this->page = 1;
        } elseif($this->countPages > 0 && $this->page > $this->countPages) {
            $this->page = $this->countPages;
        }

        $this->orderBy = $_GET['order'] ?? $this->orderBy;
        if(!in_array($this->orderBy, ['id', 'username', 'email', 'status'])) {
            $this->orderBy = 'id';
        }

        $this->direction = $_GET['direction'] ?? $this->direction;
        if(!in_array($this->direction, ['ASC', 'DESC'])) {
            $this->direction = 'DESC';
        }

        $records = TaskModel::list($this->orderBy, $this->direction, $this->page);

        $data = [
            'title' => 'Tasks',
            'records' => $records,
            'order' => $this->orderBy,
            'direction' => $this->direction,
            'newDirection' => $this->direction == 'ASC' ? 'DESC' : 'ASC',
            'page' => $this->page,
            'pagingLinks' => $this->getPagingLinks(),
            'isLoggedIn' => AuthController::isLoggedIn(),
        ];

        View::view('task/list', $data);
    }

    /**
     * Shows task page
     * @return bool
     */
    public function show(): bool
    {
        $record = TaskModel::get($this->urlVariables['id'], ['id', 'username', 'email', 'text', 'status', 'is_updated']);

        if($record === false) {
            $this->error404();
            return false;
        }

        $data = [
            'title' => 'Task #' . $record['id'],
            'record' => $record,
            'isLoggedIn' => AuthController::isLoggedIn(),
        ];

        View::view('task/show', $data);

        return true;
    }

    /**
     * Shows form for creating new task
     * @return void
     */
    public function create(): void
    {
        $data = [
            'title' => 'Create new task',
            'record' => [
                'id' => 0,
                'username' => '',
                'email' => '',
                'text' => '',
                'status' => 'new',
            ],
            'isLoggedIn' => AuthController::isLoggedIn(),
        ];

        View::view('task/edit', $data);
    }

    /**
     * Creates new task
     * @return string|false
     */
    public function createPost(): string|false
    {
        $record = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'text' => trim($_POST['text']),
            'status' => trim($_POST['status']),
        ];

        if(self::checkData($record) && $id = TaskModel::create($record)) {
            self::redirect('/task/' . $id);
            return $id;
        }

        self::redirect('/?error=1');
        return false;
    }

    /**
     * Shows form for editing existing task
     * @return bool
     */
    public function edit(): bool
    {
        AuthController::requireLogin();

        $record = TaskModel::get($this->urlVariables['id'], ['id', 'username', 'email', 'text', 'status']);

        if($record === false) {
            $this->error404();
            return false;
        }
        
        $data = [
            'title' => 'Edit task',
            'record' => $record,
            'isLoggedIn' => AuthController::isLoggedIn(),
        ];

        View::view('task/edit', $data);

        return true;
    }

    /**
     * Updates existing task
     * @return bool
     */
    public function editPost(): bool
    {
        AuthController::requireLogin();

        $id = $this->urlVariables['id'];

        $record = [
            'id' => $id,
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'text' => trim($_POST['text']),
            'status' => trim($_POST['status']),
        ];

        $oldTask = TaskModel::get($id, ['text', 'is_updated']);
        $record['is_updated'] =  $oldTask['is_updated'] || $oldTask['text'] != $record['text'];

        if(self::checkData($record) && TaskModel::update($id, $record)) {
            self::redirect('/task/' . $id);
            return true;
        }

        self::redirect('/?error=1');
        return false;
    }

    /**
     * Deletes existing task
     * @return bool
     */
    public function delete(): bool
    {
        AuthController::requireLogin();

        if(TaskModel::delete($this->urlVariables['id'])) {
            self::redirect('/');
            return true;
        }

        self::redirect('/?error=1');
        return false;
    }

    /**
     * Returns paging links
     * @return array
     */
    private function getPagingLinks(): array
    {
        $links = [];

        if($this->page > 1) {
            $links[] = [
                'url' => ROOT . '/?page='.($this->page - 1).'&order='.$this->orderBy.'&direction='.$this->direction,
                'text' => 'Previous page',
            ];
        }

        if($this->page < $this->countPages) {
            $links[] = [
                'url' => ROOT . '/?page='.($this->page + 1).'&order='.$this->orderBy.'&direction='.$this->direction,
                'text' => 'Next page',
            ];
        }

        return $links;
    }

    /**
     * Checks record fields validity
     * @param array $record
     * @return bool
     */
    private static function checkData(array $record): bool
    {
        return (
            strlen($record['username']) >= 3 &&
            filter_var($record['email'], FILTER_VALIDATE_EMAIL) &&
            strlen($record['text']) >= 3 &&
            in_array($record['status'], ['new', 'in progress', 'done', 'canceled'])
        );
    }
}
