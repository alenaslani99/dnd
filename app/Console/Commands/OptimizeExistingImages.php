<?php

namespace App\Console\Commands;

use App\Actions\OptimizeImage;
use Illuminate\Console\Command;

class OptimizeExistingImages extends Command
{
    protected $signature = 'images:optimize
        {dir=public/assets/img : Directory containing images to optimize}';

    protected $description = 'Generate responsive image variants (sm, md, lg) for all existing images';

    public function handle(OptimizeImage $optimizer): int
    {
        $dir = $this->argument('dir');
        $files = glob($dir.'/*.webp');

        if (empty($files)) {
            $this->warn("No files found matching {$dir}/{$pattern}");

            return Command::FAILURE;
        }

        $this->info('Found '.count($files).' images to process.');

        $processed = 0;
        $skipped = 0;

        foreach ($files as $file) {
            // Skip already-generated variants
            if (preg_match('/_(sm|md|lg)\.webp$/', $file)) {
                $skipped++;

                continue;
            }

            $this->line('Processing: '.basename($file));

            try {
                $optimizer->execute($file);
                $processed++;
            } catch (\Exception $e) {
                $this->error("Failed: {$file} — {$e->getMessage()}");
            }
        }

        $this->info("Done. Processed: {$processed}, Skipped (variants): {$skipped}");

        return Command::SUCCESS;
    }
}
