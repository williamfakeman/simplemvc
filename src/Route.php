<?php

namespace App;

class Route
{
    /**
     * @var array $routes - array of all routes beeing defined
     */
    private static $routes = [];

    /**
     * Retruns all routes
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Add a GET route.
     * @param string $route - route path
     * @param string $class - controller class name
     * @param string $function - controller function name
     * @return void
     */
    public static function get(string $route, string $class, string $function): void
    {
        self::add('GET', $route, $class, $function);
    }

    /**
     * Add a POST route.
     * @param string $route - route path
     * @param string $class - controller class name
     * @param string $function - controller function name
     * @return void
     */
    public static function post(string $route, string $class, string $function): void
    {
        self::add('POST', $route, $class, $function);
    }

    /**
     * Add a route to the routes array.
     * @param string $method - request method
     * @param string $route - route path
     * @param string $class - controller class name
     * @param string $function - controller class function name
     * @return void
     */
    public static function add(string $method, string $route, string $class, string $function): void
    {
        $route = ltrim($route, '/');

        // find all {variables} in the route
        $routeVariables = [];
        preg_match_all('({[a-zA-Z0-9]+})', $route, $routeVariables);
        $routeVariables = $routeVariables[0];

        // make regex for the route to parse the url: route/{variable} => /^route\/([a-zA-Z0-9-]+)$/
        $regex = '/^' . preg_replace('/{([a-zA-Z0-9]+)}/', '([a-zA-Z0-9-]+)', str_replace('/', '\/', $route)) . '$/';

        self::$routes[] = (object) [
            'method' => $method,
            'route' => $route,
            'class' => $class,
            'function' => $function,
            'routeVariables' => $routeVariables,
            'regex' => $regex,
        ];
    }

    /**
     * Finds the route that matches the url and start it or show 404 error page.
     * @param string $url - url to parse, uses $_REQUEST['path'] if not defined (for testing purposes)
     * @return bool - true if route was found, false otherwise
     */
    public static function run(string $url = null): bool
    {
        if($url === null) {
            $url = ltrim($_REQUEST['path'] ?? '', 'public/');
            $url = rtrim($url, '/');
        }

        $isControllerFound = false;

        $count = sizeof(self::$routes);
        for($i = 0; $i < $count; $i++) {
            $route = self::$routes[$i];

            if(self::check($url, $route)) {
                self::startController($url, $route);
                $isControllerFound = true;

                return true;
            }
        }
        
        if(!$isControllerFound) {
            Controller::show404($url);
            
            return false;
        }
    }

    /**
     * Checks if the url matches the route.
     * @param string $url - url to check
     * @param object $route - route to check against
     * @param string $method - request method (for testing purposes)
     * @return bool
     */
    public static function check(string $url, object $route, string $method = null): bool
    {
        $method = $method ?? $_SERVER['REQUEST_METHOD'];

        return (
            $route->method == $method &&
            preg_match($route->regex, $url)
        );
    }

    /**
     * Parses the url and stores the variables in the key-value array
     * @param string $url - url to parse
     * @param object $route - route to parse
     * @return array - parsed url variables
     */
    public static function parse(string $url, object $route): array
    {
        // get values from url
        $values = [];
        preg_match_all($route->regex, $url, $values);
        array_shift($values);

        // store values in the key-value array
        $urlVariables = [];
        for($i = 0; $i < sizeof($values); $i++) {
            $var = preg_replace('/{([a-zA-Z0-9]+)}/', '${1}', $route->routeVariables[$i]);
            $urlVariables[$var] = $values[$i][0];
        }

        return $urlVariables;
    }

    /**
     * Starts the controller.
     * @param string $url - url to pass to the controller
     * @param object $route - route object
     * @return mixed - controller class object
     */
    public static function startController(string $url, object $route): mixed
    {
        $controller = new $route->class($url, self::parse($url, $route));
        $controller->{$route->function}();

        return $controller;
    }
}
