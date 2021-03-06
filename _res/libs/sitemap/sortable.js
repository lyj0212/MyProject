
/* NHN (developers@xpressengine.com) */
$.support.touch = 'ontouchend' in document;

/* NHN (developers@xpressengine.com) */
jQuery(function($){

var dragging = false, $holder  = $('<li class="placeholder">');

if($.support.touch)
{
	$('.sortablemenu').before('<div class="touch_navi"><form class="form-inline"><span lang="en" class="label label-warning">Info</span> <label class="checkbox" onclick=""><input type="checkbox" id="inittouch" /> 터치허용 (순서 변경시 체크해 주세요.)</label></form></div>');
}

$('.sortablemenu').delegate('li:not(.placeholder)', {
		'mousedown.st touchstart' : function(event) {

			if($.support.touch && $('#inittouch').prop('checked') == true)
			{
				event.preventDefault();
				var touch = event.originalEvent.changedTouches[0];
			}
			else
			{
				var touch = event;
				if(event.which != 1) {
					$(document).unbind('mousemove.st mouseup.st touchmove touchend');
					return;
				}
			}

			var $this, $uls, $ul, width, height, offset, position, offsets, i, dropzone, wrapper='';

			if($(event.target).is('a,input,label,textarea')) return;

			dragging = true;

			$this  = $(this);
			height = $this.height();
			width  = $this.width();
			$uls   = $this.parentsUntil('.sortablemenu').filter('ul');
			$ul    = $uls.eq(-1);

			$ul.css('position', 'relative');

			position = {x:touch.pageX, y:touch.pageY};
			offset   = getOffset(this, $ul.get(0));

			$clone = $this.clone(true).attr('target', true);

			for(i=$uls.length-1; i; i--) {
				$clone = $clone.wrap('<li><ul /></li>').parent().parent();
			}

			// get offsets of all list-item elements
			offsets = [];
			$ul.find('li').each(function(idx) {
				if($this[0] === this || $this.has(this).length) return true;

				var o = getOffset(this, $ul.get(0));
				offsets.push({top:o.top, bottom:o.top+32, item:this});
			});

			// Remove unnecessary elements from the clone, set class name and styles.
			// Append it to the list
			$clone
				.find('.side,input').remove().end()
				.addClass('draggable')
				.css({
					position: 'absolute',
					opacity : .6,
					width   : width,
					height  : height,
					left    : offset.left,
					top     : offset.top,
					zIndex  : 100
				})
				.appendTo($ul.eq(0));

			// Set a place holder
			$holder
				.css({
					position:'absolute',
					opacity : .6,
					width   : width,
					height  : '5px',
					left    : offset.left,
					top     : offset.top,
					zIndex  :99
				})
				.appendTo($ul.eq(0));

			$this.css('opacity', .6);

			$(document)
				.unbind('mousemove.st mouseup.st touchmove touchend')
				.bind('mousemove.st touchmove', function(event) {

					if($.support.touch && $('#inittouch').prop('checked') == true)
					{
						event.preventDefault();
						var touch = event.originalEvent.changedTouches[0];
					}
					else
					{
						var touch = event;
					}

					var diff, nTop, item, i, c, o, t;

					dropzone = null;

					diff = {x:position.x-touch.pageX, y:position.y-touch.pageY};
					nTop = offset.top - diff.y;

					for(i=0,c=offsets.length; i < c; i++) {
						t = nTop;
						o = offsets[i];

						if(i == 0 && t < o.top) t = o.top;
						if(i == c-1 && t > o.bottom) t = o.bottom;

						if(o.top <= t && o.bottom >= t) {
							dropzone = {element:o.item, state:setHolder(o,t)};
							break;
						}
					}

					$clone.css({top:nTop});
				})
				.bind('mouseup.st touchend', function(event) {
					var $dropzone, $li;

					dragging = false;

					$(document).unbind('mousemove.st mouseup.st');
					$this.css('opacity', '');
					$clone.remove();
					$holder.remove();

					// dummy list item for animation
					$li = $('<li />').height($this.height());

					if(!dropzone) return;
					$dropzone = $(dropzone.element);

					$this.before($li);

					$dropzone[dropzone.state]($this.hide());

					$this.slideDown(100, function(){ $this.removeClass('active') });
					$li.slideUp(100, function(){ var $par = $li.parent(); $li.remove(); if(!$par.children('li').length) $par.remove()  });

					// trigger 'dropped.st' event
					$this.trigger('dropped.st');
				});

			return false;
		},
		'mouseover.st' : function() {
			if(!dragging) $(this).addClass('active');
			return false;
		},
		'mouseout.st' : function() {
			if(!dragging) $(this).removeClass('active');
			return false;
		}
	})
	.find('li')
		.prepend('<button type="button" class="moveTo">Move to</button>')
		.append('<span class="vr"></span><span class="hr"></span>')
		.find('input:text')
			.focus(function(){
				var $this = $(this), $label = $this.prev('label'), $par = $this.parent();

				$this.width($par.width() - (parseInt($par.css('text-indent'))||0) - $this.next('.side').width() - 60).css('opacity', '');
				$label.hide();
			})
			.blur(function(){
				var $this = $(this), $label = $this.prev('label'), val = $this.val();

				$this.width(0).css('opacity', 0);
				$label.removeClass('no-text').empty().text(val).show();
				if(!val) $label.addClass('no-text').text('---');
			})
			.each(function(i,input){
				var $this = $(this), id='sitemap-id-'+i;

				$this
					.attr('id', id)
					.css({width:0,opacity:0,overflow:'hidden'})
					.before('<label />')
						.prev('label')
						.attr('for', id)
						.text($this.val());
			})
		.end()
	.end()

function getOffset(elem, offsetParent) {
	var top = 0, left = 0;

	while(elem && elem != offsetParent) {
		top  += elem.offsetTop;
		left += elem.offsetLeft;

		elem = elem.offsetParent;
	}

	return {top:top, left:left};
}

function setHolder(info, yPos) {
	if(Math.abs(info.top-yPos) <= 3) {
		$holder.css({top:info.top-3,height:'5px'});
		return 'before';
	} else {
		$holder.css({top:info.bottom-3,height:'5px'});
		return 'after';
	}
}

});
