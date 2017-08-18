<script>

<!------ 사업자등록번호 중복 스크립트 ------->
$('.company_Numcheck').on('click', function(e) {
    e.preventDefault();
    if ( $('._comp_num1').val()=="" || $('._comp_num2').val()=="" || $('._comp_num3').val()=="" ) {
        alert("사업자번호를 입력해주세요.");
        $('input[name="comp_num1"]').focus();
        $('.company_Numcheck').val('N');
        
    } else {
        $.post('<?php echo $this->link->get(array('action'=>'companyNum_check'), TRUE); ?>', {comp_num: $('._comp_num1').val() + '-' + $('._comp_num2').val() + '-' + $('._comp_num3').val()}, function(bool) {
            if(bool != 'true') {
                alert(bool);
                $('._comp_num1').val('');
                $('._comp_num2').val('');
                $('._comp_num3').val('');
                $('.company_Numcheck').val('N');
            } else {
                alert('등록 가능합니다.');
                $('.company_Numcheck').val('Y');
            }
        });
    }
});

<!------ 사업체 구분 스크립트 -------> 
$(document).ready(function() {
    $("#comp_status2").chained("#comp_status1");
    $("#comp_status3").chained("#comp_status2");
});

$(".infoCorp_submit").click(function() {

    /*
    if ( $('input[name="compname"]').val() == '' ) {
        alert("회사명은 필수 입력사항입니다.");
        $('input[name="compname"]').focus();
        return false;
    }
    
    if ( $('input[name="comp_num1"]').val() == '' || $('input[name="comp_num2"]').val() == '' || $('input[name="comp_num3"]').val() == '' ) {
        alert("사업자증록번호는 필수 입력사항입니다.");
        $('input[name="comp_num1"]').focus();
        return false;
    }
    
    if ( $('input[name="found_day"]').val() == '' ) {
        alert("설립일자는 필수 입력사항입니다.");
        $('input[name="found_day"]').focus();
        return false;
    }
    
    if ( $(".comp_status1 option:selected").val() == '' ) {
        alert("사업체구분은 필수 선택사항입니다.");
        $('select[name="comp_status1"]').focus();
        return false;
    }
    
    if ( $('input[name="listed_check"]').is(':checked') != true ) {
        alert("기업상장여부는 필수 선택사항입니다.");
        $('input[name="listed_check"]').focus();
        return false;
    }
    
    if ( $('input[name="ceo_name"]').val() == '' ) {
        alert("대표자명은 필수 입력사항입니다.");
        $('input[name="ceo_name"]').focus();
        return false;
    }
    
    if ( $('input[name="comp_email"]').val() == '' ) {
        alert("이메일은 필수 입력사항입니다.");
        $('input[name="comp_email"]').focus();
        return false;
    }
    
    if ( $('input[name="address1"]').val() == '' ) {
        alert("사업장 주소는 필수 입력사항입니다.");
        $('input[name="address1"]').focus();
        return false;
    }
    
    if ( $('input[name="address2"]').val() == '' ) {
        alert("사업장 상세주소는 필수 입력사항입니다.");
        $('input[name="address2"]').focus();
        return false;
    }
    
    if ( $('input[name="phone1"]').val() == '' || $('input[name="phone2"]').val() == '' || $('input[name="phone3"]').val() == '' ) {
        alert("대표전화는 필수 입력사항입니다.");
        $('input[name="phone1"]').focus();
        return false;
    }
    
    if ( $('input[name="fax1"]').val() == '' || $('input[name="fax2"]').val() == '' || $('input[name="fax3"]').val() == '' ) {
        alert("대표전화는 필수 입력사항입니다.");
        $('input[name="fax1"]').focus();
        return false;
    }
    
    if ( $('input[name="business_status"]').val() == '' ) {
        alert("주업태는 필수 입력사항입니다.");
        $('input[name="business_status"]').focus();
        return false;
    }
    
    if ( $('input[name="business_type"]').val() == '' ) {
        alert("주업종은 필수 입력사항입니다.");
        $('input[name="business_type"]').focus();
        return false;
    }
    */
   
    if ( $('.company_Numcheck').val() != 'Y' ) {
        alert("사업자등록번호 중복체크를 해주세요.");
        return false;
    }

    $("form[name='infoCorp']").submit();
    alert('저장되었습니다.');
});


</script>