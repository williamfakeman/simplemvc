<?php

define('ROOT', rtrim(dirname($_SERVER['PHP_SELF'], 2), '/'));

chdir('..');

require_once('vendor/autoload.php');

App\App::run();
