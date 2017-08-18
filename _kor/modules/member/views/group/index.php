<p class="text-info bg-info" style="padding:20px"><span lang="en" class="label label-danger">Important</span> 진행 중인 상태의 사업 공고만 접수하기 버튼이 표시됩니다.</p>
<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('class'=>'form-horizontal portlet sortablemenu')); ?>

<h2 class="h2">회원 그룹 관리</h2>
<ul>
	<?php foreach($data as $item) : ?>
	<li>
	<input type="hidden" name="item_id[]" value="<?php echo $item['id']; ?>" class="_item_key" />
	<?php echo $item['title']; ?>
	<span class="side">
		<a class="btn btn-warning btn-xs _popup" href="<?php echo $this->link->get(array('action'=>'add', 'id'=>$item['id'])); ?>">수정</a>
		<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete_group', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
	</span>
	</li>
	<?php endforeach; ?>
</ul>

<p class="btnArea righted">
	<button class="btn btn-primary btn-sm" type="submit">순서 저장</button>
	<a class="btn btn-success btn-sm _popup" href="<?php echo $this->link->get(array('action'=>'add')); ?>">그룹 추가</a>
</p>

</form>
