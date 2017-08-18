<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>DCDCOOP 대전충청디자인기업협동조합</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="author" content="&copy; plani 042-934-3508" />
<meta name="language" content="ko" />
<link rel="stylesheet" href="/css/korean/basic.css">
<style>
p { line-height:1; }
</style>
</head>

<body>
<?php echo $data['contents']; ?>

<div class="txt_right" style="position:fixed; bottom:0; width:100%; padding:10px 0; color:#FFF; background-color:#000;">
	<div style="margin-right:10px">
		오늘 하루 띄우지 않기 <input type="checkbox" name="popup<?php echo $data['id']; ?>" class="close" value="1" />
	</div>
</div>
</body>
</html>