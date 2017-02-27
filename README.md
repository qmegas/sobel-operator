Sobel operator
==============
This is Sobel operator (or Sobel filter) implementation on PHP. It used in image processing and computer vision, particularly within edge detection algorithms.
Read full article in [Wikipedia](https://en.wikipedia.org/wiki/Sobel_operator)

Installation
------------
```bash
composer require qmegas/sobel-operator
```

Requirements
------------
PHP >= 7.0

Usage Examples
--------------
```php
<?php

$sobel = new \Qmegas\SobelOperator();
$image = imagecreatefromjpeg('1.jpg');
header('Content-type: image/png');
imagepng($sobel->applyFilter($image));
```
<img src="/qmegas/sobel-operator/images/1.jpg">

Using threshold (value must be between 0-100)
```php
<?php

$sobel = new \Qmegas\SobelOperator();
$image = imagecreatefromjpeg('1.jpg');
header('Content-type: image/png');
imagepng($sobel->applyFilter($image, [
	'threshold' => 30,
]));
```
<img src="/qmegas/sobel-operator/images/2.jpg">

Using flat mode
```php
<?php

$sobel = new \Qmegas\SobelOperator();
$image = imagecreatefromjpeg('1.jpg');
header('Content-type: image/png');
imagepng($sobel->applyFilter($image, [
	'flat' => true,
	'threshold' => 30,
]));
```
<img src="/qmegas/sobel-operator/images/3.jpg">