<?php
if(isset($this->html['css']))
{
	echo scriptForLayout($this->html, 'css');
}
?>
<script src="/_res/js/modernizr-2.6.2.min.js"></script>
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->