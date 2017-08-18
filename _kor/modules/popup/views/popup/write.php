<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />

<fieldset>
<div class="control-group">
	<label class="control-label">제목</label>
	<div class="controls">
		<input type="text" name="subject" class="span8 required" value="<?php echo set_value('subject', $data['subject']); ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">게시기간</label>
	<div class="controls">
		<input type="date" name="start_date" class="span2 required" value="<?php echo set_value('start_date', $data['start_date']); ?>" />
		~
		<input type="date" name="end_date" class="span2 required" value="<?php echo set_value('end_date', $data['end_date']); ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">크기</label>
	<div class="controls">
		<input type="text" name="width" class="span2 required" value="<?php echo set_value('width', $data['width']); ?>" /> px
		*
		<input type="text" name="height" class="span2 required" value="<?php echo set_value('height', $data['height']); ?>" /> px
		<p class="help-block"><span class="label label-info">Info</span> width * height</p>
	</div>
</div>

<div class="control-group">
	<label class="control-label">위치</label>
	<div class="controls">
		<input type="text" name="top" class="span2 required" value="<?php echo set_value('top', $data['top']); ?>" /> px
		*
		<input type="text" name="left" class="span2 required" value="<?php echo set_value('left', $data['left']); ?>" /> px
		<p class="help-block"><span class="label label-info">Info</span> top * left</p>
	</div>
</div>

<div class="control-group">
	<label class="control-label">내용</label>
	<div class="controls">
		<?php
		$params = array(
			'form' => 'writeForm',
			'input' => 'contents',
			'pid' => $data['id']
		);
		echo CI::$APP->_editor($params);
		?>
	</div>
</div>

<div class="well well-centered">
	<button type="submit" class="btn btn-primary">변경내용 저장</button>
	<a class="btn" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
</div>
</fieldset>

</form>