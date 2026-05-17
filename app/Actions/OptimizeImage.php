<?php

namespace App\Actions;

use Imagick;
use RuntimeException;

class OptimizeImage
{
    const array SIZES = [
        'sm' => 400,
        'md' => 800,
        'lg' => 1200,
    ];

    const int QUALITY = 82;

    public function execute(string $sourcePath): void
    {
        if (! file_exists($sourcePath)) {
            throw new RuntimeException("Source image not found: {$sourcePath}");
        }

        $info = pathinfo($sourcePath);
        $dir = $info['dirname'];
        $base = $info['filename'];

        foreach (self::SIZES as $suffix => $width) {
            $destPath = "{$dir}/{$base}_{$suffix}.webp";

            if (file_exists($destPath)) {
                continue;
            }

            $this->resizeToWebP($sourcePath, $destPath, $width);
        }
    }

    private function resizeToWebP(string $source, string $dest, int $targetWidth): void
    {
        $image = new Imagick($source);

        $geometry = $image->getImageGeometry();
        $originalWidth = $geometry['width'];

        if ($originalWidth <= $targetWidth) {
            $targetWidth = $originalWidth;
        }

        $image->resizeImage($targetWidth, 0, Imagick::FILTER_LANCZOS, 1);

        $image->setImageFormat('webp');
        $image->setOption('webp:method', '6');
        $image->setOption('webp:lossless', 'false');
        $image->setImageCompressionQuality(self::QUALITY);

        if (! $image->writeImage($dest)) {
            throw new RuntimeException("Failed to write resized image: {$dest}");
        }

        $image->clear();
    }
}
