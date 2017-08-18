<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="content-language" content="kr" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no,email=no,address=no" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scaleable=yes" />
<meta name="author" content="D-CUBE(D3) 클러스터 플랫폼" />
<meta name="Keywords" content="산업혁신클러스터, 대덕SW융합클러스터, D-CUBE(D3) 클러스터 플랫폼" />
<meta name="description" content="대덕연구단지와 대덕테크노밸리를 잇는 산업 혁신 클러스터" />
<meta name="copyright" content="(34126) 대전광역시 유성구 대덕대로 512번길 / TEL_042-479-41149" />
<title>이메일 인증</title>
</head>
<body>

<div style="position:relative;width:730px;margin:50px auto 0;font-size:13px;line-height:22px;font-family:'맑은 고딕','Malgun Gothic';color:#666">
	<div style="position:relative;width:100%;margin-bottom:50px;border-bottom:1px solid #dcdee1">
		<h1 style="padding:0;margin:0 0 20px"><img src="<?php echo site_url('/images/contents/sub08/h1_logo_main.jpg'); ?>" alt="D-CUBE(D3) 클러스터 플랫폼"></h1>
	</div>
	<div style="position:relative;width:100%;text-align:center">
		<div style="margin-bottom:25px"><img src="<?php echo site_url('/images/contents/sub08/mail_bg.jpg'); ?>" alt=""></div>
		<div style="display:block;position:relative;letter-spacing:-0.04em">
			<p style="padding:0;margin:0">
				<span style="display:block">안녕하세요</span>
				<strong style="display:block;font-weight:normal;font-size:26px;line-height:34px;color:#333;letter-spacing:-0.08em">대전정보문화산업진흥원 <br/><em style="font-style:normal;font-weight:bold;color:#249b7e">SW융합클러스터사업단 대덕센터</em>입니다.</strong>
			</p>
			<p style="padding:25px 0 20px;margin:0">
				<span style="display:block"><em style="font-style:normal;color:#000;text-decoration:underline"><?php echo $email; ?></em> / <?php echo $name; ?>으로 회원가입되었습니다. <br/>회원 가입 후 이메일 인증이 필요합니다. <br/>아래 "링크" 주소를 클릭하신 후에 센터 홈페이지로 이동하시면 모든 서비스 사용이 가능합니다. 감사합니다.</span>
			</p>
			<p style="padding:0 0 35px;margin:0">
				<span style="display:block"><span style="font-weight:bold">링크 :</span> <a style="display:inline-block;line-height:26px;color:#000" href="<?php echo site_url($this->link->get(array('prefix'=>'login', 'controller'=>'accounts', 'action'=>'accredit_check', 'email'=>encode_url($email), 'accredit'=>$accredit), TRUE)); ?>" target="_blank" title="새창에서 열림">메일인증하기</a></span>
			</p>

			<div style="position:relative;padding:25px 26px 28px;border:1px solid #e9e9e9;background:#f9f9f9;letter-spacing:-0.05em">
				<p style="padding:0;margin:0"><strong>혹시 가입한적이 없는데 메일을 받으셨나요?^^</strong></p>
				<p style="padding:0;margin:0">다른 사용자가 회원가입시 이메일 주소를 잘못 사용한 경우입니다.<br/>인증을 하지 않으면 사용이 불가능하므로 신경쓰지 않으셔도됩니다.</p>
			</div>
		</div>
	</div>
	<div style="position:relative;width:100%;margin-top:25px;border-top:1px solid #dcdee1;text-align:center">
		<p style="margin:15px 0">Copyright &copy; DICA. All Rights Reserved.</p>
	</div>
</div>
</body>
</html>
