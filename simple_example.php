<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Y0f\Ascii;

$imagePath = __DIR__ . '/image.jpg';

$asciiArt = Ascii::make($imagePath)
    ->withCharacters('OfY0Fy')
    ->withFontSize(9)
    ->withDimensions(width: 100, height: 100)
    ->withLineHeight(9)
    ->withLetterSpacing(4.2)
    ->withBackgroundColor('transparent');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASCII Art</title>
    <style>
    </style>
</head>

<body style="background-color: black">
    <pre><?php echo $asciiArt; ?></pre>
</body>

</html>
