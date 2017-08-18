<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>이메일 인증 &gt; LOGIN</title>
<link rel="stylesheet" type="text/css" href="/_res/libs/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/_res/libs/bootstrap/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="/_res/libs/jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/theme.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/iSmartCode.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/project.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/font_notosans.css" />
<link rel="stylesheet" type="text/css" href="/_res/font-awesome/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/_res/fontello/css/fontello.css" />
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" />
<!--<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato:400,700,900" />-->
<link rel="stylesheet" type="text/css" href="/_res/css/docs_animate.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/_res/libs/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/_res/js/jquery-ui.custom.js"></script>
<script type="text/javascript" src="/_res/js/modernizr-2.6.2.min.js"></script>
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- //기본 -->
<link rel="stylesheet" type="text/css" href="/_res/css/cms_layout.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/docs_cmpt.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/cms_cmpt.css" />
<link rel="stylesheet" type="text/css" href="/_res/css/cms_emailCertify.css" />
</head>
<body class="emailCertify">
<div class="skip">
	<a class="sr-only" href="#contents">본문컨텐츠 바로가기</a>
</div>

<div id="wrap">
	<div id="body" class="container">
		<section id="content">
			<div class="emailCertify_wrap">
				<h1><a href="#">SUGARAIN</a></h1>
				<div class="emailCertify_joincomplete">
					<p class="heading">
						<strong><span>안녕하세요.</span><?php echo $data['name']?>님 <em>이메일 인증</em>이 되지 않았습니다.</strong>
						<span>대전정보문화산업진흥원 SW융합클러스터 대덕센터에 가입해 주셔서 감사드립니다.</span>
						<span>개인정보(이메일) 무단 사용을 방지하기 위한 이메일 내용을 확인바랍니다.</span>
					</p>
					<div class="join_email">
						<span class="emailCertify_mark animated flipInX">!</span>
						<p class="txtp">가입 이메일 : <?php echo $data['userid']?></p>
						<p class="heading">
						    <strong>가입하신 이메일로 메일을 <em>전송</em>했습니다.</strong>
                        </p>
						<div class="btn_wrap">
							<a href="/"><button type="button" class="btn btn-primary">메인 이동</button></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<footer role="contentinfo" class="footer">
		<div class="container">
			<p class="text-muted">Copyright &copy; DICA. All Rights Reserved.</p>
		</div>
	</footer>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://social.plani.co.kr/_res/libs/bootstrap/js/bootstrap.js"></script>

</body>
</html>