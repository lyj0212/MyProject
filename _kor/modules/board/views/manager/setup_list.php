<?php echo validation_errors(); ?>

<?php echo form_open($this->link->get(array('action'=>'save_list')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="tableid" value="<?php echo $data['tableid']; ?>" />
<input type="hidden" name="list" id="result_list" value="" />

<fieldset>
<div class="form-group input-inline">
	<span class="col-sm-5">
		<select class="form-control select_list" multiple="multiple" size="8">
			<?php foreach($this->list_object as $value=>$field) : ?>
				<?php if( ! in_array($value, $data['list'])) : ?>
					<option value="<?php echo $value; ?>"><?php echo $field['name']; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
	</span>

	<span class="col-sm-1 centered hidden-xs">
		<a class="btn btn-xs btn-default arrow-right" href="#"><i class="glyphicon glyphicon-arrow-right"></i></a><br /><a class="btn btn-xs btn-default arrow-left" href="#"><i class="glyphicon glyphicon-arrow-left"></i></a>
	</span>

	<span class="col-sm-1 centered visible-xs">
		<a class="btn btn-xs btn-default arrow-right" href="#"><i class="glyphicon glyphicon-arrow-down"></i> 추가</a>
		<a class="btn btn-xs btn-default arrow-left" href="#"><i class="glyphicon glyphicon-arrow-up"></i> 삭제</a>
	</span>

	<span class="col-sm-5">
		<select class="form-control select_target" multiple="multiple" size="8">
			<?php foreach($data['list'] as $item) : ?>
				<option value="<?php echo $item; ?>"><?php echo $this->list_object[$item]['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</span>
	<span class="col-sm-1 centered hidden-xs">
		<a class="btn btn-xs btn-default arrow-up" href="#"><i class="glyphicon glyphicon-arrow-up"></i></a><br /><a class="btn btn-xs btn-default arrow-down" href="#"><i class="glyphicon glyphicon-arrow-down"></i></a>
	</span>
	<span class="col-sm-1 centered visible-xs">
		<a class="btn btn-xs btn-default arrow-up" href="#"><i class="glyphicon glyphicon-arrow-up"></i> 위로</a>
		<a class="btn btn-xs btn-default arrow-down" href="#"><i class="glyphicon glyphicon-arrow-down"></i> 아래로</a>
	</span>
</div>

<div class="form-group hidden-xs">
	<p class="help-block"><span lang="en" class="label label-info">Information</span> Ctrl 키를 누른 상태에서 마우스 클릭시 다중 선택이 가능합니다.</p>
</div>

<div class="well well-centered">
	<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
	<?php if($this->router->fetch_module() != 'menu' AND $this->router->fetch_class() != 'board_manager') : ?>
		<a class="btn btn-sm btn-default" href="<?php echo $this->link->get(array('action'=>'index', 'tableid'=>NULL, 'active'=>NULL)); ?>">돌아가기</a>
	<?php endif; ?>
</div>
</fieldset>

</form>
