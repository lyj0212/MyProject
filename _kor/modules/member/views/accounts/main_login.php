                <!-- 로그인 -->
                <div class="login_member_wrap">
                    <h1>LOGIN</h1>
                    <div class="login_member_frm">
                        <?php echo form_open($this->link->get(array('action'=>'main_do_login')), array('id'=>'writeForm','name' =>'writeForm', 'class'=>'form-horizontal', 'name'=>'login')); ?>
                        	<input type="hidden" name="redirect" value="<?php echo set_value('redirect', $redirect); ?>" />
                            <div class="loginfail_messg"><!-- 로그인 실패시 class="active" 추가 -->
                                <strong class='error_1'></strong>
                                <p class="txtp error_2"></p>
                            </div>

                            <fieldset class="login_wrap">
                            <legend>LOGIN 폼</legend>
                            <div class="item">
                                <label class="i_label" for="userId">이메일(아이디)</label>
                                <input type="email" maxlength="50" class="i_text uid" id="userId" name="userid" value="<?php echo set_value('userid', $userid);?>" placeholder="Email 또는 ID를 입력해 주세요" />
                            </div>
                            <div class="item">
                                <label class="i_label" for="userPw">비밀번호</label>
                                <input type="password" maxlength="16" class="i_text upw" id="userPw" name="passwd" value="" placeholder="비밀번호를 입력해 주세요" />
                            </div>
                            <div class="item keepId">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_id" value="1" <?php echo set_checkbox('remember_id', '1', $check_remember); ?> />
                                        <i class="iCheck">체크</i>
                                        <span>아이디 저장</span>
                                    </label>
                                </div>
                                <p class="login_menu">
                                    <a href="javascript:void(0);" class="help_btn" title="계정을 잊어버리셨나요?" data-target=".emailSCH_wrap" data-toggle="modal"><span class="icon-key-4">계정 찾기</span></a>
                                    <a href="<?php echo $this->link->get(array('prefix'=>'join','controller'=>'accounts', 'action'=>'join'));?>" class="help_btn" data-placement="top" data-toggle="tooltip" title="아직 회원이 아니신가요?"><span class="icon-user-plus">회원가입</span></a>
                                </p>
                            </div>
                            <p class="loginBtn btn_wrap">
                                <button type="button" class="btn btn-primary loginForm_submit"><span class="icon-off">로그인</span></button>
                            </p>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <!-- //로그인 -->

                <!-- 모달 윈도우 : 계정찾기 -->
                <div id="pagelet-modal" class="emailSCH_wrap modal fade" aria-hidden="false" aria-labelledby="pagelet-modalLabel" role="dialog" tabindex="-1">
                    <div class="modalPopup_wrap modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title">계정찾기</h2>
                            </div>
                            <div class="modal-body">
                                <div class="login_member_frm">
                                    <div class="loginfail_messg active">
                                        <p class="txtp"><strong><em>가입하신 이메일 주소</em>를 입력해주시면</strong>임시 비밀번호가 발송되며,<br/>새로운 비밀번호로 변경이 가능합니다.</p>
                                    </div>

                                    <?php echo form_open($this->link->get(array('action'=>'find_pwd')), array('id'=>'accountSCHForm','name' =>'writeForm', 'class'=>'form-horizontal', 'name'=>'find')); ?>
                                        <fieldset class="login_wrap">
                                        <legend>계정찾기 폼</legend>
                                        <div class="item">
                                            <label class="i_label" for="userid">이메일(아이디)</label>
                                            <input type="text" maxlength="50" class="i_text uid pwd_find" id="userid" name="userid" value="" placeholder="Email 또는 ID를 입력하십시오" />
                                        </div>
                                        <p class="loginBtn btn_wrap">
                                            <button type="button" class="btn btn-primary findPwd_submit"><span class="icon-lock-5">계정찾기</span></button>
                                        </p>
                                        </fieldset>
                                    </form>
                                    <p class="caution"><strong class="underline">"계정찾기" 버튼</strong> 클릭 시 입력하신 이메일로 메일이 발송됩니다.</p>
                                </div>
                            </div>
                            <p class="close_modal"><a href="#" aria-label="Close" data-dismiss="modal"><span class="modal_close">닫기</span></a></p>
                            <!--<button aria-label="Close" data-dismiss="modal" class="close_modal" type="button"><span class="modal_close">닫기</span></button>-->
                        </div>
                    </div>
                </div>
                <!-- //모달 윈도우 -->
