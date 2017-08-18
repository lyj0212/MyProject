<script>
	$(function() {
		var uploader<?php echo $sequence; ?> = $('.uploader<?php echo $sequence; ?>').pluploadQueue({
			url : '<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'upload')); ?>',
			browse_button : '<?php echo $target_button; ?>',
			container : 'editor-attach-parent<?php echo $editor_sequence; ?>',
			runtimes : 'html5,flash,silverlight,html4',
			max_file_count : <?php echo $limit; ?>,
			now_file_count : <?php echo $limit-count($files); ?>,
			multi_selection : <?php if($limit == 1) : ?>false<?php else : ?>true<?php endif; ?>,
			multiple_queues : true,
			dragdrop: true,
			filters : {
				max_file_size : '<?php echo $max_upload_size; ?>'
			},
			flash_swf_url : '/modules/attachment/js/plupload/js/Moxie.swf',
			silverlight_xap_url : '/modules/attachment/js/plupload/js/Moxie.xap',
			file_data_name : 'Filedata',
			multipart : true,
			multipart_params : {
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
				'token' : '<?php echo $token; ?>',
				'timestamp' : '<?php echo $timestamp; ?>',
				'pid' : '<?php echo $pid; ?>',
				'target' : '<?php echo $target; ?>'
			},
		 editor_mode : true
		}).pluploadQueue();

		$('.attacharea').tooltip({selector : '.progressContainer'});
		$('.attacharea').on('mouseenter', '.progressContainer', function() {
			$(this).find('.caption').css('opacity', 0.65).stop().animate({'margin-top' : '-28px'}, 150);
		}).on('mouseleave', '.progressContainer', function() {
			$(this).find('.caption').stop().animate({'margin-top' : 0}, 150);
		});

		$('.attacharea').on('click', '.btn_attach_del', function(e) {
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
							$this.closest('.uploadify-queue-item').hide('fast', function() {
								$(this).hide();
								if( ! $(this).parent().find('.uploadify-queue-item:visible').length)
								{
									$(this).closest('.attachcontainer').hide();
								}

								if( ! $('.attacharea').find('.attachcontainer:visible').length)
								{
									$('.attacharea').hide();
								}

								<?php if( ! empty($limit)) : ?>
								var now_uploaded = $('.attacharea').find('.uploadify-queue-item:visible').length;
								uploader<?php echo $sequence; ?>.setSettings('now_file_count', <?php echo $limit; ?>-now_uploaded);
								if(<?php echo $limit; ?>-now_uploaded > 0)
								{
									$('.uploader<?php echo $sequence; ?>').show();
								}
								<?php endif; ?>
								Resize_Box();
							});
						}
						else
						{
							alert(msg);
						}
					}
				);
			}
			return false;
		});

		$(document).on('click', '.btn_attach_ins', function(e) {
			e.preventDefault();
			var type = $(this).data('type'), url = $(this).data('url'), orig_name = $(this).data('orig_name'), download_link = $(this).data('download_link'), code;

			if (type == 'image')
			{
				code = '<img src="' + url + '" alt="" class="editor_image" />\n';
			}
			else if(type == 'media')
			{
				code = '';
			}

			if( ! code)
			{
				code = '<a href="' + download_link + '" data-pjax="false">' + orig_name + '</a>';
			}

			Editor.getCanvas().pasteContent(code);
		});

		<?php if( ! empty($limit) AND $limit-count($files) <= 0) : ?>
		$('.uploader<?php echo $sequence; ?>').hide();
		<?php endif; ?>
	});
</script>