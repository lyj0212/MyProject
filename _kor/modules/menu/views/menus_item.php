<ul>
	<?php foreach($data as $item) : ?>
	<?php $menu_item = modules::run('menu/manager/_construct_html', $item['menu_id'], $item['id']); ?>
	<li>
	<input type="hidden" name="item_id[]" value="<?php echo $item['id']; ?>" class="_item_key" />
	<input type="hidden" name="parent_id[]" value="<?php echo $item['parent_id']; ?>" class="_parent_key" />
	<?php if($item['index'] == 'Y') : ?><span class="label label-default">메인</span><?php endif; ?>
	<?php if($item['hidden'] == 'Y') : ?><span class="label label-default">숨김</span><?php endif; ?>
	<?php echo $item['title']; ?>
	<span class="side">
		<?php if($this->issuper == TRUE AND ! empty($menu_item)) : ?>
			<a class="btn btn-success btn-xs area_toggle">접기</a>
		<?php endif; ?>
		<?php if( ! empty($item['url'])) : ?>
			<a class="btn btn-primary btn-xs" href="<?php echo sprintf('/%s%s', ( ! empty($item['map'])) ? sprintf('%s/', $item['map']) : '', $item['url']); ?>" target="_bank">바로가기</a>
		<?php endif; ?>
		<?php if($item['grant_option'] == TRUE) : ?>
			<a class="btn btn-info btn-xs _popup" href="<?php echo $this->link->get(array('action'=>'grant', 'menu_id'=>$item['id'])); ?>" data-width="95%" data-maxwidth="1000">권한관리</a>
		<?php endif; ?>

		<?php if($this->issuper == TRUE) : ?>
			<a class="btn btn-warning btn-xs _popup" href="<?php echo $this->link->get(array('action'=>'add', 'id'=>$item['id'])); ?>" data-width="95%" data-maxwidth="1200">수정</a>
			<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
			<a class="btn btn-yellow btn-xs" href="<?php echo $this->link->get(array('action'=>'change_visible', 'id'=>$item['id'], 'visible'=>($item['hidden'] == 'Y') ? 'N' : 'Y')); ?>"><?php if($item['hidden'] == 'Y') : ?>메뉴 표시<?php else : ?>메뉴 숨김<?php endif; ?></a>
			<a class="btn btn-success btn-xs _popup" href="<?php echo $this->link->get(array('action'=>'add', 'menu_id'=>$item['menu_id'], 'parent_id'=>$item['id'])); ?>" data-width="95%" data-maxwidth="1200">하위에 메뉴 추가</a>
		<?php else : ?>
			<?php if($item['only_modify_super'] != 'Y') : ?>
				<a class="btn btn-warning btn-xs _popup" href="<?php echo $this->link->get(array('controller'=>'board_manager', 'action'=>'write', 'menu_id'=>$item['menu_id'], 'id'=>$item['id']), TRUE); ?>/<?php echo $item['param']; ?>" data-width="95%" data-maxwidth="800" data-reload="true">게시판 설정</a>
				<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
			<?php endif; ?>
			<a class="btn btn-yellow btn-xs" href="<?php echo $this->link->get(array('action'=>'change_visible', 'id'=>$item['id'], 'visible'=>($item['hidden'] == 'Y') ? 'N' : 'Y')); ?>"><?php if($item['hidden'] == 'Y') : ?>메뉴 표시<?php else : ?>메뉴 숨김<?php endif; ?></a>
			<a class="btn btn-success btn-xs _popup" href="<?php echo $this->link->get(array('controller'=>'board_manager', 'action'=>'write', 'menu_id'=>$item['menu_id'], 'parent_id'=>$item['id']), TRUE); ?>" data-width="95%" data-maxwidth="800" data-reload="true">하위에 게시판 추가</a>
		<?php endif; ?>
	</span>

	<?php echo $menu_item; ?>

	</li>
	<?php endforeach; ?>
</ul>