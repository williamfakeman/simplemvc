<?php

namespace App;

class View 
{
    /**
     * @var array
     */
    private const DEFAULT_HEADERS = [
        'Content-Type: text/html; charset=utf-8',
    ];

    /**
     * Parse the template and output or return the result
     * @param string $template - name of the template
     * @param array $data - data to pass to the template
     * @param bool $return - return the data instead of outputting it
     * @param array $headers - headers to sent to the browser
     * @return string|null - if $return is true, returns the data, otherwise returns null
     */
    public static function view(
        string $template, 
        array $data = [], 
        bool $return = false, 
        array $headers = self::DEFAULT_HEADERS
    ): string|null
    {
        if($return) {
            ob_start();
        }

        if(!$return && !headers_sent()) {
            foreach($headers as $header) {
                header($header);
            }
        }

        foreach($data as $key => $value) {
            $$key = $value;
        }

        require('views/' . $template . '.php');

        if($return) {
            return ob_get_clean();
        }

        return null;
    }
}
