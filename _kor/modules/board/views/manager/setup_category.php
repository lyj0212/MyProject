<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save_category')), array('class'=>'form-horizontal portlet sortablemenu')); ?>
<input type="hidden" name="tableid" value="<?php echo $data['tableid']; ?>" />

<h2 class="h2">카테고리 설정</h2>

<?php if($category_item = modules::run(sprintf('%s/%s/_construct_category', $this->router->fetch_module(), $this->router->fetch_class()), $data['tableid'])) : ?>
	<?php echo $category_item; ?>
<?php else : ?>
<div class="centered" style="padding:10px 0">등록된 카테고리가 없습니다.</div>
<?php endif; ?>

<p class="btnArea centered">
	<button class="btn btn-primary btn-sm" type="submit">순서 저장</button>
	<a class="btn btn-success btn-sm _popup" href="<?php echo $this->link->get(array('action'=>'category_add', 'tableid'=>$data['tableid'], 'id'=>NULL)); ?>">카테고리 추가</a>
	<?php if($this->router->fetch_module() != 'menu' AND $this->router->fetch_class() != 'board_manager') : ?>
		<a class="btn btn-default btn-sm" href="<?php echo $this->link->get(array('action'=>'index', 'tableid'=>NULL, 'active'=>NULL)); ?>">돌아가기</a>
	<?php endif; ?>
</p>

</form>
