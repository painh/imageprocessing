<?php

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


//$uploadfile = './imgs/14268685682015-03-20 15.12.22.jpg';
//$_POST['crop_min'] = 1;
//$_POST['square'] = false;
$size = getimagesize($uploadfile); 
$ext = strtolower( pathinfo($uploadfile, PATHINFO_EXTENSION) );

if($ext == 'jpg' || $ext == 'jpeg')
	$originImg = imagecreatefromjpeg($uploadfile); 
if($ext == 'png')
	$originImg = imagecreatefrompng($uploadfile); 

$min = min($size[0], $size[1]);
$max = max($size[0], $size[1]);
$MIN_SIZE = 800;
if($_POST['crop_min'] && $min > $MIN_SIZE)
{
	$max = $max * $MIN_SIZE / $min;
	if($size[0] == $min)
	{
		$min = $MIN_SIZE;
		$new_width = $size[0] * $min / $size[0];
		$new_height = $size[1] * $max / $size[1];
	}
	else
	{
		$min = $MIN_SIZE;
		$new_width = $size[0] * $max / $size[0];
		$new_height = $size[1] * $min / $size[1];
	}

	$new_width = floor($new_width);
	$new_height = floor($new_height);
    $dest = imagecreatetruecolor($new_width, $new_height);
    imagecopyresized(
        $dest,
        $originImg,
        0,
        0,
        0,
        0,
        $new_width,
        $new_height,
        $size[0],
        $size[1]
    );

	$ratio = $size[1] / $size[0];
	echo "$ratio {$size[0]} {$size[1]} $min , $max, $new_width, $new_height \n<br/>";
	$size[0] = $new_width;
	$size[1] = $new_height; 
	$originImg = $dest;
}
$layer = imagecreatetruecolor($size[0], $size[1]);
$bg = imagecolorallocate($layer, 0, 0, 0);
$col_ellipse = imagecolorallocate($layer, 255, 255, 255);
$circleSize = $min - $min * 0.05;
imagefilledellipse($layer, $size[0]/2, $size[1] / 2, $circleSize , $circleSize , $col_ellipse);


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

//if($_POST['square'])
//{
//	$min_half = $min / 2;
//
//	$width_half = $size[0] / 2;
//	$height_half = $size[1] / 2;
//	$originImg = mycrop($originImg, ['x' => $width_half - $min_half,
//										'y' => $height_half - $min_half,
//										'width' => $min,
//										'height' => $min]);
//}

//header("Content-type: image/png");
$time = time();
$filename = "imgs/con_".$time.".".$ext;
$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename); 
$filename = $withoutExt.".png";
$ret = imagepng($originImg,$filename);


include 'config.php';
include './tmhOAuth/tmhOAuth.php';
session_start();
session_unset();
$_SESSION['filename'] = $filename;
if(array_key_exists('oauth', $_SESSION))
{
	$url = "twit.php";
}
else
{
	$tmhOAuth = new tmhOAuth(array( 'consumer_key' => $CONSUMER_KEY, 'consumer_secret' => $CONSUMER_SECRET )); 
	$code = $tmhOAuth->request( 'POST', $tmhOAuth->url('oauth/request_token', ''), [ 'oauth_callback' => $OAUTH_CALLBACK ]);
	if ($code == 200) {
		$_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
		$url = $tmhOAuth->url("oauth/authorize", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}";
	} else {
		$url = '';
	} 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
<form enctype='multipart/form-data' action='twit_proxy.php' method='POST'>
<?php
if($url != 'twit.php') 
echo "<input type='hidden' name='url' value='$url' /> ";
?>
<textarea name ='inpTwit' rows="5" cols="80"> </textarea>
<br/>
<br/>
<br/>
<input type="submit" value="트윗" /> 
</form> 


<?php
	echo "<img src='$filename'></img>";
?>
	
</body>
</html>
