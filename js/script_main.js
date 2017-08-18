
$(document).ready(function(){
});

$(window).load(function(){
	var visualSlide = $('.mVisual_wrap > ul.obj').bxSlider({//메인비쥬얼
		mode: 'horizontal',
		auto: true,
		pause: 5000,
		autoControls: true,
		autoStart: true,
		autoDelay: 0,
		autoHover: false,
		controls: false,
		pagerSelector: '.pivot',
		autoControlsSelector: '.autoCtrl',
		touchEnabled: true
	});

	var scheduleSlide = $('.schedule_wrap .schedule_lst').bxSlider({//일정안내 슬라이드
		mode: 'vertical',
		auto: false,
		pause: 3000,
		autoControls: false,
		autoStart: false,
		autoDelay: 0,
		autoHover: false,
		controls: true,
		pager: false,
		touchEnabled: false
	});
});