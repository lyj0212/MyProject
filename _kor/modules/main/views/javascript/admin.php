<script>
$(function() {
	var ajaxDataRender = function(url, plot, options) {
		var ret = null;
		$.ajax({
			async : false,
			url : url,
			dataType : 'json',
			success : function(data) {
				ret = data;
			}
		});
		return ret;
	};

	var jsonurl = '<?php echo $this->link->get(array('action'=>'json_data'), FALSE); ?>';
  var plot = $.jqplot('linechart', jsonurl, {
		dataRenderer: ajaxDataRender,
		dataRendererOptions: {
			unusedOptionalUrl: jsonurl
		},
		axes : {
			xaxis : {
				renderer : $.jqplot.DateAxisRenderer,
				tickInterval : '3 days',
				tickOptions : {
					formatString : '%m.%d(%a)'
				}
			},
			yaxis : {
				min:-1,
				tickOptions : {
					formatString : '%.0f'
				}
			}
		},
		legend : {
			show : true
		},
		series:[{color:'#3193da'}],
		grid : {
			gridLineColor :'#EAEAEA',
			background : '#FFFFFF',
			borderColor : '#CCCCCC',
			borderWidth : 1.0,
			shadow : false
		},
		highlighter : {
			show : true,
			sizeAdjust : 7.5,
			fade : false,
			tooltipLocation : 'n',
			formatString : '%s, 방문수 : %.0f'
		},
		seriesDefaults : {
			showMarker : true,
			pointLabels : {
				show : true,
				formatString : '%.0f'
			},
			label : '방문수'
		}
	});

	var data = [<?php echo implode(', ', $browser_json); ?>];
	var plot1 = jQuery.jqplot ('piechart1', [data], {
		title : {
			text : '브라우져 (<?php echo sprintf('%d', $month); ?>월)',
			fontFamily : 'dotum',
			fontSize : '10pt'
		},
		seriesDefaults : {
			renderer: jQuery.jqplot.PieRenderer,
			rendererOptions : {
				showDataLabels : true
			}
		},
		legend : { show:true, location: 'e' },
		grid : {
			gridLineColor :'#EAEAEA',
			background : '#FFFFFF',
			borderColor : '#CCCCCC',
			borderWidth : 1.0,
			shadow : false
		}
	});

	var data = [<?php echo implode(', ', $platform_json); ?>];
	var plot2 = jQuery.jqplot ('piechart2', [data], {
		title : {
			text : '운영체제 (<?php echo sprintf('%d', $month); ?>월)',
			fontFamily : 'dotum',
			fontSize : '10pt'
		},
		seriesDefaults : {
			renderer: jQuery.jqplot.PieRenderer,
			rendererOptions : {
				showDataLabels : true
			}
		},
		legend : { show:true, location: 'e' },
		grid : {
			gridLineColor :'#EAEAEA',
			background : '#FFFFFF',
			borderColor : '#CCCCCC',
			borderWidth : 1.0,
			shadow : false
		}
	});
});
</script>
