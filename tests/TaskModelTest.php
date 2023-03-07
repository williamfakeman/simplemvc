<?php

use PHPUnit\Framework\TestCase;
use App\Models\TaskModel;

class TaskModelTest extends TestCase
{
    public function setUp(): void {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
        });
    }
    
    public function tearDown(): void {
        restore_error_handler();
    }

    public function testCanCreateRecord()
    {
        $id = TaskModel::create([
            'username' => 'foo', 
            'email' => 'foo@bar.baz', 
            'text' => 'foo', 
            'status' => 'new'
        ]);

        $this->assertIsString($id);

        return $id;
    }

    /**
     * @depends testCanCreateRecord
     */
    public function testCanGetRecord($id)
    {
        $this->assertIsArray(TaskModel::get($id));
    }

    /**
     * @depends testCanCreateRecord
     */
    public function testCanGetListOfRecords()
    {
        $list = TaskModel::list('id', 'DESC', 1);

        $this->assertIsArray($list);
        $this->assertTrue(count($list) > 0);
    }

    /**
     * @depends testCanCreateRecord
     */
    public function testCanUpdateRecord($id)
    {
        $this->assertTrue(TaskModel::update($id, [
            'username' => 'foo',
            'email' => 'foo@bar.baz',
            'text' => 'foo',
            'status' => 'new',
            'is_updated' => true,
        ]));

        $this->assertFalse(TaskModel::update(0, [
            'username' => 'foo',
            'email' => 'foo@bar.baz',
            'text' => 'foo',
            'status' => 'new',
            'is_updated' => true,
        ]));
    }

    /**
     * @depends testCanCreateRecord
     */
    public function testCanDeleteRecord($id)
    {
        $this->assertFalse(TaskModel::delete(0));
        $this->assertTrue(TaskModel::delete($id));
    }

    public function testCanGetCountOfRecords()
    {
        $this->assertIsInt(TaskModel::count());
    }

    public function testCanGetCountOfPages()
    {
        $this->assertIsInt(TaskModel::countPages());
    }
}
