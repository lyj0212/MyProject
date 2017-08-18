<?php echo validation_errors(); ?>

<?php echo form_open($this->link->get(array('action'=>'save_default')), array('class'=>'')); ?>
<?php if($this->tableid) : ?>
<input type="hidden" name="mode" value="modify" />
<?php endif; ?>
<fieldset>

<?php /*
프로젝트 게시판 생성 관리
*/ ?>
<?php if($this->router->fetch_class() != 'board_manager') : ?>
<div class="form-group">
	<label >게시판 아이디</label>
	<?php if(empty($data['tableid'])) : ?>
		<input type="text" name="tableid" class="form-control input-sm" value="<?php echo set_value('tableid', $data['tableid']); ?>" style="ime-mode:disabled;" />
	<?php else : ?>
		<span class="form-control input-sm uneditable-input uneditable-tableid" disabled><?php echo $data['tableid']; ?></span>
		<input type="hidden" name="tableid" value="<?php echo $data['tableid']; ?>" />
	<?php endif; ?>
	<p class="help-block hidden-xs">notice, qna와 같은 게시판 고유 이름 입니다.</p>
	<p class="help-block"><span lang="en" class="label label-danger">Important</span> 영문, 숫자 조합만 입력 가능합니다.</p>
</div>
<?php else : ?>
	<input type="hidden" name="tableid" value="<?php echo $data['tableid']; ?>" />
<?php endif; ?>

<div class="form-group">
	<label >게시판 이름</label>
	<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
	<p class="help-block hidden-xs">공지사항, 질의응답과 같은 실제 표시되는 이름 입니다.</p>
</div>
<div class="form-group">
	<label >게시판 타입</label>
	<select name="type" class="form-control input-sm">
		<option value="list" <?php echo set_select('type', 'list', ($data['type']=='list')); ?>>리스트형</option>
		<option value="gallery" <?php echo set_select('type', 'gallery', ($data['type']=='gallery')); ?>>갤러리 타입1</option>
		<option value="gallery2" <?php echo set_select('type', 'gallery2', ($data['type']=='gallery2')); ?>>갤러리 타입2</option>
	</select>
</div>
<div class="form-group">
	<label >리스트 글 수</label>
	<input type="number" name="limit" class="form-control input-sm" value="<?php echo set_value('limit', $data['limit']); ?>" />
	<p class="help-block hidden-xs">한페이지에 출력되는 글의 갯수 입니다.</p>
	<p class="help-block"><span lang="en" class="label label-danger">Important</span> 숫자만 입력 가능합니다.</p>
</div>
<div class="form-group">
	<label >카테고리 사용</label>
	<div class="checkbox">
		<label>
			<input type="checkbox" name="use_category" value="Y" <?php echo set_checkbox('use_category', 'Y', ($data['use_category']=='Y')); ?> /> 사용
		</label>
	</div>
</div>

<div class="well well-centered">
	<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
	<?php if($this->router->fetch_module() != 'menu' AND $this->router->fetch_class() != 'board_manager') : ?>
		<a class="btn btn-sm btn-default" href="<?php echo $this->link->get(array('action'=>'index', 'tableid'=>NULL, 'active'=>NULL)); ?>">돌아가기</a>
	<?php endif; ?>
</div>
</fieldset>

</form>
