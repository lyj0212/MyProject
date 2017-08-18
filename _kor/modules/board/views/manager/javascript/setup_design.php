<script>
(function(jQuery) {
	jQuery.getClosestColor = function(options) {
		var plugin = this;
		var targetColor;
		var percentage;

		plugin.settings = {
			color : '',
			level: '2' // 1~25 작을수록 같은색 클수록 옅은색
		}

		jQuery.extend(plugin.settings, options);
		var init = function() {
			plugin.setTargetColor(plugin.settings.color);
			plugin.setPercentage(plugin.settings.level);
		}

		plugin.setTargetColor = function(color) {
			color = color.replace('#', '');
			targetColor = color;
			return plugin;
		}

		plugin.setPercentage = function(percent) {
			percentage = percent;
			return plugin;
		}

		plugin.create = function() {
			var result = Array();
			var R = parseInt(targetColor.substring(0, 2), 16);
			var G = parseInt(targetColor.substring(4, 6), 16);
			var B = parseInt(targetColor.substring(2, 4), 16);

			for(var i=0; i<26; i++)
			{
				R = (R+10 < 255) ? R+10 : R;
				G = (G+10 < 255) ? G+10 : G;
				B = (B+10 < 255) ? B+10 : B;

				var RED = (R.toString(16).length <= 1) ? '0' + R.toString(16) : R.toString(16);
				var GREEN = (G.toString(16).length <= 1) ? '0' + G.toString(16) : G.toString(16);
				var BLUE = (B.toString(16).length <= 1) ? '0' + B.toString(16) : B.toString(16);

				if(targetColor == RED + BLUE + GREEN)
				{
					return false;
				}
				else
				{
					result[result.length] = RED + BLUE + GREEN;
				}
			}
			return result;
		}
		init();
	}
})(jQuery);

$('.color-picker').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex).next().css('background-color', '#' + hex);
		$(el).ColorPickerHide();
		reFreshColor();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		$($(this).ColorPickerElement()).val(hex).next().css('background-color', '#' + hex);
		if($($(this).ColorPickerElement()).attr('id') == 'main_color')
		{
			$($(this).ColorPickerElement()).trigger('change');
		}
		reFreshColor();
	}
})
.on('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
}).on('change', function() {
	reFreshColor();
});


var title_top = $('input[name="color[title_top_color]"]');
var title_bottom = $('input[name="color[title_bottom_color]"]');
var title = $('input[name="color[title_color]"]');
var title_bg = $('input[name="color[title_bg_color]"]');
var notice = $('input[name="color[notice_color]"]');
var notice_bg = $('input[name="color[notice_bg_color]"]');
var lilne = $('input[name="color[line_color]"]');

function reFreshColor()
{
	var title_top_color, title_bottom_color, title_color, title_bg_color,
		notice_color, notice_bg_color, line_color;

	title_top_color = '#' + title_top.val();
	title_bottom_color = '#' + title_bottom.val();
	title_color = '#' + title.val();
	title_bg_color = '#' + title_bg.val();
	notice_color = '#' + notice.val();
	notice_bg_color = '#' + notice_bg.val();
	line_color = '#' + lilne.val();

	$('.example-table th').attr('style',
		'color : ' + title_color + ';' +
		'background-color : ' + title_bg_color + ';' +
		'border-top-color : ' + title_top_color + ' !important;' +
		'border-bottom-color : ' + title_bottom_color + ' !important;'
	);
}

var color = new $.getClosestColor();

$('#main_color').on('change', function() {

	var closestColor = color.setTargetColor($(this).val()).create();

	title_top.val($(this).val());
	title_bottom.val(closestColor[3]);
	title.val(closestColor[2]);
	title_bg.val(closestColor[closestColor.length-2]);
});
	//alert(color.create());
</script>
