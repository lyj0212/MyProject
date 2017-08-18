<script>
var editor = CodeMirror.fromTextArea($('#code').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

var editor1 = CodeMirror.fromTextArea($('#code1').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor1.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor1.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

var editor2 = CodeMirror.fromTextArea($('#code2').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor2.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor2.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

var editor3 = CodeMirror.fromTextArea($('#code3').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor3.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor3.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

var editor4 = CodeMirror.fromTextArea($('#code4').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor4.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor4.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

var editor5 = CodeMirror.fromTextArea($('#code5').get(0), {
	lineNumbers: true,
	matchBrackets: true,
	mode: "application/x-httpd-php",
	indentUnit: 2,
	indentWithTabs: true,
	enterMode: "keep",
	tabMode: "shift",
	onFocus: function() {
		$(editor5.getWrapperElement()).addClass('CodeMirrorFocus');
	},
	onBlur: function() {
		$(editor5.getWrapperElement()).removeClass('CodeMirrorFocus');
	}
});

editor1.setSize(null, 150);
editor2.setSize(null, 150);
editor3.setSize(null, 150);
editor4.setSize(null, 150);

function resizeCMI()
{
	var width = $(editor.getWrapperElement()).closest('form').width();
	editor.setSize(width);
	editor1.setSize(width);
	editor2.setSize(width);
	editor3.setSize(width);
	editor4.setSize(width);
	editor5.setSize(width);
}

$(function() {
	resizeCMI();
});

$(window).resize(function() {
	resizeCMI();
});
</script>
