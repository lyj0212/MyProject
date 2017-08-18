<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save_notification')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
	<?php foreach($notification_option as $option) : ?>
		<?php
		$attributes = $option['@attributes'];
		$action = str_replace(array('.', '|'), array('/', ':'), $attributes['action']);
		?>
		<div class="form-group">
			<label class="col-xs-3 control-label"><?php echo $attributes['title']; ?></label>
			<div class="col-xs-9">

				<div class="mb5">
					<span class="label label-primary" style="margin-right:10px">그룹별</span>
					<select name="group[<?php echo $action; ?>][]" class="form-control chosen" multiple="multiple" size="1" style="display:inline;width:85%" data-placeholder="선택한 그룹의 모든 회원이 알림을 수신하게 됩니다.">
						<?php foreach($group['data'] as $key => $item) : ?>
							<?php if($item['admin'] == 'Y') continue; // 그냥 최고관리자는 표시안되도록 한다 2014-04-30 ?>
							<option value="<?php echo $item['id']; ?>"<?php if(isset($notification[$attributes['action']][$item['id']]) OR $item['admin'] == 'Y') : ?> selected="selected"<?php endif; ?><?php if($item['admin'] == 'Y') : ?> disabled="disabled"<?php endif; ?>><?php echo $item['title']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mb5">
					<span class="label label-warning" style="margin-right:10px">부서별</span>
					<select name="busu[<?php echo $action; ?>][]" class="form-control chosen" multiple="multiple" size="1" style="display:inline;width:85%" data-placeholder="선택한 부서의 모든 회원이 알림을 수신하게 됩니다.">
						<?php foreach($department['data'] as $key => $item) : ?>
							<option value="<?php echo $item['id']; ?>"<?php if(isset($notification[$attributes['action']][$item['id']])) : ?> selected="selected"<?php endif; ?>><?php echo $item['title']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mb10">
					<span class="label label-info" style="margin-right:10px">회원별</span>
					<select name="member[<?php echo $action; ?>][]" class="form-control chosen" multiple="multiple" size="1" style="display:inline;width:85%" data-placeholder="선택한 회원만 알림을 수신합니다.">
						<?php foreach($department['data'] as $item) : ?>
							<?php if( ! empty($member[$item['id']])) : ?>
								<optgroup label="<?php echo $item['title']; ?>">
									<?php foreach($member[$item['id']] as $user) : ?>
										<option value="<?php echo $user['id']; ?>"<?php if(isset($notification[$attributes['action']][$user['id']])) : ?> selected="selected"<?php endif; ?><?php if($this->account->get('admin') != 'Y' AND $this->account->get('id') == $user['id'] AND $attributes['action']=='__ALL__') : ?> disabled="disabled"<?php endif; ?>><?php echo $user['name']; ?> <?php echo $user['position_title']; ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="">
					<label class="control-label">알림 메세지</label>
					<input type="hidden" name="substitution[<?php echo $action; ?>]" value="<?php if( ! empty($attributes['possible_field'])) : ?><?php echo $attributes['possible_field']; ?><?php endif; ?>" />
					<input type="text" class="form-control input-sm" name="message[<?php echo $action; ?>]" value="<?php if(isset($notification[$attributes['action']]['message'])) : ?><?php echo $notification[$attributes['action']]['message']; ?><?php else : ?><?php echo $attributes['default_message']; ?><?php endif; ?>" />
					<?php if( ! empty($attributes['possible_field'])) : ?>
						<p class="help-block"><span lang="en" class="label label-success">Information 1</span> 사용 가능한 변수 : <?php echo preg_replace('/\|[a-z_]+/i', '', $attributes['possible_field']); ?></p>
						<p class="help-block"><span lang="en" class="label label-success">Information 2</span> 예 : {글쓴이} 님이 새로운 글을 등록했습니다.</p>
					<?php endif; ?>
				</div>

			</div>
		</div>
	<?php endforeach; ?>
</fieldset>

<div class="modal-footer">
	<a href="#" class="btn btn-default btn-sm close_modal">닫기</a>
	<button type="submit" class="btn btn-primary btn-sm">변경내용 저장</button>
</div>

</form>

<br />
<br />
<br />
<br />