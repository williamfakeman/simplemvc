<?php

use PHPUnit\Framework\TestCase;
use App\Route;

class RouteTest extends TestCase
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

    public function testCanAddRoutes()
    {
        Route::get('/task/task-{id}/{name}', App\Controllers\TaskController::class, 'show');
        Route::post('/task/{id}', App\Controllers\TaskController::class, 'show');
        Route::get('/{variable}', App\Controllers\TaskController::class, 'show');
        
        $routes = Route::getRoutes();

        $this->assertCount(3, $routes);
    }

    /**
     * @depends testCanAddRoutes
     */
    public function testCanMakeRegex()
    {
        $routes = Route::getRoutes();

        $this->assertEquals('/^task\/task-([a-zA-Z0-9-]+)\/([a-zA-Z0-9-]+)$/', $routes[0]->regex);
        $this->assertEquals('/^task\/([a-zA-Z0-9-]+)$/', $routes[1]->regex);
        $this->assertEquals('/^([a-zA-Z0-9-]+)$/', $routes[2]->regex);
    }

    /**
     * @depends testCanMakeRegex
     */
    public function testCanCheckRoute()
    {
        $routes = Route::getRoutes();

        $this->assertTrue(Route::check('task/task-123/foobar', $routes[0], 'GET'));
        $this->assertFalse(Route::check('task/task-123/foobar', $routes[1], 'POST'));

        $this->assertTrue(Route::check('task/123', $routes[1], 'POST'));
        $this->assertFalse(Route::check('task/123', $routes[0], 'GET'));

        $this->assertTrue(Route::check('123', $routes[2], 'GET'));
    }

    /**
     * @depends testCanCheckRoute
     */
    public function testCanParseUrlVariables()
    {
        $routes = Route::getRoutes();

        $this->assertEquals(['id' => '1', 'name' => 'taskName'], Route::parse('task/task-1/taskName', $routes[0]));
        $this->assertEquals(['id' => '1'], Route::parse('task/1', $routes[1]));
        $this->assertEquals(['variable' => '123'], Route::parse('123', $routes[2]));
    }

    /**
     * @depends testCanParseUrlVariables
     */
    public function testCanStartController()
    {
        $routes = Route::getRoutes();

        $this->assertInstanceOf(App\Controllers\TaskController::class, Route::startController('task/task-33/taskName', $routes[0]));
    }
}
