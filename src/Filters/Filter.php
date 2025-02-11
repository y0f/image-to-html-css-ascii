<?php

declare(strict_types=1);

namespace App\Y0f\Filters;

use App\Y0f\Contracts\FilterInterface;
use App\Y0f\Contracts\ImageInterface;

/**
 * Represents a filter that can be applied to an image.
 */
class Filter implements FilterInterface
{
    /** @var int The type of filter to apply (e.g., IMG_FILTER_BRIGHTNESS). */
    private int $type;

    /** @var array The arguments to pass to the filter. */
    private array $args;

    /**
     * Constructs a new Filter instance.
     *
     * @param  int  $type  The type of filter to apply.
     * @param  array  $args  The arguments to pass to the filter.
     */
    public function __construct(int $type, array $args = [])
    {
        $this->type = $type;
        $this->args = $args;
    }

    /**
     * Applies the filter to the given image.
     *
     * @param  ImageInterface  $image  The image to apply the filter to.
     */
    public function apply(ImageInterface $image): void
    {
        $gdFilter = new GdFilter($this->type, ...$this->args);
        $gdFilter->apply($image);
    }

    /**
     * Returns the filter configuration.
     *
     * @return array{int, mixed[]} An array containing the filter type and arguments.
     */
    public function getConfig(): array
    {
        return [$this->type, $this->args];
    }

    /**
     * Creates a brightness filter.
     *
     * @param  int  $level  Brightness level (-255 to 255).
     * @return self A new Filter instance.
     */
    public static function brightness(int $level): self
    {
        return new self(IMG_FILTER_BRIGHTNESS, [$level]);
    }

    /**
     * Creates a contrast filter.
     *
     * @param  int  $level  Contrast level (-100 to 100).
     * @return self A new Filter instance.
     */
    public static function contrast(int $level): self
    {
        return new self(IMG_FILTER_CONTRAST, [$level]);
    }

    /**
     * Creates a colorize filter.
     *
     * @param  int  $red  Red component (0-255).
     * @param  int  $green  Green component (0-255).
     * @param  int  $blue  Blue component (0-255).
     * @param  int  $alpha  Alpha transparency (0-127).
     * @return self A new Filter instance.
     */
    public static function colorize(int $red, int $green, int $blue, int $alpha = 0): self
    {
        return new self(IMG_FILTER_COLORIZE, [$red, $green, $blue, $alpha]);
    }

    /**
     * Creates a grayscale filter.
     *
     * @return self A new Filter instance.
     */
    public static function grayscale(): self
    {
        return new self(IMG_FILTER_GRAYSCALE);
    }

    /**
     * Creates a pixelate filter.
     *
     * @param  int  $blockSize  Pixelation block size.
     * @param  bool  $advanced  Whether to use advanced pixelation.
     * @return self A new Filter instance.
     */
    public static function pixelate(int $blockSize, bool $advanced = true): self
    {
        return new self(IMG_FILTER_PIXELATE, [$blockSize, $advanced]);
    }
}
