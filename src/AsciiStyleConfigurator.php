<?php

declare(strict_types=1);

namespace App\Y0f;

use App\Y0f\Config\Presets;

class AsciiStyleConfigurator
{
    private array $styles;

    public function __construct(array $customStyles = [])
    {
        $this->styles = array_merge(Presets::ASCII_DEFAULT_STYLES, $customStyles);
    }

    public function setFontSize(int $fontSize): self
    {
        return $this->setStyle('font-size', "{$fontSize}px");
    }

    public function setLineHeight(int $lineHeight): self
    {
        return $this->setStyle('line-height', "{$lineHeight}px");
    }

    public function setLetterSpacing(float $letterSpacing): self
    {
        return $this->setStyle('letter-spacing', "{$letterSpacing}px");
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        return $this->setStyle('background-color', $backgroundColor);
    }

    public function setFontFamily(string $fontFamily): self
    {
        return $this->setStyle('font-family', $fontFamily);
    }

    public function setCustomStyle(string $property, string $value): self
    {
        return $this->setStyle($property, $value);
    }

    private function setStyle(string $property, string $value): self
    {
        $this->styles[$property] = $value;

        return $this;
    }

    public function buildStyles(): string
    {
        return implode(' ', array_map(
            fn ($property, $value) => "{$property}: {$value};",
            array_keys($this->styles),
            $this->styles
        ));
    }
}
