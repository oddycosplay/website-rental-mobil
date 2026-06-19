<?php
$file = __DIR__.'/../storage/logs/laravel.log';
if (!file_exists($file)) {
    echo "Log file not found.\n";
    exit;
}

$content = file_get_contents($file);
$lines = explode("\n", $content);

$last_error_index = -1;
foreach ($lines as $i => $line) {
    if (strpos($line, 'local.ERROR:') !== false) {
        $last_error_index = $i;
    }
}

if ($last_error_index !== -1) {
    echo "Last error found at line $last_error_index:\n";
    $end = min(count($lines), $last_error_index + 25);
    for ($j = $last_error_index; $j < $end; $j++) {
        echo "$j: " . $lines[$j] . "\n";
    }
} else {
    echo "No local.ERROR found.\n";
}
