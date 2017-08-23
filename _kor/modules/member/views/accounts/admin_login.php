<!DOCTYPE html>
<html lang="ko" class="login-bg">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->menu->current['title']; ?> &#124; 현대 SDI Admin</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="description" content="Y-Bridge" />
	<meta name="keywords" content="Y-Bridge" />
	<meta name="copyright" content="Copyright 2013 Plani all rights reserved." />
	<meta name="author" content="PLANI" />

	<link rel="stylesheet" href="/_res/libs/bootstrap/css/bootstrap.min.css?v=20130306">
	<link rel="stylesheet" href="/manager/css/style.css?v=20130130">
	<link rel="shortcut icon" href="/images/ico/favicon_cms.ico" />
	<!--[if lt IE 9]>
	<style>
		.login-wrapper .box {
			background-color:#FFF;
		}
	</style>
	<![endif]-->
</head>
<body>

<div class="container-fluid login-wrapper">
	<a class="brand" href="/<?php echo $this->menu->current['map']; ?>">현대 SDI ADMIN</a>

	<div class="box">
		<div class="content-wrap">
			<h6>Log in</h6>

			<?php echo validation_errors(); ?>
			<?php echo form_open($this->link->get(array('action'=>'do_login')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
			<input type="hidden" name="redirect" value="<?php echo set_value('redirect', $redirect); ?>" />
			<input type="hidden" name="action" value="<?php echo set_value('action', $action); ?>" />

			<input type="text" name="userid" class="form-control" placeholder="아이디" value="<?php echo set_value('userid', $userid);?>" />
			<input type="password" name="passwd" class="form-control" placeholder="비밀번호" />

			<div class="remember">
				<label class="checkbox-inline">
					<input type="checkbox" name="remember_id" value="1" <?php echo set_checkbox('remember_id', '1', $check_remember); ?> /> 아이디 기억
				</label>
			</div>
			<button type="submit" class="btn btn-primary btn-sm">로그인</button>
		</div>
	</div>

</div>

</body>
</html>