$(document).ready(function(){
	function initialization(){
		var SCROLLING_SPEED = 1000;
		$('#detail_content').fullpage({
			sectionsColor: ['#42423c', '#d7d8d8', '#f7f6f2', '#f8f6f1'],
			anchors: ['mainVisual', 'Company', 'Product', 'Contact'],
			animateAnchor: false,
			menu: '#gnavigation',
			navigation:true,
			navigationPosition: 'left',
			navigationTooltips: ['HOME', 'COMPANY','PRODUCT','CONTACT'],
			slidesNavigation: true,
			css3: true,
			easingcss3: 'cubic-bezier(0.175, 0.885, 0.320, 1.275)',
			loopHorizontal: true,
			scrollingSpeed: SCROLLING_SPEED,
			'afterLoad': function(anchorLink, index){
				$('#header').removeClass('bdline');
				if(index == 1){
					$('#header').removeClass('bdline');
					$('#footer').css({'bottom':'-395px'});
				}
				if(index == 2 && index == 3){
					$('#header').addClass('bdline');
					$('#footer').css({'bottom':'-395px'});
				}
				if(index == 4){
					$('#header').removeClass('bdline');
					$('#footer').css({'bottom':0});
				}
			},
			onSlideLeave: function(anchorLink, index, slideIndex, direction){
				$('.curtain').removeClass('current');
				$.fn.fullpage.setScrollingSpeed(0);
				//$('.fp-section').find('.fp-slidesContainer').hide();
			},
			afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){
				$('.curtain').addClass('current');
				$('.intro.current').removeClass('current');
				$('.intro').eq(slideIndex).addClass('current');
				$.fn.fullpage.setScrollingSpeed(SCROLLING_SPEED);
				//$('.fp-section').find('.fp-slidesContainer').fadeIn(700);
			},
			afterRender: function(){
				setInterval(function(){
					$.fn.fullpage.moveSlideRight();
				},5000);
			}
		});
	}
	initialization();
	productTab(); //제품 탭메뉴
	$('.curtain').addClass('current');
	$('.intro').addClass('current');
});
$(window).bind("load resize",function(){
	setTimeout(function(){
		//initialization();
	},300);
});

//제품 탭메뉴
function productTab(){
	var param = $(".product_wrap");
	var btn = param.find("h3>a");
	var obj = param.find(".product_lst");
	var event = "click";
	btn.parent().eq(0).addClass("active");
	obj.eq(0).addClass("active");
	btn.bind(event,function(ev){
		var t = $(this);
		obj.removeClass("active");
		btn.parent().removeClass("active");
		t.parent().addClass("active");
		t.parent().next().addClass("active");
		ev.preventDefault();
		ev.stopPropagation();
	});
}