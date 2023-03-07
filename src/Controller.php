<?php

namespace App;

class Controller
{
    /**
     * @var string $url - request url
     * @var array $urlVariables - array of all url variables beeing defined
     */
    protected $url = null;
    protected $urlVariables = [];
    

    /**
     * @param string $url - request url
     * @param array $urlVariables - parsed url variables to set
     */
    public function __construct(string $url, array $urlVariables)
    {
        $this->url = $url;
        $this->urlVariables = $urlVariables;

        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Shows 404 error page (non-static method)
     * @return void
     */
    protected function error404(): void 
    {
        self::show404($this->url);
    }

    /**
     * Shows template for the 404 error page and stops the script execution
     * @param string $url - invalid url
     * @return void
     */
    public static function show404(string $url): void 
    {
        header("HTTP/1.1 404 Not Found");
        View::view('404', ['url' => $url]);

        if(!defined('PHPUNIT_TESTING')) {
            die();
        }
    }

    /**
     * Redirects to another page and stops the script execution
     * @param string $url - url to redirect to
     * @param int $status - status code to redirect to
     */
    public static function redirect(string $url, int $status = 302): void 
    {
        header('Location: ' . ROOT . $url, true, $status);

        if(!defined('PHPUNIT_TESTING')) {
            die();
        }
    }
}
