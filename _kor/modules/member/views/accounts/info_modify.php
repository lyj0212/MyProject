
				<!-- 회원가입 : 개인정보입력 -->
				<div class="login_member_wrap memJoin">
					<h1>회원수정</h1>
					<div class="login_member_frm memJoinInfo_ipt">
						<h2>개인정보수정</h2>
						<?php echo form_open($this->link->get(array('action'=>'join_save')), array('id'=>'writeForm', 'class'=>'form-horizontal', 'name'=>'modify')); ?>
							<fieldset class="login_wrap">
							<legend>개인정보수정 폼</legend>
							<div class="item">
								<label class="i_label" for="userId">이메일(아이디)</label>
								<input type="text" maxlength="50" class="i_text uid" id="userId" name="userid" value="<?php echo set_value('userid', $data['userid']); ?>" readonly="readonly" />
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
								<input type="text" maxlength="16" class="i_text upw" id="user_name" name="name" value="<?php echo set_value('name', $data['name']); ?>" placeholder="이름을 입력해 주세요" />
							</div>
							</fieldset>

							<div class="btn_wrap text-center">
								<button type="button" class="btn btn-primary modify_submit"><span class="icon-edit">수정</span></button>
							</div>
						</form>
					</div>
				</div>
				<!-- //회원가입 : 개인정보입력 -->			
