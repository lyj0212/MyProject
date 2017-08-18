<ul>
	<?php foreach($data as $item) : ?>
	<li>
	<input type="hidden" name="item_id[]" value="<?php echo $item['id']; ?>" class="_item_key" />
	<input type="hidden" name="parent_id[]" value="<?php echo $item['parent_id']; ?>" class="_parent_key" />
	<?php echo $item['title']; ?>
	<span class="side">
		<a class="btn btn-primary btn-xs iframe" href="<?php echo $this->link->get(array('action'=>'category_add', 'id'=>$item['id'])); ?>">수정</a>
		<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'category_delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
		<a class="btn btn-success btn-xs iframe" href="<?php echo $this->link->get(array('action'=>'category_add', 'type'=>$item['type'], 'parent_id'=>$item['id'])); ?>">추가</a>
	</span>
	<?php echo modules::run('category/manager/_construct_category', $item['type'], $item['id']); ?>
	</li>
	<?php endforeach; ?>
</ul>
