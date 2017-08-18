<script>
	if($('.btn_attach_del').length > 0 && $._data($('.btn_attach_del')[0], 'events') === undefined)
	{
		$('.btn_attach_del').on('click', function(e) {
			e.preventDefault();
			if(confirm('삭제 하시겠습니까?'))
			{
				var $this = $(this);
				$.post(
					'<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'delete')); ?>', {
						'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
						'id' : $(this).attr('rel')
					},
					function(msg) {
						if(msg == 'ok')
						{
							$this.closest('li').remove();
						}
						else
						{
							alert(msg);
						}
					}
				);
			}
		});
	}
</script>