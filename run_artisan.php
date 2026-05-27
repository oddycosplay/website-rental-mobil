<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$args = $_SERVER['argv'];
array_shift($args);
array_unshift($args, 'artisan');

$input = new Symfony\Component\Console\Input\ArgvInput($args);
$output = new Symfony\Component\Console\Output\ConsoleOutput();

$status = $kernel->handle($input, $output);
$kernel->terminate($input, $status);
exit($status);
