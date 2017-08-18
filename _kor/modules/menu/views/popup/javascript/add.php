<script>
$(document).on('change', 'select[name="target"]', function() {
	var $this = $(this);
	$this.next().remove();
	var child = $this.find('option:selected').attr('rel');
	if(child == 'true')
	{
		$.getJSON('<?php echo $this->link->get(array('action'=>'get_param', 'target'=>'\' + $this.val() + \'')); ?>', function(data) {
			if(data.failed)
			{
				alert('module.xml 파일이 잘못 되었습니다.');
				return false;
			}

			var items = [];
			if(data.group.length > 0)
			{
				$.each(data.group, function(key, val) {
					items.push('<option value="' + val['@attributes']['segment'] + '/' + val['@attributes']['value'] +'">' + val['@attributes']['title'] + '</option>');
				});
			}
			else
			{
				items.push('<option value="">항목이 존재하지 않습니다.</option>');
			}

			$('<select />', {
				'name': 'param',
				'class': 'form-control input-sm',
				html: items.join('\n'),
				val: '<?php echo $data['param']; ?>'
			}).insertAfter($this.parent()).before('\n').wrap('<div class="col-lg-3 row"></div>').end();
		});
	}
});

$(function() {
	$('select[name="target"]').trigger('change');
	$('input[name="title"]').focus();
});
</script>