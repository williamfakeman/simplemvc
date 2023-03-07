<?php

namespace App;

use PDO, PDOStatement;

class Model
{
    /**
     * Database credentials
     * @var string DB_TYPE
     * @var string DB_HOST
     * @var string DB_NAME
     * @var string DB_CHARSET
     * @var string DB_USER
     * @var string DB_PASSWORD
     */
    private const DB_TYPE = 'mysql';
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'database';
    private const DB_CHARSET = 'utf8mb4';
    private const DB_USER = 'user';
    private const DB_PASSWORD = 'password';

    /**
     * Singleton database pattern
     * @var PDO
     */
    private static $instance = null;

    /**
     * Returns database instance
     * @return PDO
     */
    private static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn  = self::DB_TYPE. ':';
            $dsn .= 'host=' . self::DB_HOST . ';';
            $dsn .= 'dbname=' . self::DB_NAME . ';';
            $dsn .= 'charset=' . self::DB_CHARSET . ';';

            self::$instance = new PDO(
                $dsn,
                self::DB_USER,
                self::DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$instance;
    }

    /**
     * Creates new record
     * @param array $record - record data
     * @return string|false - id of created record or false
     */
    public static function create(array $record): string|false
    {
        $fields = array_keys($record);

        $sql = 'INSERT INTO `' . static::TABLE_NAME . '`
            (' . self::getInsertSelectFieldsString($fields) . ')
            VALUES (' . self::getInsertValuesString($fields) . ')';

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        self::bindValues($stmt, $record);
        $stmt->execute();

        return $db->lastInsertId();
    }

    /**
     * Returns record by id
     * @param string $id - record id
     * @param string $fields - fields to return
     * @return array|false - record array or false
     */
    public static function get(string $id, array $fields = null): array|false
    {
        if($fields === null) {
            $fields = array_keys(static::FIELDS);
        }

        $sql = 'SELECT ' . self::getInsertSelectFieldsString($fields) . '
            FROM `' . static::TABLE_NAME. '`
            WHERE `id` = :id';

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        self::bindValues($stmt, ['id' => $id]);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * List records page by page
     * @param string $orderBy - order by
     * @param string $direction - order direction
     * @param int $page - page number
     * @param array $fields - fields to select
     * @return array = array of records
     */
    public static function list(
        string $orderBy, 
        string $direction, 
        int $page, 
        array $fields = null
    ): array 
    {
        if($fields === null) {
            $fields = array_keys(static::FIELDS);
        }
        
        $start = ($page - 1) * static::LIMIT;

        $sql = 'SELECT ' . self::getInsertSelectFieldsString($fields) . ' 
            FROM `' . static::TABLE_NAME. '` 
            ORDER BY `' . $orderBy . '` ' . $direction . ' 
            LIMIT ' . $start . ', ' . static::LIMIT;

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Updates record
     * @param string $id - record id
     * @param array $record - record data
     * @return bool
     */
    public static function update(string $id, array $record): bool
    {
        $sql = 'UPDATE `' . static::TABLE_NAME. '` 
            SET ' . self::getUpdateFieldsString(array_keys($record)) . ' 
            WHERE `id` = :id';

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        self::bindValues($stmt, $record);
        self::bindValues($stmt, ['id' => $id]);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Deletes record
     * @param string $id - record id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        $sql = 'DELETE FROM `' . static::TABLE_NAME. '` WHERE `id` = :id';

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        self::bindValues($stmt, ['id' => $id]);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Returns count of records
     * @return int
     */
    public static function count(): int 
    {
        $sql = 'SELECT COUNT(*) FROM `' . static::TABLE_NAME. '`';

        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Returns count of pages
     * @return int
     */
    public static function countPages(): int
    {
        return ceil(static::count() / static::LIMIT);
    }

    /**
     * Returns SQL string with fields for insert and select queries
     * @param array $fields
     * @return string - SQL string: `field1`,`field2`,`...`
     */
    protected static function getInsertSelectFieldsString(array $fields): string
    {
        $fields = array_map(function($field) {
            return '`' . $field . '`';
        }, $fields);

        return implode(',', $fields);
    }

    /**
     * Returns SQL string with values to bind for insert queries
     * @param array $fields
     * @return string - SQL string: :field1,:field2,:...
     */
    protected static function getInsertValuesString(array $fields): string
    {
        $fields = array_map(function($field) {
            return ':' . $field;
        }, $fields);

        return implode(',', $fields);
    }

    /**
     * Returns SQL string with update fields
     * @param array $fields
     * @return string - SQL string: `field1` = :field1,`field2` = :field2,`...` = :...
     */
    protected static function getUpdateFieldsString(array $fields): string
    {
        $fields = array_map(function($field) {
            return '`' . $field . '` = :' . $field;
        }, $fields);

        return implode(',', $fields);
    }

    /**
     * Binds parameters to PDO statement
     * @param PDOStatement $stmt - PDO statement
     * @param array $record - record data
     * @return void
     */
    protected static function bindValues(PDOStatement $stmt, array $record): void
    {
        foreach($record as $field => $value) {
            $stmt->bindValue(':' . $field, $value, static::FIELDS[$field] ?? PDO::PARAM_STR);
        }
    }
}
