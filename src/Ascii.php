<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Config\Presets;
use App\Y0f\Exceptions\ImageProcessingException;
use App\Y0f\Filters\Filter;
use BadMethodCallException;
use Exception;
use InvalidArgumentException;

final class Ascii
{
    private function __construct(
        private readonly string $imagePath,
        private readonly ImageLoader $imageLoader,
        private readonly ImageFilterProcessor $imageFilterProcessor,
        private AsciiConverter $asciiConverter,
        private AsciiArtBuilder $asciiArtBuilder,
        private int $newWidth,
        private int $newHeight
    ) {}

    public static function make(
        string $imagePath,
        ?ImageLoader $imageLoader = null,
        ?ImageFilterProcessor $imageFilterProcessor = null,
        ?AsciiConverter $asciiConverter = null,
        ?AsciiArtBuilder $asciiArtBuilder = null,
        ?int $newWidth = null,
        ?int $newHeight = null
    ): self {
        self::validatePath($imagePath);

        return new self(
            $imagePath,
            $imageLoader ?? new ImageLoader,
            $imageFilterProcessor ?? new ImageFilterProcessor,
            $asciiConverter ?? new AsciiConverter(Presets::ASCII_CHARACTER_SET),
            $asciiArtBuilder ?? new AsciiArtBuilder(
                new AsciiStyleConfigurator,
                new AsciiRenderer
            ),
            $newWidth ?? Presets::ASCII_IMAGE_WIDTH,
            $newHeight ?? Presets::ASCII_IMAGE_HEIGHT
        );
    }

    private static function validatePath(string $path): void
    {
        match (true) {
            !file_exists($path) => throw new InvalidArgumentException("Image file does not exist: {$path}"),
            !is_readable($path) => throw new InvalidArgumentException("Image file is not readable: {$path}"),
            default => null,
        };
    }

    public function withCharacters(string $characters): self
    {
        if (empty($characters)) {
            throw new InvalidArgumentException('Character set cannot be empty.');
        }

        $this->asciiConverter = new AsciiConverter($characters);

        return $this;
    }

    public function withDimensions(?int $width, ?int $height): self
    {
        match (true) {
            $width !== null && $width <= 0 => throw new InvalidArgumentException('Width must be a positive integer.'),
            $height !== null && $height <= 0 => throw new InvalidArgumentException('Height must be a positive integer.'),
            default => null,
        };

        $this->newWidth = $width;
        $this->newHeight = $height;

        return $this;
    }

    public function addFilter(Filter $filter): self
    {
        $this->imageFilterProcessor->addFilter($filter);

        return $this;
    }

    public function __call(string $name, array $arguments): self
    {
        if (!method_exists($this->asciiArtBuilder, $name) && !method_exists($this->asciiArtBuilder, '__call')) {
            throw new BadMethodCallException("Method {$name} not found in " . __CLASS__);
        }
    
        $this->asciiArtBuilder->$name(...$arguments);

        return $this;
    }

    public function render(): string
    {
        try {
            $image = $this->loadImage();
            $resizedImage = $this->resizeImage($image);
            $filteredAscii = $this->applyFilters($resizedImage);
            $asciiArt = $this->convertToAscii($filteredAscii);

            return $this->buildAsciiArt($asciiArt);
        } catch (ImageProcessingException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ImageProcessingException('Failed to render ASCII art: ' . $e->getMessage(), 0, $e);
        }
    }

    private function loadImage(): object
    {
        return $this->imageLoader->load($this->imagePath);
    }

    private function resizeImage(object $image): object
    {
        return $image->resize($this->newWidth, $this->newHeight);
    }

    private function applyFilters(object $image): object
    {
        return $this->imageFilterProcessor->applyFilters($image);
    }

    private function convertToAscii(object $image): string
    {
        return $this->asciiConverter->convert($image);
    }

    private function buildAsciiArt(string $asciiArt): string
    {
        return $this->asciiArtBuilder->build($asciiArt);
    }

    public function __toString(): string
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            return 'Error rendering ASCII art: '.$e->getMessage();
        }
    }
}
