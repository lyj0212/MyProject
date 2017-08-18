<script>
$('._checkbox_all').on('click', function() {
	if($(this).prop('checked') == true)
	{
		$('._checkbox').prop('checked', true);
	}
	else
	{
		$('._checkbox').prop('checked', false);
	}
});

$('.delete_checked').on('click', function() {
	if( ! $('._checkbox:checked').length)
	{
		alert('삭제할 파일을 선택해 주세요.');
		return false;
	}

	if(confirm('선택한 파일을 삭제 하시겠습니까?'))
	{
		var checked_id = $('._checkbox:checked').map(function() { return $(this).val(); }).get().join('|');
		location.href = '<?php echo $this->link->get(array('action'=>'delete_checked', 'aid'=>'\' + checked_id + \'')); ?>';
	}

	return false;
});
</script>
