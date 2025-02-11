# Image to ASCII Converter

## Features

A silly little project that converts images into ASCII art using HTML & CSS.

- Adjustable output.
- Custom character sets.
- It is easy to use.

## Requirements

- **PHP Version**: 8.0 or higher.
- **GD Library**: Ensure the GD library is enabled in your `php.ini` file:

  ```ini
  extension=gd
  ```

## Installation

Clone the repository:
  ```
  git clone https://github.com/y0f/image-to-html-css-ascii.git
  ```

If you're not using a framework:

  ```bash
  composer install
  ```

For Laravel users:

In your Laravel project, add the following to the ```composer.json``` file:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/y0f/image-to-html-css-ascii.git"
        }
    ],
    "require": {
        "y0f/image-to-html-css-ascii": "dev-main"
    }
}
```

Then run
```bash
composer update
```

## Basic usage

```php
  <?php

  namespace App\Http\Controllers;

  use App\Y0f\Ascii;
  use App\Y0f\Filters\Filter;

  class TestController extends Controller
  {
      public function show()
      {
          $imagePath = public_path('images/car.jpg');

          $asciiArt = Ascii::make($imagePath)
              ->withCharacters('0123456789')
              ->withDimensions(width: 150, height: 80)
              ->withFontSize(7)
              ->withLineHeight(8)
              ->withLetterSpacing(3)
              ->withBackgroundColor('transparent')
              ->addFilter(Filter::brightness(45))
              ->addFilter(Filter::contrast(-30))
              //->addFilter(Filter::colorize(255, 0, 0))
              //->addFilter(Filter::grayscale())
              //->addFilter(Filter::pixelate(10))
              ;

          return view('test', ['asciiArt' => $asciiArt]);
      }
  }
  ```

The methods used to customize the output are optional; defaults are provided in:
  ```
  App\Y0f\Config\Defaults.php
  ```

## Output:

![Naamloos](https://github.com/user-attachments/assets/29820a97-e20d-4dd1-92ed-1ae8877ae81a)



