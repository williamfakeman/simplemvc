<?php

namespace App\Controllers;

use App\ {
    View,
    Controller,
};

class AuthController extends Controller
{
    /**
     * User credentials
     * @var string LOGIN
     * @var string PASSWORD
     */
    private const LOGIN = 'admin';
    private const PASSWORD = '123';

    /**
     * Show login page
     * @return void
     */
    public function login(): void
    {
        $data = [
            'title' => 'Login',
            'error' => isset($_GET['error']),
        ];

        View::view('auth/login', $data);
    }

    /**
     * Login action
     * @return bool
     */
    public function loginPost(): bool
    {
        if($_POST['login'] == self::LOGIN && $_POST['password'] == self::PASSWORD) {
            $_SESSION['isLoggedIn'] = true;
            self::redirect('/');
            return true;
        }

        self::redirect('/auth/login?error=1');
        return false;
    }

    /**
     * Logout action
     * @return void
     */
    public function logout(): void
    {
        $_SESSION['isLoggedIn'] = false;

        if(session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        self::redirect('/');
    }

    /**
     * Returns authentication status
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return $_SESSION['isLoggedIn'] ?? false;
    }

    /**
     * Require user to be logged in, redirects to login page if not and ends script execution 
     * @return bool
     */
    public static function requireLogin(): bool
    {
        if(!self::isLoggedIn()) {
            self::redirect('/auth/login');
            return false;
        }

        return true;
    }
}
