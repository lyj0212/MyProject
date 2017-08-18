<script>
// 팝업
<?php if( ! empty($popup['data'])) : ?>
	<?php foreach($popup['data'] as $item) : ?>
		<?php if( ! get_cookie(sprintf('popup%s', $item['id']))) : ?>
			window.open('<?php echo $this->link->get(array('module'=>'popup', 'controller'=>'popup', 'action'=>'view', 'id'=>$item['id']), TRUE); ?>', 'popup<?php echo $item['id']; ?>', 'left=<?php echo $item['left']; ?>, top=<?php echo $item['top']; ?>, width=<?php echo $item['width']; ?>, height=<?php echo $item['height']+39; ?>, scrollbars=no');
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
</script>
