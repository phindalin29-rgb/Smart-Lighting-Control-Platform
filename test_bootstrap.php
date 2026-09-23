<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    );

$methods = get_class_methods($app);
foreach ($methods as $method) {
    echo $method.PHP_EOL;
}
