<script type="text/javascript">
$(function() {
	$('#category').on('change',function(){
		var category = $(this).val();
		var ismember = $(this).data('ismember');
		var apply_id = '<?php echo $this->link->get_segment('id'); ?>';
		
		if(category){
			$.ajax({
				type: 'post',
				url: '<?php echo $this->link->get(array('action'=>'get_my_json_data'), TRUE); ?>',
				data: {
					category:category,
					ismember:ismember,
					csrf_token_name:token_hash,
				},
				dataType: 'json',
				success: function (json) {
					var options = '';
					options += '<option value="">-- 사업공고를 선택해 주세요 --</option>';
					for (var i = 0; i < json.length; i++) {
						options += '<option value="' + json[i].id + '"' + 'data-apply="'+ json[i].apply_id + '"' + (json[i].apply_id == apply_id ? 'selected=\'selected\'' : '') +'>' +'[' + json[i].new_state + '] ' + json[i].subject + '</option>';
					}
					$("select#subject").html(options);
				}
			}); 
		}else{
			$('select#subject').html('<option value="">-- 사업공고를 선택해 주세요 --</option>'); 
		}
	});
	
	$('#subject').on('change',function(){
		var apply_id = $(this).find(':selected').attr('data-apply');
		location.replace('/webAdmin/apply/manager/view/id/' + apply_id);
	});
});
</script>