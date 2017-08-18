<script type="text/javascript">
$(function() {
	$('#category').on('change',function(){
		var category = $(this).val();
		if(category){
			$.ajax({
				type: 'post',
				//async: false,
				url: '<?php echo $this->link->get(array('action'=>'get_json_data'), TRUE); ?>',
				data: {
					category:category,
					csrf_token_name:token_hash,
					/*<?php //echo $this->security->get_csrf_token_name(); ?>:'<?php //echo $this->security->get_csrf_hash(); ?>',*/
				},
				dataType: 'json',
				success: function (json) {
					var options = '';
					options += '<option value="">-- 사업공고를 선택해 주세요 --</option>';
					for (var i = 0; i < json.length; i++) {
						options += '<option value="' + json[i].id + '">' +'[' + json[i].new_state + '] ' + json[i].subject + '</option>';
					}
					$("select#subject").html(options);
				}
			}); 
		}else{
			$('select#subject').html('<option value="">-- 사업공고를 선택해 주세요 --</option>'); 
		}
	});
	
	$(document).on('click', '._add', function(e) {
		e.preventDefault();
		var dynamicZone = $(this).closest('.form-group');
		var sameZone = $('[data-area="' + dynamicZone.data('area') + '"]');
		var cloneEl = dynamicZone.clone();
		cloneEl.find('input').each(function() {
			$(this).val($(this).attr('defaultValue'));
		});
		cloneEl.find('.attacharea ').remove();
		dynamicZone.after(cloneEl);
		
		//if(sameZone.find('._add').length >= 9)
		//{
		//	alert('더 이상 추가할 수 없습니다.');
		//	return false;
		//}
	}).on('click', '._del', function(e) {
		e.preventDefault();
		var dynamicZone = $(this).closest('.form-group');
		var sameZone = $('[data-area="' + dynamicZone.data('area') + '"]');
		if(sameZone.find('._del').length <= 1)
		{
			alert('더 이상 삭제할 수 없습니다.');
			return false;
		}
		if(confirm('삭제 하시겠습니까?'))
		{
			var delObj = dynamicZone.find('._d_id');
			if(delObj.val())
			{
				$(this).closest('form').prepend($('<input />').prop({
					type: 'hidden',
					name: delObj.attr('name').replace('_d[', '_d_delete[')
				}).val(delObj.val()));
			}
			dynamicZone.remove();
		}
	});
	
	<!------ 사업체 구분 스크립트 -------> 
    $("#comp_status2").chained("#comp_status1");
    $("#comp_status3").chained("#comp_status2");
	<!------ 사업체 구분 스크립트 끝 ------->
});
</script>
