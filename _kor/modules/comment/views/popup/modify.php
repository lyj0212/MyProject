<?php echo form_open($this->link->get(array('action'=>'comment_save')), array('id'=>'writeForm')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="pid" value="<?php echo set_value('pid', $data['pid']); ?>" />
<input type="hidden" name="redirect" value="<?php echo encode_url(); ?>" />
<input type="hidden" name="prefix" value="<?php echo $this->menu->current['url']; ?>" />

<div class="well">
	<?php if($this->account->is_logged() == FALSE) : ?>
	<div class="comment_writer">
		<input type="text" name="name" class="span2" placeholder="이름" value="<?php echo set_value('name', $data['name']); ?>" />
		<input type="password" name="passwd" class="span2" placeholder="비밀번호" />
	</div>
	<?php endif; ?>
	<div class="comment_box">
		<div class="comment_input">
			<textarea name="contents"><?php echo set_value('contents', $data['contents']); ?></textarea>
		</div>
		<div class="comment_btn">
			<input type="submit" class="btn" value="수정" />
		</div>
	</div>

	<button type="button" class="btn btn-small attach-file">파일첨부</button>
	<?php
	$params = array(
		'target_button' => '.attach-file',
		'pid' => $data['id'],
		'files' => ( ! empty($files)) ? $files : array()
	);
	echo CI::$APP->_uploader($params);
	?>
</div>
</form>
