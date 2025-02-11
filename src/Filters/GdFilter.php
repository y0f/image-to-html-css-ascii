<?php

declare(strict_types=1);

namespace App\Y0f\Filters;

use App\Y0f\Adapters\GdImageAdapter;
use App\Y0f\Contracts\FilterInterface;
use App\Y0f\Contracts\ImageInterface;

class GdFilter implements FilterInterface
{
    private int $filterType;

    private array $args;

    public function __construct(int $filterType, mixed ...$args)
    {
        $this->filterType = $filterType;
        $this->args = $args;
    }

    public function apply(ImageInterface $image): void
    {
        if ($image instanceof GdImageAdapter) {
            imagefilter($image->getImage(), $this->filterType, ...$this->args);
        }
    }
}
