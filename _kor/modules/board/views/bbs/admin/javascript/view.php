<script>
	$('.select_status').on('change', function() {
		var this_ = this;
		var param = {};
		param[token_name] = token_hash;
		param['status'] = $(this_).val();

		$(this_).parent().find('.bbs_status_process').show();
		$.post('<?php echo $this->link->get(array('action'=>'change_status')); ?>', param, function(bool) {
			if(bool == 'ok')
			{
				$(this_).parent().find('.bbs_status_process').hide();
				$(this_).parent().find('.bbs_status_save').show(300).delay(1000).hide(300);
			}
		});
	});
</script>