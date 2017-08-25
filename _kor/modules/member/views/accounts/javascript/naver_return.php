<script type="text/javascript">
	var naver_id_login = new naver_id_login("4vwO3QhQHBYqdealrSiI", "http://sdi.localhost/order/order_login/accounts/naver_return");
	// 접근 토큰 값 출력
	//alert(naver_id_login.oauthParams.access_token);
	// 네이버 사용자 프로필 조회
	naver_id_login.get_naver_userprofile("naverSignInCallback()");
	// 네이버 사용자 프로필 조회 이후 프로필 정보를 처리할 callback function
	function naverSignInCallback() {
        $.post(
            '<?php echo $this->link->get(array('action'=>'sns_do_login')); ?>', {
                'sns' : 'naver',
                'snsid' : naver_id_login.getProfileData('id'),
                'name' : naver_id_login.getProfileData('name'),
                'email' : naver_id_login.getProfileData('email'),
                'picture' : naver_id_login.getProfileData('profile_image')
            },
            function(redirect) {
                location.replace(redirect);
            }
        );
	}

</script>

