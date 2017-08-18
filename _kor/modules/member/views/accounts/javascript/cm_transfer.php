<script>

    $('.transfer_send').on('click', function(e) {

        var check1 = $('.transfer_chk').is(':checked');

        if ( check1 == false) {
            alert("이용약관, 개인정보 이용지침에 동의해 주시기 바랍니다.");
            return false;
        }
        
        $("form[name='transfer']").submit();
        alert('정보이관이 완료되었습니다.');
    });


</script>