<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Adapters\GdImageAdapter;
use App\Y0f\Contracts\ImageInterface;
use App\Y0f\Exceptions\InvalidImageTypeException;
use App\Y0f\Exceptions\ImageProcessingException;

class ImageLoader
{
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB Limit
    private const ALLOWED_TYPES = [
        IMAGETYPE_PNG => 'image/png',
        IMAGETYPE_JPEG => 'image/jpeg',
        IMAGETYPE_WEBP => 'image/webp',
        IMAGETYPE_GIF => 'image/gif',
    ];

    public function load(string $imagePath): ImageInterface
    {
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            throw new ImageProcessingException("File does not exist or is not readable: {$imagePath}");
        }

        if (filesize($imagePath) > self::MAX_FILE_SIZE) {
            throw new ImageProcessingException("File size exceeds the 5MB limit.");
        }

        $type = exif_imagetype($imagePath);
        if ($type === false || !isset(self::ALLOWED_TYPES[$type])) {
            throw new InvalidImageTypeException('Invalid or unsupported image type.');
        }

        $fileMimeType = mime_content_type($imagePath);
        if ($fileMimeType !== self::ALLOWED_TYPES[$type]) {
            throw new InvalidImageTypeException('MIME type does not match expected image type.');
        }

        $image = match ($type) {
            IMAGETYPE_PNG => imagecreatefrompng($imagePath),
            IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($imagePath),
            IMAGETYPE_GIF => imagecreatefromgif($imagePath),
            default => throw new InvalidImageTypeException('Unexpected error loading image.'),
        };

        if (!$image) {
            throw new ImageProcessingException("Failed to load image: {$imagePath}");
        }

        return new GdImageAdapter($image);
    }
}
