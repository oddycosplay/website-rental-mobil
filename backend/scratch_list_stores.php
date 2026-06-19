<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Store;

$stores = Store::all();
echo "ID | Name | Slug\n";
foreach($stores as $s) {
    echo "{$s->id} | {$s->name} | {$s->slug}\n";
}
