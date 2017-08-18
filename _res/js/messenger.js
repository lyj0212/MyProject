var opts = {};

if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
	opts['sync disconnect on unload'] = true;
}

opts['transports'] = ['websocket', 'polling'];

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 서버 데몬 접속
 * --------------------------------------------------------------------------------------------------------------------
 */
var socket = io.connect('114.108.180.142:5675', opts);

$(function() {
	socket.emit('join', messenger_data);
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 특정 회원에게 알리기
 * --------------------------------------------------------------------------------------------------------------------
 */
function whisper(data)
{
	socket.emit('whisper', data);
}

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 새소식 알리기
 * --------------------------------------------------------------------------------------------------------------------
 */
socket.on('whisper', function(data) {
	if(parent && parent == self)
	{
		var total_feeds = $('._totalfeeds').show().text();
		if(total_feeds != '99+')
		{
			if(total_feeds == '') total_feeds = 0;
			$('._totalfeeds').text(parseInt(total_feeds) + 1);
		}

		// Encode htmlentities
		data.message = $('<div />').text(data.message).html();

		var li = '<li><a href="' + data.link + '" ' + data.attr + '><img src="' + data.picture + '" /><span class="subject ellipsis"><img src="/images/social/common/new_icon.gif" />' + data.message + '</span><span class="date">' + data.name + ', <span class="_timestamp" data-timestamp="' + Math.round(Date.now()/1000)  + '"></span></span></a></li>';
		$('.notifications').find('.nofeeds').addClass('hide').end().prepend(li);
		$('.notifications>li:gt(5)').remove();
		showNotification(data);
	}
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 접속시 마이피플 목록에 추가
 * --------------------------------------------------------------------------------------------------------------------
 */
socket.on('join', function(member_id) {
	if(parent && parent == self)
	{
		$('._connecting_member[data-id="' + member_id + '"]').show();
		if($('._connecting_member:visible').length > 0)
		{
			$('.stats_lst_access').hide();
			$('.stats_lst_btn').show();
		}

		if($('._connecting_member:visible').length > 10)
		{
			$('._show_more_mypeple_btn').removeClass('hide');
		}
	}
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 접속종료 마이피플 목록에서 삭제
 * --------------------------------------------------------------------------------------------------------------------
 */
socket.on('leave', function(member_id) {
	if(parent && parent == self)
	{
		$('._connecting_member[data-id="' + member_id + '"]').hide();
		if($('._connecting_member:visible').length == 0)
		{
			$('.stats_lst_access').show();
			$('.stats_lst_btn').hide();
		}

		if($('._connecting_member:visible').length <= 10)
		{
			$('._show_more_mypeple_btn').addClass('hide');
		}
	}
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 데시크탑 알림
 * --------------------------------------------------------------------------------------------------------------------
 */
function showNotification(data)
{
	$('.noti_playsound').remove();
	$('<div class="noti_playsound" />').appendTo('body').jmp3();

	data.message = data.message.replace(/(<([^>]+)>)/ig, "");
	notify.createNotification(data.title, {body:data.message, icon:data.picture, link:data.link, tag:md5(data.title + data.message + data.link)});
}

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 데스크탑 알림 활성화
 * --------------------------------------------------------------------------------------------------------------------
 */
$(function() {
	$('#meminfo_notice').on('click', function() {
		$('.meminfo_layer').toggle();
	});

	$('body').on('click', function(e) {
		if($(e.target).closest('#meminfo_notice').length == 0 && $(e.target).closest('.meminfo_layer').length == 0)
		{
			$('.meminfo_layer:visible').hide();
		}
	});

	if(notify.isSupported == true)
	{
		if(notify.permissionLevel() == 'default')
		{
			$('.feeds_notice_head .btn-group').prepend('<a class="webnotification btn btn-default"><span class="icon-cog-1">알림설정</span></a>').find('.webnotification').on('click', function(e) {
				e.preventDefault();
				notify.requestPermission(function() {
					location.reload();
				});
				var isIE = false;
				try {
					isIE = (window.external && window.external.msIsSiteMode() !== undefined);
				} catch(e) {}

				if(isIE)
				{
					alert('알림을 수신하기 위해 작업 표시 줄에 현재 페이지를 고정해주세요.');
				}
				else
				{
					alert('상단 주소표시줄 아래 바탕화면 알림 메세지에 허용을 눌러주세요.');
				}
			}).tooltip({
				title : '설정시 데스크탑에서 알림 보기가 가능합니다.',
				placement : 'left',
				container : 'body'
			});
		}
	}
});

/**
 * Javascript md5
 * https://github.com/blueimp/JavaScript-MD5
 */
!function(a){"use strict";function b(a,b){var c=(65535&a)+(65535&b),d=(a>>16)+(b>>16)+(c>>16);return d<<16|65535&c}function c(a,b){return a<<b|a>>>32-b}function d(a,d,e,f,g,h){return b(c(b(b(d,a),b(f,h)),g),e)}function e(a,b,c,e,f,g,h){return d(b&c|~b&e,a,b,f,g,h)}function f(a,b,c,e,f,g,h){return d(b&e|c&~e,a,b,f,g,h)}function g(a,b,c,e,f,g,h){return d(b^c^e,a,b,f,g,h)}function h(a,b,c,e,f,g,h){return d(c^(b|~e),a,b,f,g,h)}function i(a,c){a[c>>5]|=128<<c%32,a[(c+64>>>9<<4)+14]=c;var d,i,j,k,l,m=1732584193,n=-271733879,o=-1732584194,p=271733878;for(d=0;d<a.length;d+=16)i=m,j=n,k=o,l=p,m=e(m,n,o,p,a[d],7,-680876936),p=e(p,m,n,o,a[d+1],12,-389564586),o=e(o,p,m,n,a[d+2],17,606105819),n=e(n,o,p,m,a[d+3],22,-1044525330),m=e(m,n,o,p,a[d+4],7,-176418897),p=e(p,m,n,o,a[d+5],12,1200080426),o=e(o,p,m,n,a[d+6],17,-1473231341),n=e(n,o,p,m,a[d+7],22,-45705983),m=e(m,n,o,p,a[d+8],7,1770035416),p=e(p,m,n,o,a[d+9],12,-1958414417),o=e(o,p,m,n,a[d+10],17,-42063),n=e(n,o,p,m,a[d+11],22,-1990404162),m=e(m,n,o,p,a[d+12],7,1804603682),p=e(p,m,n,o,a[d+13],12,-40341101),o=e(o,p,m,n,a[d+14],17,-1502002290),n=e(n,o,p,m,a[d+15],22,1236535329),m=f(m,n,o,p,a[d+1],5,-165796510),p=f(p,m,n,o,a[d+6],9,-1069501632),o=f(o,p,m,n,a[d+11],14,643717713),n=f(n,o,p,m,a[d],20,-373897302),m=f(m,n,o,p,a[d+5],5,-701558691),p=f(p,m,n,o,a[d+10],9,38016083),o=f(o,p,m,n,a[d+15],14,-660478335),n=f(n,o,p,m,a[d+4],20,-405537848),m=f(m,n,o,p,a[d+9],5,568446438),p=f(p,m,n,o,a[d+14],9,-1019803690),o=f(o,p,m,n,a[d+3],14,-187363961),n=f(n,o,p,m,a[d+8],20,1163531501),m=f(m,n,o,p,a[d+13],5,-1444681467),p=f(p,m,n,o,a[d+2],9,-51403784),o=f(o,p,m,n,a[d+7],14,1735328473),n=f(n,o,p,m,a[d+12],20,-1926607734),m=g(m,n,o,p,a[d+5],4,-378558),p=g(p,m,n,o,a[d+8],11,-2022574463),o=g(o,p,m,n,a[d+11],16,1839030562),n=g(n,o,p,m,a[d+14],23,-35309556),m=g(m,n,o,p,a[d+1],4,-1530992060),p=g(p,m,n,o,a[d+4],11,1272893353),o=g(o,p,m,n,a[d+7],16,-155497632),n=g(n,o,p,m,a[d+10],23,-1094730640),m=g(m,n,o,p,a[d+13],4,681279174),p=g(p,m,n,o,a[d],11,-358537222),o=g(o,p,m,n,a[d+3],16,-722521979),n=g(n,o,p,m,a[d+6],23,76029189),m=g(m,n,o,p,a[d+9],4,-640364487),p=g(p,m,n,o,a[d+12],11,-421815835),o=g(o,p,m,n,a[d+15],16,530742520),n=g(n,o,p,m,a[d+2],23,-995338651),m=h(m,n,o,p,a[d],6,-198630844),p=h(p,m,n,o,a[d+7],10,1126891415),o=h(o,p,m,n,a[d+14],15,-1416354905),n=h(n,o,p,m,a[d+5],21,-57434055),m=h(m,n,o,p,a[d+12],6,1700485571),p=h(p,m,n,o,a[d+3],10,-1894986606),o=h(o,p,m,n,a[d+10],15,-1051523),n=h(n,o,p,m,a[d+1],21,-2054922799),m=h(m,n,o,p,a[d+8],6,1873313359),p=h(p,m,n,o,a[d+15],10,-30611744),o=h(o,p,m,n,a[d+6],15,-1560198380),n=h(n,o,p,m,a[d+13],21,1309151649),m=h(m,n,o,p,a[d+4],6,-145523070),p=h(p,m,n,o,a[d+11],10,-1120210379),o=h(o,p,m,n,a[d+2],15,718787259),n=h(n,o,p,m,a[d+9],21,-343485551),m=b(m,i),n=b(n,j),o=b(o,k),p=b(p,l);return[m,n,o,p]}function j(a){var b,c="";for(b=0;b<32*a.length;b+=8)c+=String.fromCharCode(a[b>>5]>>>b%32&255);return c}function k(a){var b,c=[];for(c[(a.length>>2)-1]=void 0,b=0;b<c.length;b+=1)c[b]=0;for(b=0;b<8*a.length;b+=8)c[b>>5]|=(255&a.charCodeAt(b/8))<<b%32;return c}function l(a){return j(i(k(a),8*a.length))}function m(a,b){var c,d,e=k(a),f=[],g=[];for(f[15]=g[15]=void 0,e.length>16&&(e=i(e,8*a.length)),c=0;16>c;c+=1)f[c]=909522486^e[c],g[c]=1549556828^e[c];return d=i(f.concat(k(b)),512+8*b.length),j(i(g.concat(d),640))}function n(a){var b,c,d="0123456789abcdef",e="";for(c=0;c<a.length;c+=1)b=a.charCodeAt(c),e+=d.charAt(b>>>4&15)+d.charAt(15&b);return e}function o(a){return unescape(encodeURIComponent(a))}function p(a){return l(o(a))}function q(a){return n(p(a))}function r(a,b){return m(o(a),o(b))}function s(a,b){return n(r(a,b))}function t(a,b,c){return b?c?r(b,a):s(b,a):c?p(a):q(a)}"function"==typeof define&&define.amd?define(function(){return t}):a.md5=t}(this);

/***
 *  jMP3 v0.2.1 - 10.10.2006 (w/Eolas fix & jQuery object replacement)
 * an MP3 Player jQuery Plugin (http://www.sean-o.com/jquery/jmp3)
 * by Sean O
 *
 * An easy way make any MP3 playable directly on most any web site (to those using Flash & JS),
 * using the sleek Flash Single MP3 Player & the fantabulous jQuery.
 *
 * SIMPLE USAGE Example:
 * $(youridorclass).jMP3();
 *
 * ADVANCED USAGE Example:
 * $("#sounddl").jmp3({
*   showfilename: "false",
*   backcolor: "000000",
*   forecolor: "00ff00",
*   width: 200,
*   showdownload: "false"
* });
 *
 * HTML:
 * <span class="mp3">sound.mp3</span>
 *
 * NOTE: filename must be enclosed in tag.  Various file paths can be set using the filepath option.
 *
 * Copyright (c) 2006 Sean O (http://www.sean-o.com)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 ***/
jQuery.fn.jmp3 = function(passedOptions) {
	// hard-wired options
	var playerpath = "/_res/sound/";					// SET THIS FIRST: path to singlemp3player.swf

	// passable options
	var options = {
		"filepath": "/_res/sound/alarm2.mp3",										// path to MP3 file (default: current directory)
		"backcolor": "",									// background color
		"forecolor": "ffffff",								// foreground color (buttons)
		"width": "0",										// width of player
		"repeat": "no",										// repeat mp3?
		"volume": "100",										// mp3 volume (0-100)
		"autoplay": "true",								// play immediately on page load?
		"showdownload": "false",								// show download button in player
		"showfilename": "false"								// show .mp3 filename after player
	};

	// use passed options, if they exist
	if (passedOptions) {
		jQuery.extend(options, passedOptions);
	}

	// iterate through each object
	return this.each(function(){
		// filename needs to be enclosed in tag (e.g. <span class='mp3'>mysong.mp3</span>)
		var filename = options.filepath;
		// do nothing if not an .mp3 file
		// build the player HTML

		var mp3html = '<audio autoplay="autoplay">';
		mp3html += '<source src="' + filename + '" type="audio/ogg">';
		mp3html += '<source src="' + filename + '" type="audio/mpeg">';

		mp3html += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
		mp3html += 'width="' + options.width + '" height="0" ';
		mp3html += 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">';
		mp3html += '<param name="movie" value="' + playerpath + 'singlemp3player.swf?';
		mp3html += 'showDownload=' + options.showdownload + '&file=' + filename + '&autoStart=' + options.autoplay;
		mp3html += '&backColor=' + options.backcolor + '&frontColor=' + options.forecolor;
		mp3html += '&repeatPlay=' + options.repeat + '&songVolume=' + options.volume + '" />';
		mp3html += '<param name="wmode" value="transparent" />';
		mp3html += '<embed wmode="transparent" width="' + options.width + '" height="0" ';
		mp3html += 'src="' + playerpath + 'singlemp3player.swf?'
		mp3html += 'showDownload=' + options.showdownload + '&file=' + filename + '&autoStart=' + options.autoplay;
		mp3html += '&backColor=' + options.backcolor + '&frontColor=' + options.forecolor;
		mp3html += '&repeatPlay=' + options.repeat + '&songVolume=' + options.volume + '" ';
		mp3html += 'type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
		mp3html += '</object>';

		mp3html += '</audio>';

		// don't display filename if option is set
		if (options.showfilename == "false") { jQuery(this).html(""); }
		jQuery(this).prepend(mp3html+"&nbsp;");

		// Eolas workaround for IE (Thanks Kurt!)
		//if(jQuery.browser.msie){ this.outerHTML = this.outerHTML; }
	});


};