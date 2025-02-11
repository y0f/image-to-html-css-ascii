<?php

declare(strict_types=1);

namespace App\Y0f;

use Illuminate\Support\ServiceProvider;

class AsciiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ascii', function ($app, array $parameters) {
            if (! isset($parameters['imagePath'])) {
                throw new \InvalidArgumentException('The imagePath parameter is required.');
            }

            return Ascii::make($parameters['imagePath']);
        });
    }
}
