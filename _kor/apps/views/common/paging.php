	<div class="table_paginate">
		<ul class="pagination">
			<?php if(!empty($page->first_page)) : ?>
			<li class="first"><a href="<?php echo $page->first_page;?>" class="icon-angle-double-left"><span class="hide">맨 처음</span></a></li>
			<?php endif; ?>
			<?php if(!empty($page->prev_page)) : ?>
			<li class="previous"><a href="<?php echo $page->prev_page;?>" class="icon-angle-left"><span class="hide">이전</span></a></li>
			<?php endif; ?>
			<?php foreach($page->page_list as $item) : ?>
			<li<?php if($page->cur_page == $item['num']) : ?> class="active"<?php endif; ?>><a href="<?php echo $item['link'];?>"><?php echo $item['num'];?></a></li>
			<?php endforeach; ?>
			<?php if(!empty($page->next_page)) : ?>
			<li class="next"><a href="<?php echo $page->next_page;?>" class="icon-angle-right"><span class="hide">다음</span></a></li>
			<?php endif; ?>
			<?php if(!empty($page->last_page)) : ?>
			<li class="last"><a href="<?php echo $page->last_page;?>" class="icon-angle-double-right"><span class="hide">맨 마지막</span></a></li>
			<?php endif; ?>
		</ul>
	</div>