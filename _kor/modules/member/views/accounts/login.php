<div class="login_form">

	<?php echo validation_errors(); ?>
	<?php echo form_open($this->link->get(array('action'=>'do_login')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
	<input type="hidden" name="redirect" value="<?php echo set_value('redirect', $redirect); ?>" />
	<input type="hidden" name="action" value="<?php echo set_value('action', $action); ?>" />

	<fieldset>
	<legend>로그인 입력폼</legend>
	<div class="insert">
		<label for="uid"><img src="/images/login/txt_id.gif" alt="id" /></label>
		<input type="text" id="uid" name="userid" title="아이디 입력" value="<?php echo set_value('userid');?>" />
		<label for="upw"><img src="/images/login/txt_pw.gif" alt="pw" /></label>
		<input type="password" id="upw" name="passwd" title="비밀번호 입력" />
	</div>
	<p class="float_left"><input  type="image" src="/images/login/btn_login.gif" alt="로그인" /></p>
	</fieldset>
	</form>

</div>
