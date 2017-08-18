<?php echo form_open($this->link->get(array('action'=>'comment_save')), array('id'=>'writeForm')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="pid" value="<?php echo set_value('pid', $data['pid']); ?>" />
<input type="hidden" name="tid" value="<?php echo set_value('tid', $data['tid']); ?>" />
<input type="hidden" name="redirect" value="<?php echo encode_url(); ?>" />
<input type="hidden" name="prefix" value="<?php echo $this->menu->current['url']; ?>" />
<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />

<div class="well">
	<div class="comment_box">
		<div class="comment_input">
			<?php
			$params = array(
				'input' => 'contents',
				'skin' => 'mini',
				'pid' => $data['id']
			);
			echo CI::$APP->_editor($params);
			?>
		</div>
		<div class="comment_btn">
			<input type="submit" class="btn btn-info btn-sm" value="등록" />
		</div>
	</div>
</div>

</form>