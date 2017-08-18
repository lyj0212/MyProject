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

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * 중요게시물 저장 처리
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$(document).on('click', '.highlight_mark, .highlight_unmark', function(e) {
		e.preventDefault();

		var type = ($(this).hasClass('highlight_mark')) ? 'mark' : 'unmark';
		$.ajax({
			type: 'post',
			url: highlight_url,
			data: token_name + '=' + token_hash + '&target=' + $(this).attr('rel') + '&type=' + type,
			success: function(msg) {
				if(msg == 'true')
				{
					location.reload();
				}
				else
				{
					alert(msg);
				}
			}
		});
	});
</script>