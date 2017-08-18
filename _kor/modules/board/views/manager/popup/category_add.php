<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'category_save_item')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="tableid" value="<?php echo set_value('tableid', $data['tableid']); ?>" />
<input type="hidden" name="parent_id" value="<?php echo set_value('parent_id', $data['parent_id']); ?>" />

<fieldset>
<div class="form-group">
	<label class="col-xs-2 control-label">카테고리 명</label>
	<div class="col-xs-7">
		<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
	</div>
</div>
</fieldset>

<div class="modal-footer">
	<a href="#" class="btn btn-default btn-sm close_modal">닫기</a>
	<button type="submit" class="btn btn-primary btn-sm">변경내용 저장</button>
</div>

</form>