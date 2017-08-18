<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save_grant')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
<?php foreach($grant_option as $grant) : ?>
<div class="form-group">
	<label class="col-xs-2 control-label"><?php echo $grant['@attributes']['title']; ?></label>
	<div class="col-xs-10">

		<div class="mb5">
			<span class="label label-primary" style="margin-right:10px">그룹별</span>
			<select name="group[<?php echo str_replace(array('.', '|'), array('/', ':'), $grant['@attributes']['action']); ?>][]" class="form-control chosen" multiple="multiple" size="1" style="display:inline;width:85%" data-placeholder="선택한 그룹의 모든 회원이 권한을 갖게 됩니다.">
				<?php foreach($group['data'] as $key => $item) : ?>
					<?php if($item['admin'] == 'Y') continue; // 그냥 최고관리자는 표시안되도록 한다 2014-04-30 ?>
					<option value="<?php echo $item['id']; ?>"<?php if(isset($permission[$grant['@attributes']['action']][$item['id']]) OR $item['admin'] == 'Y') : ?> selected="selected"<?php endif; ?><?php if($item['admin'] == 'Y') : ?> disabled="disabled"<?php endif; ?>><?php echo $item['title']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="">
			<span class="label label-warning" style="margin-right:10px">회원별</span>
			<select name="member[<?php echo str_replace(array('.', '|'), array('/', ':'), $grant['@attributes']['action']); ?>][]" class="form-control chosen" multiple="multiple" size="1" style="display:inline;width:85%" data-placeholder="선택한 회원만 권한을 갖게 됩니다.">
				<?php foreach($member['data'] as $user) : ?>
					<option value="<?php echo $user['id']; ?>"<?php if(isset($permission[$grant['@attributes']['action']][$user['id']])) : ?> selected="selected"<?php endif; ?><?php if($this->account->get('admin') != 'Y' AND $this->account->get('id') == $user['id'] AND $grant['@attributes']['action']=='__ALL__') : ?> disabled="disabled"<?php endif; ?>><?php echo $user['name']; ?> (<?php echo $user['userid']; ?>)</option>
				<?php endforeach; ?>
			</select>
		</div>

		<?php if($grant['@attributes']['action']=='__ALL__') : ?>
		<p class="help-block"><span lang="en" class="label label-danger">Important</span> 관리권한으로 설정하면 최고관리자와 같은 권한을 갖게 됩니다.</p>
		<?php endif; ?>

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
<br />
<br />
<br />
<br />