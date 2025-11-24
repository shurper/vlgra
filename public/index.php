<?php

declare(strict_types=1);

use App\Presentation\Bootstrap\DemoBootstrap;

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new DemoBootstrap(dirname(__DIR__));
$controller = $bootstrap->controller();

echo $controller->handle($_SERVER['REQUEST_METHOD'] ?? 'GET', $_GET, $_POST);
