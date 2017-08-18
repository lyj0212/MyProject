<script>
$('input.close').on('click', function() {
	$.cookie($(this).attr('name'), 1, {expires:1, path:'/'});
	self.close();
});
</script>