<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
<div class="control-group">
	<label class="control-label">제목</label>
	<div class="controls">
		<input type="text" name="subject" class="span8 required" value="<?php echo set_value('subject', $data['subject']); ?>" />
	</div>
</div>

<?php /*
<div class="control-group">
	<label class="control-label">링크</label>
	<div class="controls">
		<input type="text" name="link" class="span8 required" value="<?php echo set_value('link', $data['link']); ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">타겟</label>
	<div class="controls">
		<select name="target" class="span2">
			<option value="_blank" <?php echo set_select('target', '_blank', ($data['target'] == '_blank')); ?>>새창</option>
			<option value="_self" <?php echo set_select('target', '_self', ($data['target'] == '_self')); ?>>현재창</option>
		</select>
	</div>
</div>
*/ ?>

<div class="control-group">
	<label class="control-label">순서</label>
	<div class="controls">
		<input type="number" name="sort" class="span1 required" value="<?php echo set_value('sort', $data['sort']); ?>" />
	</div>
</div>

<div class="control-group">
	<label class="control-label">비주얼 이미지</label>
	<div class="controls">
		<button type="button" class="btn btn-small btn-success popupzone">파일첨부</button>
		<?php
		$params = array(
			'target_button' => '.popupzone',
			'pid' => $data['id'],
			'target' => 'popupzone',
			'limit' => 1,
			'ext_desc' => '이미지 파일',
			'ext' => '*.jpg; *.gif; *.png',
			'files' => ( ! empty($popupzone)) ? $popupzone : array()
		);
		echo CI::$APP->_uploader($params);
		?>
	</div>
</div>

	<div class="control-group">
		<label class="control-label">섬네일 이미지</label>
		<div class="controls">
			<button type="button" class="btn btn-small btn-success popupzone_thumb">파일첨부</button>
			<?php
			$params = array(
				'target_button' => '.popupzone_thumb',
				'pid' => $data['id'],
				'target' => 'popupzone_thumb',
				'limit' => 1,
				'ext_desc' => '이미지 파일',
				'ext' => '*.jpg; *.gif; *.png',
				'files' => ( ! empty($popupzone_thumb)) ? $popupzone_thumb : array()
			);
			echo CI::$APP->_uploader($params);
			?>
		</div>
	</div>

<div class="well well-centered">
	<button type="submit" class="btn btn-primary">변경내용 저장</button>
	<a class="btn" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
</div>
</fieldset>

</form>