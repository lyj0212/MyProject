//OS 체크 불린값 전달 window 폰, window 기반 태블릿pc 에서 테스트가 필요함
function chaked_OS(){
	var device = navigator.userAgent;
	var str = device.split(";");
	str = str[0].split("(");
	str = str[1].split(" ");
	var chkOS = false;
	if(str[0] != "Windows" && str[0] != "Macintosh" && str[0] != "compatible"){
		chkOS = true; // 데스크탑이 아닐 때 true
	}
	return chkOS;
}
//MSIE 9이하 버전체크
function ms_ver(){
	if(navigator.userAgent.match('MSIE')){
		var msie = navigator.userAgent;
		var ms_ver = msie.substr(msie.lastIndexOf('MSIE')).split('MSIE')[1];
		ms_ver = Number(ms_ver.split('.')[0]);
		return ms_ver;
	}else{
		return null;
	}
}

$(document).ready(function(){
	topSearch(); //상단 통합검색
	flowlabel(); //인풋 레이블
	iptfileType($(".fileForm")); // 파일첨부 컨트롤
	gnb(); //메뉴 활성화
	winggnb(); //윙GNB메뉴 활성화
	totalsearchView(); //통합검색 펼침
	familySlide(); //패밀리사이트 펼침
});

//상단 통합검색
function topSearch(){
	//셀렉트박스 디자인
	var select = $("select.info_select");
	select.on('change', function() {
		var select_name = $(this).children('option:selected').text();
		$(this).siblings('label').text(select_name);
	}).trigger('change');
}
//인풋 레이블
function flowlabel(){
	if(!$("label").is(".flow")) return false;
	var o1 = $("label.flow");
	var o2 = $("label.flow").next();
	o1.css({"position":"absolute"});
	o1.bind("click focusin",function(){ $(this).css({"visibility":"hidden"}); });
	o2.bind("click focusin",function(){ $(this).prev().css({"visibility":"hidden"}); });
	o2.bind("focusout",function(){
		if($(this).val() == ""){ $(this).prev().css({"visibility":"visible"}); }
	});
	$.each(o2,function(e){ if($(this).val() != "") $(this).prev().css({"visibility":"hidden"}); });
}
//파일첨부 컨트롤
function iptfileType(obj){
	var temp = "<span class=\"iptfile_wrap\">"
		+"  <input type=\"text\" class=\"form-control\" value=\"\" readonly=\"readonly\" title=\"첨부파일 등록\" />"
		+"  <a href=\"javascript:;\" class=\"btn_filesch\">찾아보기</a>"
		+"</span>";
	$(obj).find(".iptfile")
		.each(function(){
			$(this).after(temp);
		})
		.find("input[type=file]").change(function(){
			$(this).parents(".fileForm").find(".iptfile_wrap").find("input[type=text]").val($(this).val().replace(/^c:\\fakepath\\/i,''));
	});
	$(obj).find("input[type=text]").dblclick(function(){
		$(this).parents(".fileForm").find("input[type=file]").click();
	});
}

//메뉴 활성화
function gnb(){
	var param = $("#header");
	var obj = param.find(".th2");
	var btn = param.find(".th1 > a");
	var elem = 0;
	var mno = "";
	
	obj.hide();
	$.each(obj,function(){
		$(this).find(">li").last().addClass("lastchild");
	});
	function _current(s){
		if(mno == "") btn.removeClass("current"); else btn.eq(s).addClass("current");
	}
	function _open(){
		btn.not(elem).removeClass("current").eq(elem).addClass("current");
		obj.not(elem).hide().eq(elem).show();
		param.stop(true,false).animate({"height":"123px"},500,"easeOutExpo");
		param.find(".topmenubg").stop(true,false).animate({"height":"70px"},500,"easeOutExpo");
	}
	function _close(){
		btn.removeClass("current");
		param.stop(true,false).animate({"height":"75px"},500,"easeOutExpo",function(){obj.hide();});
		param.find(".topmenubg").stop(true,false).animate({"height":0},500,"easeOutExpo");
	}
	// 테블릿 터치 헨들링
	btn.bind("touchstart click",function(event){
		elem = $(this).parent().index();
		_open();
		event.stopPropagation();
	});
	btn.bind("mouseenter focusin",function(event){
		if(chaked_OS() != true){
			elem = $(this).parent().index();
			_open();
		}
		event.stopPropagation();
	});
	param.find("#gnavigation").bind("mouseleave",function(){
		_close();
		_current(elem);
	});
	_current(elem);
	$.each($("#gnavigation .th1"),function(elem){
		$(this).addClass("no"+(elem+1));
	});
}

