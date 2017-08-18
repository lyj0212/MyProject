<?php echo validation_errors(); ?>
<div class="table_wrap iptForm">
	<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm')); ?>
	<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
	<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />
	<legend>입력 폼</legend>
	<fieldset>
		<table class="bbs_table">
		<caption>원스톱 입력란</caption>
		<colgroup>
			<col width="160px" /><col />
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="subject">제목</label></th>
			<td>
				<?php if(isset($custom_alarm) AND $custom_alarm == 'Y') : ?>
				<div class="bbs_alarm">
					<label class="checkbox-inline"><input type="checkbox" name="alarm" value="Y"<?php if(empty($data['id'])) : ?> checked="checked"<?php endif; ?> /> 알리기<?php if( ! empty($custom_alarm_info)) : ?><?php echo $custom_alarm_info; ?><?php endif; ?></label>
				</div>
				<?php endif; ?>

				<div class="<?php if($this->setup['use_category'] == 'Y') : ?>bbs_titleipt use_category<?php else : ?>bbs_titleipt<?php endif; ?>">
					<?php if($this->setup['use_category'] == 'Y') : ?>
					<div class="select_box">
						<label for="category">분류</label>
						<select id="category" name="category" class="info_select">
						<option value="">-- 분류 --</option>
						<?php foreach($category['data'] as $item) : ?>
						<option value="<?php echo $item['id']; ?>" <?php echo set_select('category', $item['id'], ($item['id']==$data['category'])); ?>><?php echo $item['title']; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
					<div><input type="text" id="subject" name="subject" class="form-control input-sm required" value="<?php echo set_value('subject', $data['subject']); ?>" placeholder="제목 입력" title="제목 입력" /></div>
				</div>
			</td>
		</tr>
		<?php if($this->auth->admin() == TRUE && $this->link->get_segment('tableid', FALSE) != 'photo' && $this->link->get_segment('tableid', FALSE) != 'order') : ?>
		<tr>
			<th scope="row">공지글 여부</th>
			<td>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="isnotice" value="Y" <?php echo set_checkbox('isnotice', 'Y', ($data['isnotice']=='Y')); ?> />
						<i class="iCheck">체크</i>
						<span>공지사항</span>
					</label>
				</div>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td colspan="2" class="editor">
				<?php
				$params = array(
					'input' => 'contents',
					'pid' => $data['id']
				);
				echo CI::$APP->_editor($params);
				?>
			</td>
		</tr>
		</tbody>
		</table>

		<div class="table_footer">
			<p class="btn_wrap btn_sm text-right">
				<button type="submit" class="btn btn-sm btn-modify"><span class="icon-edit"><?php if($this->link->get_segment('tableid', FALSE) == 'order') : ?>발주신청<?php else : ?> 저장<?php endif; ?></span></button>
				<a class="btn btn-sm btn-list" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>"><span class="icon-menu">목록</span></a>
			</p>
		</div>
	</fieldset>
	</form>
</div>