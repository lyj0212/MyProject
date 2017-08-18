				<!-- 회원가입 : 개인정보입력 -->
				<div class="login_member_wrap memJoin">
					<h1>회원가입</h1>
					<div class="progress_wrap">
						<h2 class="hide">회원가입 진행순서</h2>
						<ol>
						<li><span><i>STEP 1</i> 약관동의</span></li>
						<li class="on"><span><i>STEP 2</i> 개인정보입력</span></li>
						<li class="last"><span><i>STEP 3</i> 가입완료</span></li>
						</ol>
					</div>
					<div class="login_member_frm memJoinInfo_ipt">
						<h2>개인정보입력</h2>
						<?php echo form_open($this->link->get(array('action'=>'join_save')), array('id'=>'writeForm', 'class'=>'form-horizontal', 'name'=>'join_save')); ?>
							<fieldset class="login_wrap">
							<legend>개인정보입력 폼</legend>
							<div class="item redund_chk">
								<label class="i_label" for="userId">이메일(아이디)</label>
								<input type="email" maxlength="50" class="i_text uid" id="userId" name="userid" value="" placeholder="Email 또는 ID를 입력하십시오" />
								<button type="button" class="btn btn-default userid_check">중복검사</button>
							</div>
							<div class="item">
								<label class="i_label" for="userPw">비밀번호</label>
								<input type="password" maxlength="16" class="i_text upw" id="userPw" name="passwd" value="" placeholder="비밀번호를 입력해 주세요 (영문, 숫자를 혼합하여 6~20자 이내)" />
							</div>
							<div class="item">
								<label class="i_label" for="userPw_ok">비밀번호 확인</label>
								<input type="password" maxlength="16" class="i_text upw" id="userPw_ok" name="passwd_ok" value="" placeholder="비밀번호를 다시 입력해 주세요" />
							</div>
							<div class="item">
								<label class="i_label" for="user_name">이름</label>
								<input type="text" maxlength="16" class="i_text upw" id="user_name" name="name" value="" placeholder="이름을 입력해 주세요" />
							</div>
							</fieldset>

							<div class="btn_wrap text-center">
								<button type="button" class="btn btn-primary joinForm_submit"><span>다음단계</span></button>
							</div>
						</form>
					</div>
				</div>
				<!-- //회원가입 : 개인정보입력 -->			