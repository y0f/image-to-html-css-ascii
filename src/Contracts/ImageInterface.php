<?php

declare(strict_types=1);

namespace App\Y0f\Contracts;

interface ImageInterface
{
    public function getWidth(): int;

    public function getHeight(): int;

    public function getPixelColor(int $x, int $y): array;

    public function resize(?int $width, ?int $height): self;

    public function apply(FilterInterface $filter): self;
}
