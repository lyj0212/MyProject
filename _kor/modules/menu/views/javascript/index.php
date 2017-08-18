<script>
$('.sortablemenu').each(function() {
	$(this).find('ul:eq(0)').addClass('lined');
	$(this).on('mouseover', 'li', function(e) {
		$(e.currentTarget).find('.side:first').show();
	}).on('mouseout', 'li', function(e) {
		$(e.currentTarget).find('.side:first').hide();
	});

	$(this).on('click', '.area_toggle', function() {
		var $this = $(this);
		$(this).parent().parent().find('ul:first').slideToggle('fast', function() {
			if($(this).is(':visible'))
			{
				$(this).parent().removeClass('toggled');
				$this.text('접기');
			}
			else
			{
				$(this).parent().addClass('toggled');
				$this.text('펴기');
			}
		});
	});
});

var showOrHide = true;
$('.tgMap').on('click', function() {
	$(this).parent().find('ul:first > li > .side > .area_toggle').each(function() {
		if(showOrHide == true)
		{
			if($(this).parent().parent().is(':not(.toggled)'))
			{
				$(this).click();
			}
		}
		else
		{
			if($(this).parent().parent().is('.toggled'))
			{
				$(this).click();
			}
		}
	});
	if(showOrHide == true)
	{
		showOrHide = false;
	}
	else
	{
		showOrHide = true;
	}
});
</script>