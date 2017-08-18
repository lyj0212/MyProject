	/**
	 * 멀티셀렉트간 이동 스크립트
	 *
	 * 2012-09-04 Uks
	 */
(function(jQuery) {
	jQuery.SelectOrder = function(options) {
		var plugin = this;
		var select_list;
		var select_target;
		var btn_left;
		var btn_right;
		var btn_up;
		var btn_down;
		var return_input;

		plugin.settings = {
			select_list: '.select_list',
			select_target: '.select_target',
			leftArrow: '.arrow-left',
			rightArrow: '.arrow-right',
			upArrow: '.arrow-up',
			downArrow: '.arrow-down',
			return_input: '#result_list'
		}

		jQuery.extend(plugin.settings, options);
		var init = function() {
			select_list = jQuery(plugin.settings.select_list);
			select_target = jQuery(plugin.settings.select_target);

			btn_left = jQuery(plugin.settings.leftArrow);
			btn_right = jQuery(plugin.settings.rightArrow);
			btn_up = jQuery(plugin.settings.upArrow);
			btn_down = jQuery(plugin.settings.downArrow);

			return_input = jQuery(plugin.settings.return_input);

			btn_left.on('click', do_left);
			btn_right.on('click', do_right);
			btn_up.on('click', do_up);
			btn_down.on('click', do_down);

			result();
		}

		var do_left = function() {
			jQuery(select_target).find('>option:selected[default != "true"]').appendTo(select_list);
			result();

			return false;
		}

		var do_right = function() {
			jQuery(select_list).find('>option:selected').appendTo(select_target);
			result();

			return false;
		}

		var do_up = function() {
			var selected = select_target.find('>option:selected');
			selected.eq(0).prev('option').before(selected);
			result();

			return false;
		}

		var do_down = function() {
			var selected = select_target.find('>option:selected');
			selected.eq(-1).next('option').after(selected);
			result();

			return false;
		}

		var result = function() {
			var value = select_target.find('>option').map(function() { return jQuery(this).val(); }).get().join(',');
			jQuery(return_input).val(value);
		}

		init();
	}
})(jQuery);
