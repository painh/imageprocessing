<?php
include_once("config.php");
include './tmhOAuth/tmhOAuth.php';
session_start();
$tmhOAuth = new tmhOAuth(array( 'consumer_key' => $CONSUMER_KEY, 'consumer_secret' => $CONSUMER_SECRET ));
$tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
$tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

$code = $tmhOAuth->request( 'POST', $tmhOAuth->url('oauth/access_token', ''), array( 'oauth_verifier' => $_REQUEST['oauth_verifier']));

if ($code == 200) 
{
	$_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
	unset($_SESSION['oauth']);
} 
else 
{
echo "error";
exit;
//    print_r($tmhOAuth);
}

$tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
$tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

$image = $_SESSION['filename'];
$twit = $_SESSION['twit'];

$code = $tmhOAuth->request(
'POST',
'https://api.twitter.com/1.1/statuses/update_with_media.json',
array(
'media[]'  => "@{$image};type=image/jpeg;filename={$image}",
'status'   => $twit,
),
true, // use auth
true  // multipart
);

//print_r($code);
//print_r($tmhOAuth->response['response']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
<?php
	if($code == 200)
		echo "성공";
	else if($code == 413)
		echo "파일 크기가 너무 큼";
	else
		echo "실패 :  $code";
?>
	<a href='.'>
	<div style="height:100px">
	goto index 
	</div>
	</a>	
</body>
</html>
