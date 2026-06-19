<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
echo "Base Path: " . $app->basePath() . "\n";
echo "Environment File Path: " . $app->environmentFilePath() . "\n";
