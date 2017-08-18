<?php echo validation_errors(); ?>
<?php echo form_open_multipart($this->link->get(array('action'=>'save_map')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
<div class="form-group">
	<label class="col-sm-2 control-label">맵 이름</label>
	<div class="col-sm-10 row">
		<div class="col-sm-4">
			<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">맵 구분자</label>
	<div class="col-sm-10 row">
		<div class="col-sm-4">
			<div class="input-group input-group-sm">
				<span class="input-group-addon"><?php echo str_replace(SITE.'/', '', site_url()); ?></span><input type="text" name="map" class="form-control" value="<?php echo set_value('map', $data['map']); ?>" /><span class="input-group-addon">/sub01...</span>
			</div>
		</div>
		<div class="col-sm-10">
			<p class="help-block"><span lang="en" class="label label-primary">Info</span> 사용하지 않을경우 입력하지 않아도 됩니다.</p>
			<p class="help-block"><span lang="en" class="label label-danger">Important</span> 알파벳 문자와 숫자, 밑줄, 대시만 포함할 수 있습니다.</p>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">관리자 전용</label>
	<div class="col-sm-10">
		<label class="checkbox-inline">
			<input type="checkbox" name="isadmin" value="Y" <?php echo set_checkbox('isadmin', 'Y', ($data['isadmin']=='Y')); ?>>
		</label>
		<p class="help-block"><span lang="en" class="label label-primary">Info</span> 체크할 경우 해당 맵은 최고 관리자를 제외하고 접속 할 수 없습니다.</p>
	</div>
</div>
</fieldset>

<div class="modal-footer">
	<a href="#" class="btn btn-default btn-sm close_modal">Close</a>
	<button type="submit" class="btn btn-primary btn-sm">변경내용 저장</button>
</div>

</form>