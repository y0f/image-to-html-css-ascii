<?php

declare(strict_types=1);

namespace App\Y0f\Adapters;

use App\Y0f\Contracts\FilterInterface;
use App\Y0f\Contracts\ImageInterface;
use GdImage;

class GdImageAdapter implements ImageInterface
{
    public function __construct(private GdImage $image) {}

    public function getWidth(): int
    {
        return imagesx($this->image);
    }

    public function getHeight(): int
    {
        return imagesy($this->image);
    }

    public function getPixelColor(int $x, int $y): array
    {
        $rgb = imagecolorat($this->image, $x, $y);

        return imagecolorsforindex($this->image, $rgb);
    }

    public function resize(?int $width, ?int $height): self
    {
        $resizedImage = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $resizedImage,
            $this->image,
            0, 0, 0, 0,
            $width, $height,
            $this->getWidth(), $this->getHeight()
        );
        $this->image = $resizedImage;

        return $this;
    }

    public function apply(FilterInterface $filter): self
    {
        $filter->apply($this);

        return $this;
    }

    public function getImage(): GdImage
    {
        return $this->image;
    }
}
