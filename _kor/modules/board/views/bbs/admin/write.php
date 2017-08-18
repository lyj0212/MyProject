<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />

<fieldset>
	<?php if(isset($custom_alarm) AND $custom_alarm == 'Y') : ?>
	<div class="bbs_alarm">
		<label class="checkbox-inline"><input type="checkbox" name="alarm" value="Y"<?php if(empty($data['id'])) : ?> checked="checked"<?php endif; ?> /> 알리기<?php if( ! empty($custom_alarm_info)) : ?><?php echo $custom_alarm_info; ?><?php endif; ?></label>
	</div>
	<?php endif; ?>

	<div class="row clearfix">
		<?php if($this->setup['use_category'] == 'Y') : ?>
			<div class="col-md-2 col-sm-3 pdr5">
				<select name="category" class="form-control input-sm">
					<option value="">-- 분류 --</option>
					<?php foreach($category['data'] as $item) : ?>
						<option value="<?php echo $item['id']; ?>" <?php echo set_select('category', $item['id'], ($item['id']==$data['category'])); ?>><?php echo $item['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="mt10 visible-xs"></div>
		<?php endif; ?>
		<div class="<?php if($this->setup['use_category'] == 'Y') : ?>col-md-10 col-sm-9 pdl0<?php else : ?>col-lg-12<?php endif; ?>">
			<input type="text" name="subject" class="form-control input-sm required" value="<?php echo set_value('subject', $data['subject']); ?>" placeholder="제목" />
		</div>
	</div>

	<?php if($this->auth->admin() == TRUE) : ?>
		<div class="mt20">
			<label class="checkbox-inline">
				<input type="checkbox" name="isnotice" value="Y" <?php echo set_checkbox('isnotice', 'Y', ($data['isnotice']=='Y')); ?>> 공지사항
			</label>
		</div>
	<?php endif; ?>

	<div class="mt20">
		<?php
		$params = array(
			'input' => 'contents',
			'pid' => $data['id']
		);
		echo CI::$APP->_editor($params);
		?>
	</div>

	<div class="well well-centered">
		<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
		<a class="btn btn-sm btn-default" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
	</div>
</fieldset>

</form>