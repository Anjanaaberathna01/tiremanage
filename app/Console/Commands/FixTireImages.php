<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTireImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:tire_images {--dry-run : Do not write changes, only show what would be changed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix double-encoded JSON strings in requests.tire_images by decoding to real JSON arrays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dry = $this->option('dry-run');

        $this->info('Scanning requests table for double-encoded tire_images...');

        $rows = DB::table('requests')->select('id', 'tire_images')->get();
        $count = 0;
        foreach ($rows as $r) {
            $val = $r->tire_images;
            if ($val === null || $val === '') {
                continue;
            }

            // If the value is already a JSON array (starts with [) and decodes to array, skip
            $firstChar = is_string($val) && strlen($val) ? $val[0] : null;
            if ($firstChar === '[') {
                $decoded = json_decode($val, true);
                if (is_array($decoded)) {
                    // Already proper JSON array
                    continue;
                }
            }

            // Attempt to json_decode the string (it might be a JSON string containing a JSON array)
            $decoded = json_decode($val, true);
            if (is_array($decoded)) {
                $count++;
                $this->line("Will fix id={$r->id} -> " . json_encode($decoded));
                if (!$dry) {
                    DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded)]);
                }
            } else {
                // If decoding failed, try to unescape common backslashes and try again
                $unescaped = str_replace('\\/', '/', $val);
                $decoded2 = json_decode($unescaped, true);
                if (is_array($decoded2)) {
                    $count++;
                    $this->line("Will fix (unescaped) id={$r->id} -> " . json_encode($decoded2));
                    if (!$dry) {
                        DB::table('requests')->where('id', $r->id)->update(['tire_images' => json_encode($decoded2)]);
                    }
                }
            }
        }

        $this->info("Done. Rows changed: {$count}. Dry-run: " . ($dry ? 'yes' : 'no'));
        return 0;
    }
}
