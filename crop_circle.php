<?php 
function circleCrop($file, $square, $max_size, $out_file)
{
	// Open image
	$image = new \Imagick($file);
	$image_width = $image->getImageWidth();
	$image_height = $image->getImageHeight();
	$min = min($image_width, $image_height);
	$max = max($image_width, $image_height);
	if($min > $max_size)
	{
		$max = $max * $max_size / $min;
		if($image_width == $min)
		{
			$min = $max_size;
			$new_width = $image_width * $min / $image_width;
			$new_height = $image_height * $max / $image_height;
		}
		else
		{
			$min = $max_size;
			$new_width = $image_width * $max / $image_width;
			$new_height = $image_height * $min / $image_height;
		}

		$new_width = floor($new_width);
		$new_height = floor($new_height);

		$image->adaptiveResizeImage($new_width, $new_height); 
		$image_width = $image->getImageWidth();
		$image_height = $image->getImageHeight();
		$min = min($image_width, $image_height);
		$max = max($image_width, $image_height);
	}
	if($square)
	{
		$min_half = $min / 2;

		$width_half = $image_width / 2;
		$height_half = $image_height / 2;
		$image->cropImage( $min, $min, $width_half - $min_half, $height_half - $min_half);
//		$image->writeImage('image3.png'); 

		$image_width = $image->getImageWidth();
		$image_height = $image->getImageHeight();
		$min = min($image_width, $image_height);
		$max = max($image_width, $image_height);
	}

	// Create, draw mask
	$mask = new \Imagick();
	$mask->newImage($image->getImageWidth(), $image->getImageHeight(), 'transparent', 'png');

	$circleSize = $min - $min * 0.05;
	$r = $circleSize / 2;
	echo "$min $max $circleSize <br/>";

	$mask_shape = new \ImagickDraw();
	$mask_shape->setStrokeAntialias(true);

	$mask_shape->setFillColor('white');
	$mask_shape->setStrokeColor('white');
	$mask_shape->circle($image_width/2, $image_height/2, $max / 2 + $r, $min / 2);

	$mask->drawImage($mask_shape);

	// Apply mask to image
	$image->compositeImage($mask, \Imagick::COMPOSITE_COPYOPACITY, 0, 0, \Imagick::CHANNEL_ALL); 
	$image->writeImage($out_file); 
	$image->destroy();
}
