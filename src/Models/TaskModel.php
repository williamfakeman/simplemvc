<?php

namespace App\Models;

use App\Model, PDO;

class TaskModel extends Model
{
    /**
     * @var int LIMIT - database query limit
     * @var string TABLE_NAME - table name
     * @var array FIELDS - table fields with bind types
     */
    public const LIMIT = 3;
    public const TABLE_NAME = 'tasks';
    public const FIELDS = [
        'id' => PDO::PARAM_INT, 
        'username' => PDO::PARAM_STR,
        'email' => PDO::PARAM_STR,
        'text' => PDO::PARAM_STR,
        'status' => PDO::PARAM_STR,
        'is_updated' => PDO::PARAM_BOOL,
    ];
}
