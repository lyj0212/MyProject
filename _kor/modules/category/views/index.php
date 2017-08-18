<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('class'=>'form-horizontal portlet sortablemenu')); ?>
<input type="hidden" name="type" value="<?php echo $data['type']; ?>" />

<h2 class="h2">항목 설정</h2>

<?php if($category_item = modules::run('category/manager/_construct_category', $data['type'])) : ?>
	<?php echo $category_item; ?>
<?php else : ?>
<div class="centered" style="padding:10px 0">등록된 카테고리가 없습니다.</div>
<?php endif; ?>

<p class="btnArea righted">
	<button class="btn btn-primary btn-sm" type="submit">변경내용 저장</button>
	<a class="btn btn-success btn-sm _popup" href="<?php echo $this->link->get(array('action'=>'category_add', 'type'=>$data['type'])); ?>" data-width="100%" data-maxwidth="100%">카테고리 추가</a>
	<a href="#" class="btn btn-default btn-sm close_modal">닫기</a>
</p>

</form>
