<script>
$('.sortablemenu').each(function() {
	$(this).find('ul:eq(0)').addClass('lined');
	$(this).on('mouseover', 'li', function(e) {
		$(e.currentTarget).find('.side:first').show();
	}).on('mouseout', 'li', function(e) {
		$(e.currentTarget).find('.side:first').hide();
	});
});
</script>
