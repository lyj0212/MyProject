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
<meta name="author" content="SDIdeaUp 현대SDI" />
<meta name="Keywords" content="SDIdeaUp, 현대SDI, Special Different Idea" />
<meta name="description" content="빛나는 당신의 삶을 위해 현대SDI Idea를 Up하다" />
<meta name="copyright" content="(48547) 부산광역시 남구 신선로 365, 10공학관 105호 / Email mabu007@nate.com / TEL 070-4808-2680(010-8747-5580)" />
<title>이메일인증</title>
</head>
<body>

<div style="position:relative;width:730px;margin:50px auto 0;font-size:13px;line-height:22px;font-family:'맑은 고딕','Malgun Gothic';color:#666">
    <div style="position:relative;width:100%;margin-bottom:50px;border-bottom:1px solid #dcdee1">
        <h1 style="padding:0;margin:0 0 20px"><!--<img src="<?php /*echo site_url('/images/contents/sub08/h1_logo_main.jpg'); */?>" alt="SDIdeaUp 현대SDI">--></h1>
    </div>
    <div style="position:relative;width:100%;text-align:center">
        <div style="margin-bottom:25px"><!--<img src="<?php /*echo site_url('/images/contents/sub08/mail_bg.jpg'); */?>" alt="">--></div>
        <div style="display:block;position:relative;letter-spacing:-0.04em">
            <p style="padding:0;margin:0">
                <span style="display:block">안녕하세요</span>
                <strong style="display:block;font-weight:normal;font-size:26px;line-height:34px;color:#333;letter-spacing:-0.08em"><em style="font-style:normal;font-weight:bold;color:#249b7e">SDIdeaUp 현대SDI</em>입니다.</strong>
            </p>
            <p style="padding:25px 0 20px;margin:0">
                <span style="display:block"><em style="font-style:normal;color:#000;text-decoration:underline"><?php echo $data['userid']; ?></em> / <?php echo $data['name']; ?>으로 임시비밀번호가 발송되었습니다.<br/>아래 "임시번호"로 로그인이 가능하며 로그인 후 비밀번호 변경을 꼭 해주시기바랍니다.</span>
            </p>
            <p style="padding:0;margin:0">
                <strong style="display:block;font-weight:normal;font-size:26px;line-height:34px;color:#333;letter-spacing:-0.08em">임시비밀번호 : <em style="font-style:normal;font-weight:bold;color:#249b7e"><?php echo $data['passwd'] ?></em></strong>
            </p>
            <br/>
            <div style="position:relative;padding:25px 26px 28px;border:1px solid #e9e9e9;background:#f9f9f9;letter-spacing:-0.05em">
                <p style="padding:0;margin:0"><strong>혹시 가입한적이 없는데 메일을 받으셨나요?^^</strong></p>
                <p style="padding:0;margin:0">다른 사용자가 회원가입시 이메일 주소를 잘못 사용한 경우입니다.<br/>인증을 하지 않으면 사용이 불가능하므로 신경쓰지 않으셔도됩니다.</p>
            </div>
        </div>
    </div>
    <div style="position:relative;width:100%;margin-top:25px;border-top:1px solid #dcdee1;text-align:center">
        <p style="margin:15px 0">&copy; 2017 HYUNDAI IDEAUP Co., Ltd. <span>All Rights Reserved</span></p>
    </div>
</div>
</body>
</html>
