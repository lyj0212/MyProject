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


</script>