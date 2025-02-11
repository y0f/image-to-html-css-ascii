<?php

declare(strict_types=1);

namespace App\Y0f\Contracts;

interface FilterInterface
{
    public function apply(ImageInterface $image): void;
}
