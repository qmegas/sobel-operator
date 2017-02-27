<?php

namespace Qmegas;

class SobelOperator
{
	protected $matrixSizeX = 3;
	protected $matrixSizeY = 3;
	protected $matrixX = [[-1, 0, 1], [-2, 0, 2], [-1, 0, 1]];
	protected $matrixY = [[-1, -2, -1], [0, 0, 0], [1, 2, 1]];
	
	public function applyFilter($image, array $options = [])
	{
		$width = imagesx($image);
		$height = imagesy($image);
		$isFlat = (isset($options['flat']) && $options['flat'] === true);
		
		imagefilter($image, IMG_FILTER_GRAYSCALE);
		
		$newImage = imagecreatetruecolor($width, $height);
		
		$colors = [];
		for ($i = 0; $i < 256; ++$i) {
			$colors[$i] = imagecolorallocate($image, $i, $i, $i);
		}
		
		$img = [];
		$finalImage = [];
		for ($x = 0; $x < $width; ++$x) {
			$img[$x] = array_fill(0, $height, 0);
			$finalImage[$x] = array_fill(0, $height, 0);
			for ($y = 0; $y < $height; ++$y) {
				$img[$x][$y] = $this->getColorIndex(imagecolorat($image, $x, $y));
			}
		}
		
		$maxValue = 0;
		$offsetX = ($this->matrixSizeX - 1) / 2;
		$offsetY = ($this->matrixSizeY - 1) / 2;
		for ($x = 0, $toX = $width - $this->matrixSizeX; $x < $toX; ++$x) {
			for ($y = 0, $toY = $height - $this->matrixSizeY; $y < $toY; ++$y) {
				$pixelX = $pixelY = 0;
				for ($pX = 0; $pX < $this->matrixSizeX; ++$pX) {
					for ($pY = 0; $pY < $this->matrixSizeY; ++$pY) {
						$pixelX += $this->matrixX[$pX][$pY] * $img[$x + $pX][$y + $pY];
						$pixelY += $this->matrixY[$pX][$pY] * $img[$x + $pX][$y + $pY];
					}
				}
				$val = ceil(sqrt(($pixelX ** 2) + ($pixelY ** 2)));
				
				$maxValue = max($maxValue, $val);
				
				$finalImage[$offsetX + $x][$offsetY + $y] = $val;
			}
		}
		
		$threshold = isset($options['threshold']) ? intval($options['threshold']) * $maxValue / 100  : 0;
		
		for ($x = 0; $x < $width; ++$x) {
			for ($y = 0; $y < $height; ++$y) {
				$val = $finalImage[$x][$y];
				
				if ($val <= $threshold) {
					$val = 0;
				}
				
				$val = ($val > 255) ? 255 : $val;
				
				if ($isFlat && $val > 0) {
					$val = 255;
				}
				
				imagesetpixel($newImage, $x, $y, $colors[$val]);
			}
		}
		
		return $newImage;
	}
	
	protected function getColorIndex(int $color) : int
	{
		return ($color & 0xFF);
	}
}