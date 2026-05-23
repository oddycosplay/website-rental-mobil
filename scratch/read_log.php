<?php
$file = __DIR__.'/../storage/logs/laravel.log';
if (!file_exists($file)) {
    echo "Log file not found.\n";
    exit;
}

$content = file_get_contents($file);
$lines = explode("\n", $content);

// Find the last occurrence of MissingLayoutException
$match_index = -1;
foreach ($lines as $i => $line) {
    if (strpos($line, 'MissingLayoutException') !== false) {
        $match_index = $i;
    }
}

if ($match_index !== -1) {
    echo "Found MissingLayoutException around line $match_index:\n";
    $start = max(0, $match_index - 10);
    $end = min(count($lines) - 1, $match_index + 60);
    for ($j = $start; $j <= $end; $j++) {
        echo "$j: " . $lines[$j] . "\n";
    }
} else {
    echo "No occurrence of MissingLayoutException found in laravel.log.\n";
    // Show last 100 lines instead
    $start = max(0, count($lines) - 100);
    for ($j = $start; $j < count($lines); $j++) {
        echo "$j: " . $lines[$j] . "\n";
    }
}
