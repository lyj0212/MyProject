<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />

<fieldset>
<div class="form-group">
	<label>레이아웃 이름</label>
	<input type="text" name="title" class="form-control input-sm" value="<?php echo set_value('title', $data['title']); ?>" />
</div>
<div class="form-group">
	<label>레이아웃 HTML</label>
	<textarea name="source" class="form-control input-sm" id="code" rows="20"><?php echo set_value('source', $data['source']); ?></textarea>
</div>
<div class="form-group">
	<label>상단 메뉴 HTML</label>
	<textarea name="top_menu" class="form-control input-sm" id="code1" rows="10"><?php echo set_value('top_menu', $data['top_menu']); ?></textarea>
</div>
<div class="form-group">
	<label>상단 메뉴 HTML ENTRIES</label>
	<textarea name="top_menu_depth" class="form-control input-sm" id="code2" rows="10"><?php echo set_value('top_menu_depth', $data['top_menu_depth']); ?></textarea>
</div>
<div class="form-group">
	<label>서브 메뉴 HTML</label>
	<textarea name="sub_menu" class="form-control input-sm" id="code3" rows="10"><?php echo set_value('sub_menu', $data['sub_menu']); ?></textarea>
</div>
<div class="form-group">
	<label>서브 메뉴 HTML ENTRIES</label>
	<textarea name="sub_menu_depth" class="form-control input-sm" id="code4" rows="10"><?php echo set_value('sub_menu_depth', $data['sub_menu_depth']); ?></textarea>
</div>
<div class="form-group">
	<label>팝업 레이아웃 HTML</label>
	<textarea name="popup" class="form-control input-sm" id="code5" rows="15"><?php echo set_value('popup', $data['popup']); ?></textarea>
</div>
<div class="well well-centered">
	<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
	<a class="btn btn-sm btn-default" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
</div>
</fieldset>

</form>
