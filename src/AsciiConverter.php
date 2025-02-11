<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Config\Presets;
use App\Y0f\Contracts\ImageInterface;

class AsciiConverter
{
    // Integer-optimized luminance weights (0.2126 * 1000 = 2126, etc.)
    private const R_WEIGHT = 2126;
    private const G_WEIGHT = 7152;
    private const B_WEIGHT = 722;

    private const WEIGHT_TOTAL = 10000;

    private int $characterCount;

    private array $styledCharacters;

    public function __construct(
        private string $characterSet = Presets::ASCII_CHARACTER_SET,
    ) {
        $this->characterCount = strlen($characterSet);
        $this->precomputeStyledCharacters();
    }

    // Precomputed templates used to build
    private function precomputeStyledCharacters(): void
    {
        $this->styledCharacters = [];
        for ($i = 0; $i < $this->characterCount; $i++) {
            $this->styledCharacters[] = '<span style="color:%s;">'.$this->characterSet[$i].'</span>';
        }
    }

    public function convert(ImageInterface $image): string
    {
        $width = $image->getWidth();
        $height = $image->getHeight();

        $minBrightness = 255;
        $maxBrightness = 0;
        $pixelGrid = [];

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $colors = $image->getPixelColor($x, $y);
                $r = $colors['red'];
                $g = $colors['green'];
                $b = $colors['blue'];

                // Integer-based luminance calculation
                $brightness = (int) (($r * self::R_WEIGHT + $g * self::G_WEIGHT + $b * self::B_WEIGHT) / self::WEIGHT_TOTAL);

                $hex = '#'.
                       str_pad(dechex($r), 2, '0', STR_PAD_LEFT).
                       str_pad(dechex($g), 2, '0', STR_PAD_LEFT).
                       str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

                $pixelGrid[$y][$x] = ['b' => $brightness, 'h' => $hex];

                if ($brightness < $minBrightness) {
                    $minBrightness = $brightness;
                }
                if ($brightness > $maxBrightness) {
                    $maxBrightness = $brightness;
                }
            }
        }

        $range = $maxBrightness - $minBrightness;
        $scale = $range > 0 ? ($this->characterCount - 1) / $range : 0;

        $rows = [];
        for ($y = 0; $y < $height; $y++) {
            $row = '';
            foreach ($pixelGrid[$y] as $pixel) {
                $normalized = $pixel['b'] - $minBrightness;
                $charIndex = $scale > 0 ? (int) round($normalized * $scale) : 0;
                $charIndex = min($charIndex, $this->characterCount - 1);
                $row .= sprintf($this->styledCharacters[$charIndex], $pixel['h']);
            }
            $rows[] = $row;
        }

        return implode("\n", $rows);
    }
}
