<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    public function setUp(): void {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
        });

        if(!defined('ROOT')) {
            define('ROOT', '');
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
        $controller = new AuthController('/', []);
        $this->assertInstanceOf(AuthController::class, $controller);
        
        return $controller;
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanShowLoginPage($controller)
    {
        $this->assertNull($controller->login());
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanLogin($controller)
    {
        $_POST['login'] = 'admin1';
        $_POST['password'] = 'foo';
        $this->assertFalse($controller->loginPost());

        $_POST['login'] = 'admin';
        $_POST['password'] = '123';
        $this->assertTrue($controller->loginPost());

        return $controller;
    }

    /**
     * @depends testCanCreateControllerObject
     */
    public function testCanLogout($controller)
    {
        $this->assertNull($controller->logout());
    }

    public function testIsLoggedIn()
    {
        $this->assertFalse(AuthController::isLoggedIn());

        $_SESSION['isLoggedIn'] = true;
        $this->assertTrue(AuthController::isLoggedIn());
    }

    public function testRequireLogin()
    {
        $_SESSION['isLoggedIn'] = false;
        $this->assertFalse(AuthController::requireLogin());

        $_SESSION['isLoggedIn'] = true;
        $this->assertTrue(AuthController::requireLogin());
    }
}
