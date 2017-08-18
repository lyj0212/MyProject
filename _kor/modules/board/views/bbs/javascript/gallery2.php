<script>
$(window).load( function() {
	$('#gridgallery').BlocksIt({
		numOfCol: 4,
		offsetX: 8,
		offsetY: 8
	});

	$(window).trigger('resize');
});

//window resize
var currentCol = 4;
$(window).resize(function() {
	var currentWidth = $('#gridgallery').width();
	/*
	if(currentWidth < 370) {
		col = 1;
	} else if(currentWidth < 500) {
		col = 2;
	} else if(currentWidth < 670) {
		col = 3;
	} else if(currentWidth < 900) {
		col = 4;
	} else {
		col = 5;
	}*/
	
	if(currentWidth < 283) {
		col = 1;
	} else if(currentWidth < 563) {
		col = 2;
	} else if(currentWidth < 845) {
		col = 3;
	} else {
		col = 4;
	}

	if(currentCol != col)
	{
		$('#gridgallery').BlocksIt({
			numOfCol: col,
			offsetX: 8,
			offsetY: 8
		});
	}
});
</script>
