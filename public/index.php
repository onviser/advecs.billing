<?php declare(strict_types=1);
require_once '../vendor/autoload.php';

use Advecs\App\Application\Application;
use Advecs\App\HTTP\Request;

(new Application())
    ->handleRequest(new Request(array_merge($_GET, $_POST, $_COOKIE, $_SERVER)));
exit;