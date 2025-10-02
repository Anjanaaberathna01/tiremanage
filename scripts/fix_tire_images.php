<?php
// Usage: php fix_tire_images.php [--apply]
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$apply = in_array('--apply', $argv, true);
echo ($apply ? "Running in APPLY mode\n" : "Running in DRY-RUN mode (no DB writes)\n");

$rows = DB::table('requests')->select('id', 'tire_images')->orderBy('id', 'desc')->get();
$count = 0;
foreach ($rows as $r) {
    $val = $r->tire_images;
    if ($val === null || $val === '') {
        continue;
    }

    // Try first decode
    $decoded = json_decode($val, true);

    if (is_array($decoded)) {
        // already proper
        continue;
    }

    // If decode returns a string, it may be a JSON string containing a JSON array
    if (is_string($decoded)) {
        $decoded2 = json_decode($decoded, true);
        if (is_array($decoded2)) {
            $count++;
            echo "Will fix id={$r->id} -> " . json_encode($decoded2) . PHP_EOL;
            if ($apply) {
                DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded2)]);
            }
            continue;
        }
    }

    // Finally try unescaping common escaped slashes and try again
    $unescaped = str_replace('\\/', '/', $val);
    $decoded3 = json_decode($unescaped, true);
    if (is_array($decoded3)) {
        $count++;
        echo "Will fix (unescaped) id={$r->id} -> " . json_encode($decoded3) . PHP_EOL;
        if ($apply) {
            DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded3)]);
        }
    }
}

echo "Done. Rows changed (would change): {$count}\n";
