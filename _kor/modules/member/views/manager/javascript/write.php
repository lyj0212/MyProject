<script>
$('.dynamic-inline').parent().on('click', '.add', function() {
	var cloned = $(this).closest('.dynamic-inline').clone();
	cloned.filter(':not(.ltie7)').addClass('ltie7');
	cloned.find('input, select').each(function() {
		$(this).val($(this).attr('defaultValue'));
		$(this).attr('ident', '');
	});
	$(this).closest('.dynamic-inline').after(cloned);
}).on('click', '.del', function() {
	if($(this).parent().parent().parent().find('.del').length <= 1)
	{
		alert('더 이상 삭제할 수 없습니다.');
		return false;
	}
	if(confirm('삭제 하시겠습니까?'))
	{
		$(this).closest('.dynamic-inline').remove();
	}
});

$('#writeForm').on('submit', function() {
	var position = $('select[name="dynamic_member_position[]"]:last option:selected').attr('rel');
	$('input[name="position"]').val(position);
});

$('.zipcode').colorbox({
	iframe : true,
	width:'95%',
	maxWidth:600,
	initialWidth : 0,
	initialHeight : 0,
	height : 0,
	scrolling : false,
	returnFocus : false
});

$(document).on('click', '.category', function() {
	$.colorbox({
		href : $(this).attr('href'),
		iframe : true,
		width:'95%',
		maxWidth:800,
		initialWidth : 0,
		initialHeight : 0,
		height : 0,
		scrolling : false,
		returnFocus : false,
		onClosed : function() {
			location.reload();
		}
	});

	return false;
});

//아이디(이메일) 중복 스크립트
$('.userid_check').on('click', function(e) {
    e.preventDefault();
    if ( $('input[name="userid"]').val()=="") {
        alert("이메일을 입력해 주세요.");
        $('input[name="userid"]').focus();
        
    } else {
        $.post('<?php echo $this->link->get(array('action'=>'userid_Check'), TRUE); ?>', {id: $('input[name="userid"]').val()}, function(bool) {
            if(bool != 'true') {
                alert(bool);
                $('input[name="userid"]').val('');
                $('input[name="userid"]').focus();
            } else {
                alert('사용 가능합니다.');
            }
        });
    }
});

</script>
