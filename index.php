<?php




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
<style>

input[type="file"]
{
	background-color:#aaa;
	height:200px;
}
input[type="submit"]
{
	background-color:#aaa;
	height:200px;
	width:200px;
}
</style>
<h1>안드로이드는 크롬에서 쓰세여</h1>	
<h1>서버에 저장된 파일은 한시간 단위로 지울 예정이니 직접 링크는 삼가해주세여</h1>	
<form enctype="multipart/form-data" action="processing.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" /> 
<label><input style="margin-bottom:50px;" checked=true type="checkbox" name="square" /> 사각형으로 크롭 </label>
<br/>
<input style="height:200px;" type="file" name="file" accept="image/*" /> 
<br/>
<br/>
<br/>
<input type="submit" value="파일 전송" /> 
</form> 

<img id = 'imgResult' src=''></img>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
</script>

</body>
</html>
