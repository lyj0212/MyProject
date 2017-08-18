<script type="text/javascript">
	var pagingOnLoad;
</script>

<div class="centered">
	<ul class="pagination">
		<?php if(!empty($page->first_page)) : ?><li class="pre"><a href="#none" onClick="$.post('<?php echo $page->first_page; ?>', function(data) { $('#<?php echo $update_field; ?>').html(data); pagingOnLoad() });" alt="<?php echo __('처음으로'); ?>" title="<?php echo __('처음으로'); ?>"><span class="ui-icon ui-icon-triangle-1-w"></span></a></li><?php endif; ?>

		<?php foreach($page->page_list as $item) : ?>
			<?php if($page->cur_page == $item['num']) : ?>
				<li class="active"><a href="#none" onClick="$.post('<?php echo $item['link']; ?>', function(data) { $('#<?php echo $update_field; ?>').html(data); pagingOnLoad(); });"><strong><?php echo $item['num'];?></strong></a></li>
			<?php else : ?>
				<li><a href="#none" onClick="$.post('<?php echo $item['link']; ?>', function(data) { $('#<?php echo $update_field; ?>').html(data); pagingOnLoad(); });"><?php echo $item['num'];?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if(!empty($page->last_page)) : ?><li class="next"><a href="#none" onClick="$.post('<?php echo $page->last_page; ?>', function(data) { $('#<?php echo $update_field; ?>').html(data); pagingOnLoad(); });" alt="<?php echo __('마지막으로'); ?>" title="<?php echo __('마지막으로'); ?>"><span class="ui-icon ui-icon-triangle-1-e"></span></a></li><?php endif; ?>
	</ul>
</div>