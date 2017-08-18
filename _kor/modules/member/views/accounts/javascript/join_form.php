<script>

<!------ 아이디(이메일) 중복 스크립트 ------->
$('.userid_check').on('click', function(e) {
    
    e.preventDefault();
    var regEmail = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    
    if ( $('input[name="userid"]').val()=="") {
        alert("이메일을 입력해 주세요.");
        $('input[name="userid"]').focus();
        $('.userid_check').val('N');
        
    } else {
        
        if(!regEmail.test($('input[name="userid"]').val())) {
            alert('이메일 주소가 유효하지 않습니다');
            u_email.focus();
            return false;
        }
        $.post('<?php echo $this->link->get(array('action'=>'userid_Check'), TRUE); ?>', {id: $('input[name="userid"]').val(),csrf_token_name:token_hash}, function(bool) {
            if(bool != 'true') {
                alert(bool);
                $('input[name="userid"]').val('');
                $('input[name="userid"]').focus();
                $('.userid_check').val('N');
            } else {
                alert('사용 가능합니다.');
                $('.userid_check').val('Y');
            }
        });
    }
});

$(".joinForm_submit").click(function() {
    
    var reg_pwd = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;
    
    if ( $('.userid_check').val() != 'Y' ) {
        alert("이메일 중복체크를 해주세요.");
        return false;
    }
    
    if ( $('input[name="passwd"]').val() == '' ) {
        alert("비밀번호를 입력해주세요.");
        return false;
    }
    
    if ( $('input[name="passwd_ok"]').val() == '' ) {
        alert("비밀번호확인을 입력해주세요.");
        return false;
    }
    
    if ( $('input[name="passwd"]').val() != $('input[name="passwd_ok"]').val()) {
        alert("비밀번호가 일치하지 않습니다.");
        $('input[name="passwd_ok"]').focus();
        return false;
    }
    
    if ( $('input[name="name"]').val() == '' ) {
        alert("이름을 입력해주세요.");
        return false;
    }
    
    if(!reg_pwd.test($('input[name="passwd"]').val())) {
        alert('비밀번호를 확인하세요.(영문, 숫자를 혼합하여 6~20자 이내)');
        $('input[name="passwd"]').focus();
        $('input[name="passwd_ok"]').val('');
        return false;
    }
    
    $("form[name='join_save']").submit();
});



</script>