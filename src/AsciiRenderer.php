<?php

declare(strict_types=1);

namespace App\Y0f;

class AsciiRenderer
{
    private string $template = '
        <div style="display: flex; justify-content: center; align-items: center;">
            <pre style="%s">%s</pre>
        </div>
    ';

    public function render(string $asciiArt, AsciiStyleConfigurator $styleConfigurator): string
    {
        $styles = $styleConfigurator->buildStyles();

        return sprintf($this->template, $styles, $asciiArt);
    }
}
