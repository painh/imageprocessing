<?php
include_once 'crop_circle.php';

$uploaddir = 'imgs/';
$uploadfile = $uploaddir . time(). basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
//		error_log( "File is valid, and was successfully uploaded.");
} else {
		error_log( "Possible file upload attack!");
		error_log( 'Here is some more debugging info:');
		error_log(print_r($_FILES, true));
}

		//error_log(print_r($_FILES, true));


//$uploadfile = './imgs/14268685682015-03-20 15.12.22.jpg';
//$_POST['crop_min'] = 1;
//$_POST['square'] = false;
$size = getimagesize($uploadfile); 
$ext = strtolower( pathinfo($uploadfile, PATHINFO_EXTENSION) );

$time = time();
$filename = "imgs/con_".$time.".".$ext;
$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename); 
$filename = $withoutExt.".png";

circleCrop($uploadfile, $_POST['square'], $_POST['crop_min'] ? 800 : 0, $filename);


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
