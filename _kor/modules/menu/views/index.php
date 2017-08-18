<?php echo validation_errors(); ?>

<?php foreach($data as $item) : ?>
<?php echo form_open($this->link->get(array('action'=>'save_area')), array('class'=>'form-horizontal portlet sortablemenu')); ?>
<input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>" />

<h2 class="h2"><input name="title" value="<?php echo $item['title']; ?>" /></h2>

<?php if($menu_item = modules::run('menu/manager/_construct_html', $item['id'])) : ?>
	<?php echo $menu_item; ?>
<?php else : ?>
<div class="centered" style="padding:10px 0">등록된 맵이 없습니다.</div>
<?php endif; ?>

<p class="btnArea righted">
	<button class="btn btn-primary btn-sm" type="submit">순서 저장</button>
	<?php if($this->issuper == TRUE) : ?>
		<a class="btn btn-warning btn-sm _popup" href="<?php echo $this->link->get(array('action'=>'add_map', 'id'=>$item['id'])); ?>" data-width="95%" data-maxwidth="1200">맵 수정</a>
		<a class="btn btn-danger btn-sm" href="<?php echo $this->link->get(array('action'=>'delete_map', 'menu_id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">맵 삭제</a>
		<a class="btn btn-success btn-sm _popup" href="<?php echo $this->link->get(array('action'=>'add', 'menu_id'=>$item['id'])); ?>" data-width="95%" data-maxwidth="1200">메뉴 추가</a>
	<?php endif; ?>
</p>

</form>
<?php endforeach; ?>

<?php if($this->issuper == TRUE) : ?>
<a class="btn btn-lg btn-block btn-primary _popup" href="<?php echo $this->link->get(array('action'=>'add_map')); ?>" data-width="95%" data-maxwidth="1200">새로운 맵 추가</a>
<?php endif; ?>