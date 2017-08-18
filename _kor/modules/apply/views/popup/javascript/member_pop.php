<script type="text/javascript">
$(function() {
	$(document).on("click", "._get", function(e) {
		
		parent.$("form")[0].reset();
		$.post('<?php echo $this->link->get(array('action'=>'get_member_data'), TRUE); ?>', {id: $(this).data('id'),csrf_token_name:token_hash}, function(result) {
			$.each(result, function(k, v) {
				var arr;
				parent.$("input[name='" + k + "']").val(v);
				
				if(k == 'userid') 
				{
					parent.$("input[name='chief_email']").val(v);
				}
				
				if(k =='comp_num' || k =='corporate_num' || k == 'venture_num') 
				{
					if(v != null && v.length > 0)
					{
						if(v.indexOf('-') > -1) 
						{
							console.log('b');
							arr = v.split('-');
							$.each(arr, function(k2, v2) {
								parent.$("input[name='" + k + (k2+1) +"']").val(v2);
							});
						}
						else
						{
							parent.$("input[name='" + k + "1']").val(v);
						}
					}
				}
				
				if(k == 'venture_ap' || k == 'listed_check' || k == 'attached_lab' || k == 'address_status')
				{
					parent.$("input:radio[name='" + k + "'][value='" + v + "']").prop("checked", true);
				}
				
				if(k == 'comp_status1' || k == 'comp_status2' || k == 'comp_status3')
				{
					parent.$("select[name='" + k + "'] option[value='" + v + "']").prop("selected", true).trigger('change');
				}
			});
		}, 'json');
		
		parent.$.colorbox.close();
	});
});
</script>