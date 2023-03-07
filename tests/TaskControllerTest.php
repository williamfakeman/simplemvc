<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\TaskController;

class TaskControllerTest extends TestCase
{
    public function setUp(): void {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
        });

        if(!defined('ROOT')) {
            define('ROOT', 'PHPUNIT_TESTING');
        }

        if(!defined('PHPUNIT_TESTING')) {
            define('PHPUNIT_TESTING', true);
        }
    }
    
    public function tearDown(): void {
        restore_error_handler();
    }

    public function testCanCreateControllerObject()
    {
        $controller = new TaskController('/', ['id' => 'foo']);
        $this->assertInstanceOf(TaskController::class, $controller);
        
        return $controller;
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanShowCreatePage($controller)
    {
        $this->assertNull($controller->create());
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanShowListPage($controller) 
    {
        $this->assertNull($controller->list());
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanCreatePost($controller)
    {
        $_POST = [
            'username' => 'foo',
            'email' => 'foo@bar', // not valid email
            'text' => 'foo',
            'status' => 'new',
        ];
        $this->assertFalse($controller->createPost());

        $_POST['email'] = 'foo@bar.baz'; // valid email

        $id = $controller->createPost();
        $this->assertIsString($id);

        return $id;
    }

    /**
     * @depends testCanCreatePost
     */
    public function testCanShowRecordPage($id) 
    {
        $controller = new TaskController('/', ['id' => 0]); // 404
        $this->assertFalse($controller->show());

        $controller = new TaskController('/', ['id' => $id]);
        $this->assertTrue($controller->show());

        return $controller;
    }

    /**
     * @depends testCanShowRecordPage
     */
    public function testCanShowEditPage($controller)
    {
        $this->assertTrue($controller->edit());

        $controller = new TaskController('/', ['id' => 0]); // 404
        $this->assertFalse($controller->edit());
    }

    /**
     * @depends testCanShowRecordPage
     */
    public function testCanUpdateRecord($controller)
    {
        $_POST = [
            'username' => 'foo',
            'email' => 'foo@bar', // not valid email
            'text' => 'foo',
            'status' => 'new',
        ];
        $this->assertFalse($controller->editPost());

        $_POST['email'] = 'foo2@bar.baz'; // valid email
        $this->assertTrue($controller->editPost());

        return $controller;
    }

    /**
     * @depends testCanUpdateRecord
     */
    public function testCanDeleteRecord($controller)
    {
        $this->assertTrue($controller->delete());
    }
}
