<?php echo validation_errors(); ?>
<?php echo form_open_multipart($this->link->get(array('action'=>'save')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="menu_id" value="<?php echo set_value('menu_id', $data['menu_id']); ?>" />
<input type="hidden" name="parent_id" value="<?php echo set_value('parent_id', $data['parent_id']); ?>" />

<fieldset>
<div class="form-group">
	<label class="col-sm-2 control-label">메뉴명</label>
	<div class="col-sm-10 row">
		<div class="col-lg-3">
			<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">접속 URL</label>
	<div class="col-sm-10 row">
		<div class="col-lg-5">
			<div class="input-group input-group-sm">
				<span class="input-group-addon"><?php echo str_replace(SITE.'/', '', site_url()); ?><?php if( ! empty($menu['data']['map'])) : ?><?php echo $menu['data']['map']; ?>/<?php endif; ?></span>
				<input type="text" name="url" class="form-control" value="<?php echo set_value('url', $data['url']); ?>" />
			</div>
		</div>
		<div class="col-lg-10">
			<p class="help-block"><span lang="en" class="label label-danger">Important</span> 알파벳 문자와 숫자, 밑줄, 대시만 포함할 수 있습니다.</p>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">외부링크</label>
	<div class="col-sm-10 row">
		<div class="col-lg-3">
			<input type="text" name="link" class="form-control input-sm" value="<?php echo set_value('link', $data['link']); ?>" />
		</div>
		<div class="col-lg-10">
			<p class="help-block"><span lang="en" class="label label-danger">Important</span> 외부링크를 입력할 경우 접속 URL 보다 우선순위를 갖습니다.</p>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">타겟</label>
	<div class="col-sm-10">
		<label class="checkbox-inline">
			<input type="checkbox" name="win_target" value="_blank" <?php echo set_checkbox('win_target', '_blank', ($data['win_target']=='_blank')); ?>> 새창
		</label>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">레이아웃</label>
	<div class="col-sm-10 row">
		<div class="col-lg-3">
			<select name="layout" class="form-control input-sm">
				<option value="">미사용</option>
				<?php foreach($layout['data'] as $item) : ?>
				<option value="<?php echo $item['id']; ?>" <?php echo set_select('layout', $item['id'], ($data['layout']==$item['id'])); ?>><?php echo $item['title']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-lg-10">
			<label class="checkbox-inline">
				<input type="checkbox" name="layout_apply" value="1"> 같은 맵의 모든 메뉴에 적용
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">모듈</label>
	<div class="col-sm-10 row">
		<div class="col-lg-3">
			<select name="target" class="form-control input-sm">
				<option value="">미사용</option>
				<optgroup></optgroup>
				<?php foreach($module as $item) : ?>
					<?php if( ! empty($item['separation_start'])) : ?>
						<optgroup label="<?php echo $item['separation_start']; ?>">
					<?php elseif( ! empty($item['separation_end'])) : ?>
						</optgroup>
						<optgroup></optgroup>
					<?php else : ?>
						<option value="<?php echo encode_url($item['target']); ?>"<?php if(isset($item['child'])) : ?> rel="true"<?php endif; ?> <?php echo set_select('target', encode_url($item['target']), ($data['target']==$item['target'])); ?>><?php echo $item['title']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">타이틀 이미지</label>
	<div class="col-sm-10">
		<?php
		$params = array(
			'pid' => $data['id'],
			'target' => 'menu_title',
			'limit' => 1,
			'ext_desc' => '이미지 파일',
			'ext' => 'jpg,gif,png',
			'files' => ( ! empty($menu_title)) ? $menu_title : array()
		);
		echo CI::$APP->_uploader($params);
		?>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">메뉴 이미지</label>
	<div class="col-sm-10">
		<p class="help-block"><span lang="en" class="label label-info">Normal</span> 기본 이미지</p>
		<?php
		$params = array(
			'pid' => $data['id'],
			'target' => 'menu_normal',
			'limit' => 1,
			'ext_desc' => '이미지 파일',
			'ext' => 'jpg,gif,png',
			'files' => ( ! empty($menu_normal)) ? $menu_normal : array()
		);
		echo CI::$APP->_uploader($params);
		?>

		<p class="help-block"><span lang="en" class="label label-info">Hover</span> 마우스 오버시 이미지</p>
		<?php
		$params = array(
			'pid' => $data['id'],
			'target' => 'menu_hover',
			'limit' => 1,
			'ext_desc' => '이미지 파일',
			'ext' => 'jpg,gif,png',
			'files' => ( ! empty($menu_hover)) ? $menu_hover : array()
		);
		echo CI::$APP->_uploader($params);
		?>

		<p class="help-block"><span lang="en" class="label label-info">Active</span> 현재 활성화된 이미지</p>
		<?php
		$params = array(
			'pid' => $data['id'],
			'target' => 'menu_active',
			'limit' => 1,
			'ext_desc' => '이미지 파일',
			'ext' => 'jpg,gif,png',
			'files' => ( ! empty($menu_active)) ? $menu_active : array()
		);
		echo CI::$APP->_uploader($params);
		?>

	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">표출 여부</label>
	<div class="col-sm-10">
		<label class="checkbox-inline">
			<input type="checkbox" name="hidden" value="Y" <?php echo set_checkbox('hidden', 'Y', ($data['hidden']=='Y')); ?>> 메뉴 표출 안함
		</label>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">메인 설정</label>
	<div class="col-sm-10">
		<label class="checkbox-inline">
			<input type="checkbox" name="index" value="Y" <?php echo set_checkbox('index', 'Y', ($data['index']=='Y')); ?>> 해당 맵의 시작 화면으로 설정
		</label>
	</div>
</div>

</fieldset>

<div class="modal-footer">
	<?php if(empty($data['id'])) : ?>
	<label><input type="checkbox" name="continue" value="Y" <?php echo set_checkbox('continue', 'Y', ($data['continue']=='Y')); ?>> 계속해서등록</label>
	<?php endif; ?>
	<a href="#" class="btn btn-default btn-sm close_modal">닫기</a>
	<button type="submit" class="btn btn-primary btn-sm">변경내용 저장</button>
</div>

</form>