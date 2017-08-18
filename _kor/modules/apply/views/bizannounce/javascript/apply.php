<script type="text/javascript">
$(function() {
	<!------ 사업체 구분 스크립트 -------> 
	$("#comp_status2").chained("#comp_status1");
	$("#comp_status3").chained("#comp_status2");
	<!------ 사업체 구분 스크립트 끝 ------->
	
	<!------ 중복 체크 스크립트 ------->
	$(document).on('click', '._check', function(e) {
		e.preventDefault();
		var target = $(this).data('target');
		var target_text = $('label[for="'+target+'1"]').text();
		var value;
		
		if($("input[name='" + target + "1']").val() == "" || $("input[name='" + target + "2']").val() == "" || $("input[name='" + target + "3']").val() == "") 
		{
				alert(target_text + "를 입력해주세요.");
				$("input[name='" + target + "1']").focus();
		}
		else
		{
			value = $("input[name='" + target + "1']").val() + "-" + $("input[name='" + target + "2']").val() + "-" + $("input[name='" + target + "3']").val();
			$.post('<?php echo $this->link->get(array('action'=>'dupliacate_check'), TRUE); ?>', {field:target, value: value}, function(bool) {
				if(bool != 'true') {
					alert(bool);
					 $("input[name='" + target + "1']").val(''); 
					 $("input[name='" + target + "2']").val('');
					 $("input[name='" + target + "3']").val('');
				} else {
					$("input[name='" + target + "_check']").val('1');
					alert('등록 가능합니다.');
				}
			});
		}
	});
	
	$(document).on('click', '._add', function(e) {
		e.preventDefault();
		var dynamicZone = $(this).closest('.biz_papers_wrap');
		var sameZone = $('[data-area="' + dynamicZone.data('area') + '"]');
		var cloneEl = dynamicZone.clone();
		cloneEl.find('input').each(function() {
			$(this).val($(this).attr('defaultValue'));
		});
		cloneEl.find('.biz_papers_lst ').remove();
		cloneEl.find("input[type=file]").change(function(){
			$(this).parents(".fileForm").find(".iptfile_wrap").find("input[type=text]").val($(this).val().replace(/^c:\\fakepath\\/i,''));
		});
		dynamicZone.after(cloneEl);
		
	}).on('click', '._del', function(e) {
		e.preventDefault();
		var dynamicZone = $(this).closest('.biz_papers_wrap');
		var sameZone = $('[data-area="' + dynamicZone.data('area') + '"]');
		if(sameZone.find('._del').length <= 1)
		{
			alert('더 이상 삭제할 수 없습니다.');
			return false;
		}
		if(confirm('삭제 하시겠습니까?'))
		{
			dynamicZone.remove();
		}
	});
});
</script>
