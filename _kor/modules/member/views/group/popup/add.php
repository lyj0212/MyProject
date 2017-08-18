<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save_group')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
<div class="form-group">
	<label class="col-xs-2 control-label">그룹명</label>
	<div class="col-xs-10">
		<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
	</div>
</div>
<div class="form-group">
	<label class="col-xs-2 control-label">관리자</label>
	<div class="col-xs-10">
		<label class="checkbox-inline">
			<input type="checkbox" name="admin" value="Y" <?php echo set_checkbox('admin', 'Y', ($data['admin']=='Y')); ?>> 관리자
		</label>
		<p class="help-block"><span lang="en" class="label label-danger">Important</span> 관리자 그룹은 개별 권한관리와 상관없이 모든권한을 가집니다.</p>
	</div>
</div>
</fieldset>

<div class="modal-footer">
	<a href="#" class="btn btn-sm btn-default close_modal">닫기</a>
	<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
</div>

</form>
