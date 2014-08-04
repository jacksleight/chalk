<?php
return [
	'resize' => function($image, $params) {
		if (extension_loaded('exif')) {
			$orientation = $image->exif('Orientation');
			switch ($orientation) {
				case 2:
					$image->flip('h');
				break;
				case 3:
					$image->rotate(180);
				break;
				case 4:
					$image->flip('v');
				break;
				case 5:
					$image->flip('v')->rotate(-90);
				break;
				case 6:
					$image->rotate(-90);
				break;
				case 7:
					$image->flip('h')->rotate(-90);
				break;
				case 8:
					$image->rotate(90);
				break;
			}
		}
		$width = isset($params['size'])
			? $params['size']
			: $params['width'];
		$height	= isset($params['size'])
			? $params['size']
			: $params['height'];
		$width	= $width * 2;
		$height	= $height * 2;
		if ($params['crop']) {
			list($a, $b) = \Coast\math_ratio($width, $height);
			$ratio = $a / $b;
			$crop  = (object) [
				'width'  => $image->width,
				'height' => $image->height,
			];
			if ($ratio > 1) {
				if ($image->width / $ratio > $image->height) {
					$crop->width = floor($image->height * $ratio);
				} else {
					$crop->height = floor($image->width / $ratio);
				}
			} elseif ($ratio > 0) {
				if ($image->height * $ratio > $image->width) {
					$crop->height = floor($image->width / $ratio);
				} else {
					$crop->width = floor($image->height * $ratio);
				}
			}
			$image->crop($crop->width, $crop->height);
			$image->resize($width, $height);
		} else {
			$image->resize($width, $height, true);
		}
	}
];