//윙GNB메뉴 활성화
function winggnb(){
	var param = $("#nav_wing");
	var obj = param.find(".wing_menu");
	var btn_totalMenu = $("a.btn_totalMenu");
	var elem = 01; // th1 메뉴코드, index로 사용
	var elem = elem-1;
	var n = elem;
	var w = 0;
	var data = false;
	var dur = 500; // 애니메이션 진행 속도
	var meth = "easeOutExpo"; // 애니메이션 진행 타입
	var href = location.href;

	var _winggnb_open = function(){
		$(".wing_mask").stop(true,true).fadeIn(dur/2);
		$("body").addClass("wing_open");
		param.stop(true,true).animate({scrollTop:$(".lnk_th1").eq(elem).offset().top},dur,meth);
		data = true;
	};
	var _winggnb_close = function(){
		if(n != elem){
			param.find(".lnk_th1").not(elem).removeClass("on").next().stop(true,true).delay(300).slideUp(150);
			param.find(".lnk_th1").eq(elem).addClass("on").next().stop(true,true).slideDown(300);
		}
		$(".wing_mask").stop(true,true).fadeOut(dur/2,function(){
			data = false;
		});
		$("body").removeClass("wing_open");
		//param.stop(true,true).delay(dur).animate({scrollTop:0},0,meth);
	};
 	 
	btn_totalMenu.unbind().bind("click touchend",function(event){
		if(data == false) obj.queue(_winggnb_open).dequeue(); else obj.queue(_winggnb_close).dequeue();
		$(window).resize(function(){
			$(".wing_mask").click();
		});
		event.preventDefault();
		event.stopPropagation();
	});
	param.find("a").bind("click",function(event){
		if($(".nav_wing:animated").size()) return false;
		event.stopPropagation();
	}) 
	// 터치 아웃 영역
	$(".wing_mask").unbind().bind("click touchmove",function(event){
		obj.queue(_winggnb_close).dequeue();
		event.preventDefault();
		event.stopPropagation();
	});

	// 트리메뉴 컨트롤
	param.find(".lnk_th1").not(elem).next().hide().eq(elem).show().prev().addClass("on");
	param.find(".lnk_th1").bind("click",function(event){
		n = $(this).parent().index();
		if($(this).next().css("display") == "none"){
			param.find(".lnk_th1").not(n).removeClass("on").next().stop(true,true).delay(300).slideUp(150);
			param.find(".lnk_th1").eq(n).addClass("on").next().stop(true,true).slideDown(300);
		}
		if($(this).next().attr('class') == 'th2'){
			event.preventDefault();
			event.stopPropagation();
		}
	});
	param.find(".th2 a").bind("click",function(event){
		if($(this).next().css("display") == "none"){
			$(this).next().show();
		}else{
			$(this).next().hide();
		}
		if($(this).next().attr('class') == 'th3'){
			event.preventDefault();
			event.stopPropagation();
		}
	});
	param.find(".btn_close a").bind("click",function(event){
		obj.queue(_winggnb_close).dequeue();
		event.preventDefault();
		event.stopPropagation();
	});
}

//통합검색 펼침
function totalsearchView(){
	var param = $(".tside_wrap");
	var obj = param.find(".totalsearch");
	var btn = param.find("li > a.btn_totalSch");

	btn.unbind().bind("click",function(event){
		if(param.find(".totalsearch").css("display") == "none"){
			param.find(".totalsearch").stop(true,true).slideDown(300);
		}else{
			param.find(".totalsearch").stop(true,true).slideUp(300);
		}
		event.preventDefault();
		event.stopPropagation();
	});
	param.find(".btn_close button").bind("click",function(event){
		if(param.find(".totalsearch").css("display") == "none"){
			param.find(".totalsearch").stop(true,true).slideDown(300);
		}else{
			param.find(".totalsearch").stop(true,true).slideUp(300);
		}
		event.preventDefault();
		event.stopPropagation();
	});
}

//패밀리사이트 펼침
function familySlide(){
	var param = $(".organ_links");
	var obj = param.find(".organ_links_obj");
	var btn = param.find("dt > a");

	btn.unbind().bind("click",function(event){
		var t = $(this);
		if(t.parent().next("dd").css("display") == "none"){
			t.parent().next("dd").stop(true,true).slideDown(300);
		}else{
			t.parent().next("dd").stop(true,true).slideUp(300);
		}
		t.parent().toggleClass("active");
		event.preventDefault();
		event.stopPropagation();
	});
}

//하단 배너 슬라이드
$(window).load(function(){
	var bannerSlide = $('.banner_wrap .banner_lst').bxSlider({
		mode: 'horizontal',
		slideWidth: 153,
	    minSlides: 7,
	    maxSlides: 7,
	    moveSlides: 1,
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
