<script>
	$(function() {
		$('.chosen').chosen({no_results_text: '일치하는 항목이 없습니다.', width: '85%', disable_search_threshold:5}).change(function(e, param) {
			var this_ = this;

			if(param.selected && param.selected == '__ALL__')
			{
				$(this_).find('option').prop('selected', true);
				$(this_).trigger('chosen:updated');
			}

			if(param.deselected && param.deselected != '__ALL__')
			{
				$(this_).find('option:selected[value="__ALL__"]').prop('selected', false);
				$(this_).trigger('chosen:updated');
			}

		});
	});
</script>