<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Contracts\FilterInterface;
use App\Y0f\Contracts\ImageInterface;

class ImageFilterProcessor
{
    private array $filters = [];

    public function addFilter(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
    }

    public function applyFilters(ImageInterface $image): ImageInterface
    {
        foreach ($this->filters as $filter) {
            $filter->apply($image);
        }

        return $image;
    }
}
