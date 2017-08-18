<script>

    $('.agree_check').on('click', function(e) {

        var check1 = $('.check1_Y').is(':checked');
        var check2 = $('.check2_Y').is(':checked');

        if ( check1 == false) {
            alert("이용약관을 동의해 주세요.");
            return false;
        }
        if ( check2 == false) {
            alert("개인정보 수집 및이용을 동의해 주세요.");
            return false;
        }
        
        $(location).attr('href','<?php echo $this->link->get(array('action'=>'join_form'), TRUE); ?>');
    });


</script>