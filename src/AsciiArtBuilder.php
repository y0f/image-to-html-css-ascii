<?php

declare(strict_types=1);

namespace App\Y0f;

use BadMethodCallException;

class AsciiArtBuilder
{
    public function __construct(
        private AsciiStyleConfigurator $styleConfigurator,
        private AsciiRenderer $renderer
    ) {}

    /**
     * Handles dynamic method calls to apply styles to ASCII art.
     *
     * Intercepts methods starting with 'with' (e.g., `withFontSize`, `withBackgroundColor`)
     * and maps them to corresponding setters in `AsciiStyleConfigurator` (e.g., `setFontSize`, `setBackgroundColor`).
     *
     * Example:
     * - `withFontSize(12)` calls `setFontSize(12)`.
     * - `withBackgroundColor('black')` calls `setBackgroundColor('black')`.
     *
     * @param string $name Method name.
     * @param array $arguments Method arguments.
     * @return self For method chaining.
     * @throws BadMethodCallException If the method is invalid or unsupported.
     */
    public function __call(string $name, array $arguments): self
    {
        if (strpos($name, 'with') === 0) {
            $method = 'set' . substr($name, 4);
            if (method_exists($this->styleConfigurator, $method)) {
                $this->styleConfigurator->$method(...$arguments);
                return $this;
            }
        }

        throw new BadMethodCallException("Method {$name} does not exist in " . __CLASS__);
    }

    public function build(string $asciiArt): string
    {
        return $this->renderer->render($asciiArt, $this->styleConfigurator);
    }
}
