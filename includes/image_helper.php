<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Resizes an image and caches it.
 * 
 * @param string $sourcePath Path to the source image relative to root
 * @param int $width Target width
 * @param int $height Target height
 * @param string $method 'cover' or 'resize'
 * @return string Path to the resized image
 */
function get_resized_image($sourcePath, $width, $height, $method = 'cover')
{
    $absSourcePath = __DIR__ . '/../' . $sourcePath;

    if (!file_exists($absSourcePath)) {
        return $sourcePath;
    }

    $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
    $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
    $cacheDir = __DIR__ . '/../assets/images/cache/';

    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $cacheFilename = "{$filename}_{$width}x{$height}_{$method}.{$extension}";
    $cachePath = $cacheDir . $cacheFilename;
    $publicCachePath = 'assets/images/cache/' . $cacheFilename;

    // Return cached image if it exists and source hasn't changed
    if (file_exists($cachePath) && filemtime($cachePath) >= filemtime($absSourcePath)) {
        return $publicCachePath;
    }

    try {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($absSourcePath);

        if ($method === 'cover') {
            $image->cover($width, $height);
        } elseif ($method === 'contain') {
            $image->contain($width, $height);
        } else {
            $image->resize($width, $height);
        }

        $image->save($cachePath);
        return $publicCachePath;
    } catch (Exception $e) {
        // Log error and return original path on failure
        error_log("Image Resizing Error: " . $e->getMessage());
        return $sourcePath;
    }
}
