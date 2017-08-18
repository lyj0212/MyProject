<script type="text/javascript">
$(function() {
	$('.select_result').on('change', function() {
		var this_ = this;
		var param = {};
		param[token_name] = token_hash;
		param['result'] = $(this_).val();

		$(this_).parent().find('.bbs_status_process').show();
		$.post('<?php echo $this->link->get(array('action'=>'change_result', 'id'=>'\' + $(this_).data(\'id\') + \'')); ?>', param, function(bool) {
			if(bool == 'ok')
			{
				$(this_).parent().find('.bbs_status_process').hide();
				$(this_).parent().find('.bbs_status_save').show(300).delay(1000).hide(300);
			}
		});
	});
	
    $('.excelBtn').on('click', function() {
        location.replace("<?php echo $this->link->get(array('action'=>'excel')); ?>");
    });

});
</script>
