<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$rows = DB::table('requests')->select('id', 'tire_images')->orderBy('id', 'desc')->limit(20)->get();
foreach ($rows as $r) {
    echo "id={$r->id}\n";
    var_export($r->tire_images);
    echo "\n---\n";
}

echo "Done\n";
