<div id="cLoadingOverlay"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
	window.jQuery || document.write('<script src="/_res/libs/jquery/jquery.min.js"><\/script>');
</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
<script>
	window.jQuery.ui || document.write('<script src="/_res/libs/jquery/jquery-ui.min.js"><\/script>');
</script>

<!--[if lte IE 7]>
<script src="/_res/js/json2.js"></script>
<![endif]-->

<?php if($this->account->is_logged() != FALSE OR $this->router->fetch_module() == 'crond') : ?>
	<script>
		var messenger_data = {
			'id' : '<?php echo $this->account->get('id'); ?>',
			'name' : '<?php echo form_prep($this->account->get('fullname')); ?>'
		};
		var garbage = [{blank:'blank'}];
	</script>
	<script src="/_res/js/sio.js?v=1.2.0"></script>
	<script src="/_res/js/messenger.js"></script>
<?php endif; ?>

<script>
	var highlight_url = '<?php echo $this->link->get(array('action'=>'highlight')); ?>';
	var refreshComment_url = '<?php echo $this->link->get(array('action'=>'refreshComment')); ?>';
	var token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var token_hash = '<?php echo $this->security->get_csrf_hash(); ?>';
	var is_mobile = 'N';
	if(Modernizr.mq('(max-width:991px)'))
	{
		is_mobile = 'Y';
	}
</script>

<?php if($this->menu->current['isadmin'] == 'Y') : ?>
<script src="/manager/js/manager.js"></script>
<?php endif; ?>
<script src="/_res/js/jquery.chained.min.js"></script>

<?php
if(isset($this->html['js']))
{
	echo scriptForLayout($this->html);
}
?>
<?php echo $module_script; ?>

<script type="text/javascript" src="/js/jquery.bxslider.min.js"></script>