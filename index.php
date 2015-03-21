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
<h1>안드로이드는 크롬에서 쓰세요(기본 브라우저는 킷캣에서 버그 있다고 함)</h1>	
<h1>서버에 저장된 파일은 한시간 단위로 지우고 있으니 보관용은 트위터에 올리세요</h1>	
<form enctype="multipart/form-data" action="processing.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" /> 
<label><input style="margin-bottom:50px;" checked=true type="checkbox" name="square" /> 사각형으로 크롭 </label>
<br/>
<label><input style="margin-bottom:50px;" checked=true type="checkbox" name="crop_min" /> 800 크기로 리사이즈  -> 이미지 큰거 그냥 올리면 서버 터짐</label>
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
