function setInitialize()
{
	$(document).ajaxStart(function() { $('#cLoadingOverlay').show(); });
	$(document).ajaxStop(function() { $('#cLoadingOverlay').hide(); });

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * New 이미지를 제목 앞으로
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('.ellipsis:has(img.new_icon):not(:has(img.new_icon:visible))')
		.prepend('<img src="/_res/img/new_icon.gif" style="margin-right:10px; display:inline" alt="new" />');

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * 팝업 버튼 비활성화
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('[disabled="disabled"]').on('click', function(e) {
		e.preventDefault();
	});

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * placeholder 태그를 지원하지 않는 브라우져에서 가능하도록 처리
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('input[placeholder], textarea[placeholder]').placeholder();

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * 툴팀 활성화
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('._tooltip').tooltip({container : 'body'});

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * typeaheads 엔터 비활성화
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('.typeaheads').on('keydown', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();
		}
	});

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * jquery validator 플러그인 활성화
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$.extend($.validator.messages, {
		required:"필수입력",
		remote:"Please fix this field.",
		email:"올바른 이메일을 입력해 주세요.",
		url:"올바른 URL 값을 입력해 주세요.",
		date:"올바른 날짜 형식으로 입력해 주세요.",
		dateISO:"Please enter a valid date (ISO).",
		number:"숫자만 입력할 수 있습니다.",
		digits:"Please enter only digits.",
		creditcard:"Please enter a valid credit card number.",
		equalTo:"Please enter the same value again.",
		maxlength:jQuery.validator.format("{0}자 이내로 입력해 주세요."),
		minlength:jQuery.validator.format("{0}자 이상 입력해 주세요."),
		rangelength:jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
		range:jQuery.validator.format("Please enter a value between {0} and {1}."),
		max:jQuery.validator.format("Please enter a value less than or equal to {0}."),
		min:jQuery.validator.format("{0} 이상 입력해주세요.")
	});

	// Correct date validation error in Chrome and Safari.
	$.validator.methods["date"] = function (value, element)
	{
		//var d = new Date();
		//alert(new Date(d.toLocaleDateString(value)));
		//return this.optional(element)||!/Invalid|NaN/.test(new Date(d.toLocaleDateString(value)));

		var shortDateFormat = "yy-mm-dd";
		var res = true;
		try {
			$.datepicker.parseDate(shortDateFormat, value);
		} catch (error) {
			res = false;
		}
		return res;
	};

	/*
	 * --------------------------------------------------------------------------------------------------------------------
	 * submit validation 처리 및 저장버튼 비활성화 처리
	 * --------------------------------------------------------------------------------------------------------------------
	 */
	$('form').each(function(index, form)
	{
		$(form).on('submit', function() {
			$(this).find('button[type="submit"], input[type="submit"]').filter(':not(.non_loading)').attr('data-loading-text', '처리중').button('loading');
		}).validate({
			invalidHandler: function(form) {
				$(this).find('button[type="submit"], input[type="submit"]').button('reset');
			},
			showErrors: function (errorMap, errorList) {
				$.each(this.successList, function (index, value) {
					$(value).tooltip("destroy");
				});
				$.each(errorList, function (index, value) {
					$(value.element).tooltip({trigger: 'manual', animation: false, container: 'body'}).attr("data-original-title", value.message).tooltip('show');
				});
			}
		});
	});

	$('._popup').removeAttr('disabled');
}

$(function() {
	setInitialize();
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 레이어 팝업
 * --------------------------------------------------------------------------------------------------------------------
 */
$(document).on('click', '._popup', function(e) {
	e.preventDefault();

	var this_ = this;
	var target = ($(this_).data('parent') === true) ? parent : self;

	target.$.colorbox({
		href : $(this_).attr('href'),
		iframe : true,
		width : $(this_).data('width') || '95%',
		height : 0,
		maxWidth : $(this_).data('maxwidth') || 770,
		initialWidth : 0,
		initialHeight : 0,
		scrolling : false,
		onClosed : function() {
			Resize_Box();
			$('#cLoadingOverlay', parent.document).hide();
			if($(this_).data('reload') === true)
			{
				location.reload();
			}
		}
	});

	try {
		var callback = $(this_).data('callback');
		var fn = window[callback];
		if($.isFunction(fn))
		{
			fn.call(null, this_);
		}
	} catch(e) {}
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 섬네일 마우스 오버시 미리보기
 * --------------------------------------------------------------------------------------------------------------------
 */
$(document).on('mouseenter', '.thumb_preview', function() {
	var src = ($(this).attr('data-src')) ? $(this).attr('data-src') : $(this).attr('src');
	$(this).popover({trigger : 'menual', html : true, content : '<img src="' + src + '" />'}).popover('show');
}).on('mouseleave', '.thumb_preview', function() {
	$('.popover ').hide();
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 팝업닫기 버튼
 * --------------------------------------------------------------------------------------------------------------------
 */
$('.close_modal').click(function(e) {
	e.preventDefault();
	parent.$.colorbox.close();
});

if(parent && parent == self)
{
	//$('.close_modal').hide();
}

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 팝업 리사이즈
 * --------------------------------------------------------------------------------------------------------------------
 */
function Resize_Box(callback)
{
	var isInIframe = (parent !== self) ? true : false;
	if(isInIframe == true)
	{
		var x = $('body').width();
		var y = $('body').height();

		try {
			if(parent.parent !== parent)
			{
				parent.parent.$.colorbox.resize({
					innerHeight: y
				});
			}
		}
		catch (e) {}

		try {
			parent.$.colorbox.resize({
				innerWidth: x,
				innerHeight: y
			});
		}
		catch (e) {}

		$('.cboxIframe', parent.document)[0].contentWindow.focus();

		if( $.isFunction( callback ) )
		{
			callback.call();
		}
	}
}

/*
 * --------------------------------------------------------------------------------------------------------------------
 * 팝업오픈시 로딩 이미지 처리
 * --------------------------------------------------------------------------------------------------------------------
 */
$(function() {
	$('#cLoadingOverlay', parent.document).hide();
	Resize_Box();
	setTimeout(function() { Resize_Box(); }, 500);
	setTimeout(function() { Resize_Box(); }, 1000);
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * input type=number 작성시 숫자를 한글료 표시
 * --------------------------------------------------------------------------------------------------------------------
 */
$(document).on('keyup focus', 'input[type="number"]', function() {
	if($(this).val() != '' && $(this).val() != 0)
	{
		var title = $().number_format($(this).val()) + ' (' + num2str($(this).val()) + ')';
		$(this).tooltip('destroy').tooltip({trigger: 'manual', animation: false, title : title, container: 'body'}).tooltip('show');
	}
	else
	{
		$(this).tooltip('destroy');
	}
}).on('focusout', 'input[type="number"]', function() {
	$(this).tooltip('destroy');
});

/*
 * --------------------------------------------------------------------------------------------------------------------
 * date 타입을 지원하지 않으면 bootstrap datepicker 활성화
 * --------------------------------------------------------------------------------------------------------------------
 */
if( ! Modernizr.inputtypes.date)
{
	var datepickered = [];
	$(document).on('focus', '[type="date"]', function() {
		if( ! $(this).data('ident') || ! $.inArray($(this).data('ident'), datepickered))
		{
			$(this).datetimepicker({
				format: 'yyyy-mm-dd',
				minView: 2,
				autoclose : true,
				todayBtn : 'linked'
			});
			var unique = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
			$(this).data('ident', unique);
			datepickered.push(unique);
		}
	});
}