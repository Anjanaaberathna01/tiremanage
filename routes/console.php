<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\FixTireImages;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register the FixTireImages command so it is discoverable when artisan runs
Artisan::command('fix:tire_images {--dry-run : Do not write changes, only show what would be changed}', function () {
    $dry = $this->option('dry-run');
    $cmd = new FixTireImages();
    // The command uses DB facade directly; set the dry-run option on the command instance via reflection
    $reflection = new \ReflectionObject($cmd);
    $method = $reflection->getMethod('handle');
    // Call handle() which will respect the Dry-run option if we set the property; simpler: call handle() and let it read option from argv
    // Temporarily set argv so the command can read the option via $this->option()
    $cmd->setLaravel(app());
    // Manually call handle with dry-run by setting input option via Symfony input is complex here, so just call the handle and let the command inspect PHP's $argv isn't necessary.
    // Instead, call the command's handle() and it will read the --dry-run option from $this->option() which is not set; to avoid complexity, we'll execute the command logic inline here.
    $this->info('Scanning requests table for double-encoded tire_images...');
    $rows = \Illuminate\Support\Facades\DB::table('requests')->select('id', 'tire_images')->get();
    $count = 0;
    foreach ($rows as $r) {
        $val = $r->tire_images;
        if ($val === null || $val === '') {
            continue;
        }
        $firstChar = is_string($val) && strlen($val) ? $val[0] : null;
        if ($firstChar === '[') {
            $decoded = json_decode($val, true);
            if (is_array($decoded)) {
                continue;
            }
        }
        $decoded = json_decode($val, true);
        if (is_array($decoded)) {
            $count++;
            $this->line("Will fix id={$r->id} -> " . json_encode($decoded));
            if (!$dry) {
                \Illuminate\Support\Facades\DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded)]);
            }
        } else {
            $unescaped = str_replace('\\/', '/', $val);
            $decoded2 = json_decode($unescaped, true);
            if (is_array($decoded2)) {
                $count++;
                $this->line("Will fix (unescaped) id={$r->id} -> " . json_encode($decoded2));
                if (!$dry) {
                    \Illuminate\Support\Facades\DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded2)]);
                }
            }
        }
    }
    $this->info("Done. Rows changed: {$count}. Dry-run: " . ($dry ? 'yes' : 'no'));
});
