<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>



<!-- 로그인폼 -->
<div class="memberForm_wrap">
	<h2 class="hide">로그인</h2>
    <?php echo form_open($this->link->get(array('action'=>'main_do_login')), array('id'=>'writeForm','name' =>'writeForm', 'class'=>'form-horizontal', 'name'=>'login')); ?>
		<input type="hidden" name="redirect" value="<?php echo set_value('redirect', $redirect); ?>" />
		<fieldset>
			<legend>로그인 폼</legend>
			<div class="frm_group uid">
				<label for="userid"><i class="pe-7s-mail" aria-hidden="true"><span class="hide">아이디(이메일)</span></i></label>
				<input type="text" class="form_ctrl" id="userId" name="userid" value="<?php echo set_value('userid', $userid);?>" placeholder="아이디(이메일)" maxlength="50" />
			</div>
			<div class="frm_group upw">
				<label for="passwd"><i class="pe-7s-lock" aria-hidden="true"><span class="hide">비밀번호</span></i></label>
				<input type="password" class="form_ctrl" id="passwd" name="passwd" value="" placeholder="비밀번호" maxlength="16" />
			</div>

			<div class="frm_group">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember_id" id="loginchk" <?php echo set_checkbox('remember_id', '1', $check_remember); ?> value="1"/>
						<i class="iCheck"></i><span>로그인상태유지</span>
					</label>
				</div>
				<div class="passwd_sch">
					<!--<a href="<?php /*echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'findpass'));*/?>" class="btn btn_idpwsch"><i class="pe-7s-power" aria-hidden="true"></i> 비밀번호찾기</a>-->
					<a href="<?php echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'join'));?>" class="btn btn_join"><i class="pe-7s-user" aria-hidden="true"></i> 회원가입</a>
				</div>
				<!--<p class="login_menu">
					<a href="javascript:void(0);" class="help_btn" title="계정을 잊어버리셨나요?" data-target=".emailSCH_wrap" data-toggle="modal"><span class="icon-key-4">계정 찾기</span></a>
					<a href="<?php /*echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'join'));*/?>" class="help_btn" data-placement="top" data-toggle="tooltip" title="아직 회원이 아니신가요?"><span class="icon-user-plus">회원가입</span></a>
				</p>-->
				<p class="btn_wrap center">
					<button class="btn btn-primary" type="submit"><i class="pe-7s-power" aria-hidden="true"></i> LOGIN</button>
				</p>
			</div>
		</fieldset>
	</form>

	<p class="sns_wrap">
		<strong><span>[ <em>SNS</em>으로 로그인하기 ]</span></strong>
		<a class="btn" href="#"><span class="kt" id="kakaoBtn">카카오톡</span></a>
		<a class="btn" href="#"><span class="in" id="instaBtn">인스타그램</span></a>
		<a class="btn" href="#"><span class="gg" id="googleBtn">구글</span></a>
		<a class="btn" href="#"><span class="fb" id="facebookBtn">페이스북</span></a>
		<a class="btn" href="#"><span class="nv" id="naver_id_login">네이버</span></a>
	</p>
</div>
<!-- //로그인폼 -->

