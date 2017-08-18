<script>
$(function() {
	$('.disabled [rel="tooltip"]').click(function() {
		return false;
	}).tooltip({
		title : '기본설정 저장 후 활성화 됩니다.',
		container: 'body'
	});
});
</script>
