<?php

chdir("/home/painh/www/nod/img/");

$uploaddir = 'imgs/';
$uploadfile = $uploaddir . time(). basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
//		error_log( "File is valid, and was successfully uploaded.");
} else {
		error_log( "Possible file upload attack!");
		error_log( 'Here is some more debugging info:');
		error_log(print_r($_FILES, true));
}

		error_log(print_r($_FILES, true));


$size = getimagesize($uploadfile); 

$layer = imagecreatetruecolor($size[0], $size[1]);
$bg = imagecolorallocate($layer, 0, 0, 0);
$col_ellipse = imagecolorallocate($layer, 255, 255, 255);

$min = min($size[0], $size[1]);
$circleSize = $min - $min * 0.05;
imagefilledellipse($layer, $size[0]/2, $size[1] / 2, $circleSize , $circleSize , $col_ellipse);

$ext = strtolower( pathinfo($uploadfile, PATHINFO_EXTENSION) );

if($ext == 'jpg' || $ext == 'jpeg')
	$originImg = imagecreatefromjpeg($uploadfile); 
if($ext == 'png')
	$originImg = imagecreatefrompng($uploadfile); 

if($originImg)
{
	for($i = 0; $i < $size[0];++$i)
		for($j = 0; $j < $size[1];++$j)
		{
			$color = imagecolorat($layer, $i, $j);

			if($color == 0)
				imagesetpixel($originImg, $i, $j, $col_ellipse);
		}
}

function mycrop($src, array $rect)
{
    $dest = imagecreatetruecolor($rect['width'], $rect['height']);
    imagecopyresized(
        $dest,
        $src,
        0,
        0,
        $rect['x'],
        $rect['y'],
        $rect['width'],
        $rect['height'],
        $rect['width'],
        $rect['height']
    );

    return $dest;
}

if($_POST['square'])
{
	$min_half = $min / 2;

	$width_half = $size[0] / 2;
	$height_half = $size[1] / 2;
	$originImg = mycrop($originImg, ['x' => $width_half - $min_half,
										'y' => $height_half - $min_half,
										'width' => $min,
										'height' => $min]);
}

//header("Content-type: image/png");
$time = time();
$filename = "imgs/con_".$time.".".$ext;
if($ext == 'jpg' || $ext == 'jpeg')
	$ret = imagejpeg($originImg,$filename);
if($ext == 'png')
	$ret = imagepng($originImg,$filename);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>


<?php
	echo "<a href ='$filename'><img src='$filename'></img></a>";
?>
	
</body>
</html>
