<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Adapters\GdImageAdapter;
use App\Y0f\Contracts\ImageInterface;
use App\Y0f\Exceptions\InvalidImageTypeException;

class ImageLoader
{
    public function load(string $imagePath): ImageInterface
    {
        $type = exif_imagetype($imagePath);

        $image = match ($type) {
            IMAGETYPE_PNG => imagecreatefrompng($imagePath),
            IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($imagePath),
            IMAGETYPE_GIF => imagecreatefromgif($imagePath),
            default => throw new InvalidImageTypeException('Invalid image type'),
        };

        return new GdImageAdapter($image);
    }
}
