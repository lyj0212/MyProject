<script>

// 이메일 유효성 검사 없이 관리자(admin) 로그인 가능하게 처리
$('input[name="userid"], input[name="passwd"]').keyup(function(event) {
    
    if ( $('input[name="userid"]').val() == 'admin') {
        $('input[name="userid"]').attr("type", "text");
    }
});

$(".loginForm_submit").click(function() {

    if ( $('input[name="userid"]').val() == '' || $('input[name="passwd"]').val() == '') {
        $('.error_1').html('이메일(아이디) 또는 비밀번호가 일치하지 않습니다!');
        $('.error_2').html('이메일(아이디)과 비밀번호를 다시 입력해 주시기 바랍니다.');
        $('.loginfail_messg').addClass('active');
        return false;
    }

    $("form[name='login']").submit();

});

$('input[name="passwd"]').keyup(function(event) {
    
    if (event.keyCode == 13) {
        
        if ( $('input[name="userid"]').val() == '' || $('input[name="passwd"]').val() == '') {
            $('.error_1').html('이메일(아이디) 또는 비밀번호가 일치하지 않습니다!');
            $('.error_2').html('이메일(아이디)과 비밀번호를 다시 입력해 주시기 바랍니다.');
            $('.loginfail_messg').addClass('active');
            return false;
        }
        
        $("form[name='login']").submit();
        return;
    }
});

$(".findPwd_submit").click(function() {
    
    var regEmail = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

    if ( $('.pwd_find').val() == '') {
        
        alert('이메일(아이디) 입력해 주시기 바랍니다.');
        $('.pwd_find').focus();
        return false;
        
    } else {
        
        if(!regEmail.test($('.pwd_find').val())) {
            alert('이메일 주소가 유효하지 않습니다');
            $('.pwd_find').focus();
            return false;
        }
        
    }

    $("form[name='find']").submit();
    alert('임시비밀번호가 발송되었습니다.');

});

	// 페이스북 로그인
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '269937483492963',
			cookie     : true,
			xfbml      : true,
			version    : 'v2.8'
		});
	};

	$('#facebookBtn').on('click', function(e) {
		e.preventDefault();
		FB.login(function(response) {
			FB.api('/me', {fields: 'name,email'}, function(user) {
				$.post('<?php echo $this->link->get(array('action' => 'sns_do_login'), TRUE); ?>', {'sns' : 'facebook', 'snsid' : user.id, 'name' : user.name, 'email' : user.email, 'picture' : 'https://graph.facebook.com/' + user.id + '/picture?type=large', 'token' : response.authResponse.accessToken}, function (redirect) {
					location.replace(redirect);
				});
			});
		}, {scope: 'email,user_friends'});
	});

	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/ko_KR/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// 구글+ 로그인
	$('#googleBtn').on('click', function(e) {
		e.preventDefault();
		gapi.signin.render('googleBtn', {
			'callback': 'signinCallback',
			'clientid': '17666928282-t722grimiuqu77gqcnoaabalh5tn48pl.apps.googleusercontent.com',
			'cookiepolicy': 'single_host_origin',
			'requestvisibleactions': 'http://schemas.google.com/AddActivity',
			'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email'
		});
	});

	function signinCallback(authResult) {
		if (authResult['access_token']) {
			gapi.auth.setToken(authResult);
			gapi.client.load('oauth2', 'v2', function() {
				var request = gapi.client.oauth2.userinfo.get();
				request.execute(function (resp) {
					$.post('<?php echo $this->link->get(array('action' => 'sns_do_login'), TRUE); ?>', {'sns' : 'google', 'snsid' : resp['id'], 'name' : resp['name'], 'email' : resp['email'], 'picture' : resp['picture'], 'token' : authResult['id_token']}, 	function (redirect) {
						location.replace(redirect);
					});
				});
			});
		} else if (authResult['error']) {
			//   "access_denied" - 사용자가 앱에 대한 액세스 거부
			//   "immediate_failed" - 사용자가 자동으로 로그인할 수 없음
			//console.log(authResult);
		} else {
			alert('오류가 발생했습니다.');
		}
	}

	(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = '//apis.google.com/js/client:plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();

	// 인스타 로그인 크릭시
	$('#instaBtn').on('click', function(e) {
		e.preventDefault();
		//window.open('https://api.instagram.com/oauth/authorize/?client_id=ce9c521a17494d5b91e7af685c36492b&redirect_uri=http://sdi.localhost/order/order_login/accounts/insta_return&response_type=code&scope=likes+comments+relationships+basic', 'sel', 'width=450, height=400')
		location.href='https://api.instagram.com/oauth/authorize/?client_id=ce9c521a17494d5b91e7af685c36492b&redirect_uri=http://sdi.localhost/order/order_login/accounts/insta_return&response_type=code&scope=likes+comments+relationships+basic';
	});

	//카카오톡 로그인
	$('#kakaoBtn').on('click', function(e) {
		alert('s');
		Kakao.init('3c91a56849f213462ceec9b570ebbaa7');
		e.preventDefault();
		Kakao.Auth.login({
			success: function(authObj) {
				Kakao.API.request({
					url: '/v1/user/me',
					success: function(res) {
						$.post('<?php echo $this->link->get(array('action' => 'sns_do_login'), TRUE); ?>', {'sns' : 'kakao', 'snsid' : res.id, 'name' : res.properties.nickname, 'email' : res.kaccount_email, 'picture' : res.properties.thumbnail_image, 'token' : res.id}, 	function (redirect) {
							location.replace(redirect);
						});
					},
					fail: function(error) {
						alert(JSON.stringify(error));
					}
				});
			},
			fail: function(err) {
				alert(JSON.stringify(err));
			}
		});
	});

	//네이버 로그인
	/*$('#naverBtn').on('click', function(e) {

		var naver_id_login = new naver_id_login("4vwO3QhQHBYqdealrSiI", "http://sdi.localhost");
		var state = naver_id_login.getUniqState();
		naver_id_login.setButton("white", 2,40);
		naver_id_login.setDomain("YOUR_SERVICE_URL");
		naver_id_login.setState(state);
		naver_id_login.setPopup();
		naver_id_login.init_naver_id_login();

	}*/






</script>