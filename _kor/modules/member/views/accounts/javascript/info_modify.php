<script>

$(".modify_submit").click(function() {
    
    var reg_pwd = /^.*(?=.{6,20})(?=.*[0-9])(?=.*[a-zA-Z]).*$/;

    if ( $('input[name="passwd"]').val() == '' || $('input[name="passwd_ok"]').val() == '') {
        alert('비밀번호를 입력해 주세요.');
        $('input[name="passwd"]').focus();
        return false;
    }
    
    if(!reg_pwd.test($('input[name="passwd"]').val())) {
        alert('비밀번호를 확인하세요.(영문, 숫자를 혼합하여 6~20자 이내)');
        $('input[name="passwd"]').focus();
        $('input[name="passwd_ok"]').val('');
        return false;
    }
    
    if ( $('input[name="passwd"]').val() != $('input[name="passwd_ok"]').val()) {
        alert("비밀번호가 일치하지 않습니다.");
        $('input[name="passwd_ok"]').focus();
        return false;
    }

    $("form[name='modify']").submit();

});

</script>