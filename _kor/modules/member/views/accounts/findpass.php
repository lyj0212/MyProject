<!-- 계정찾기 폼 -->
<div class="memberForm_wrap">
    <h2 class="hide">계정 - 가입정보</h2>
    <form name="memberCertifyForm" method="post" action="#">
        <fieldset>
            <legend>계정 - 가입정보 폼</legend>
            <p class="title"><strong>오랜만에 오셨나요? ^^</strong>아이디, 비밀번호를 찾으실 수 있도록 도와드리겠습니다!</p>

            <div class="frm_group uname">
                <label class="username" for="userName"><i class="pe-7s-user" aria-hidden="true"><span class="hide">이름</span></i></label>
                <input type="text" class="form_ctrl" id="userName" name="userName" value="" placeholder="이름" />
            </div>
            <div class="frm_group uid">
                <label class="userid" for="userid"><i class="pe-7s-mail" aria-hidden="true"><span class="hide">아이디(이메일)</span></i></label>
                <input type="text" class="form_ctrl" id="userid" name="userid" value="" placeholder="아이디(이메일)" />
            </div>

            <div class="JoinResult_wrap _show" style="display:none">
                <strong><span id="name"></span>님 오랫동안 기다렸습니다…</strong>
								<span class="desc"><span id="date"></span> 가입한 적이 있어요. <br/>비밀번호를 다시 입력해보실래요??</span>
                <p class="btn_wrap">
                    <a href="<?php echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'login'));?>">로그인</a>
                    <a href="<?php echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'find_pwd'));?>" id="_nowpasswd">새로운 비밀번호 이메일로 받기</a>
                </p>
            </div>
						<div class="JoinResult_wrap _hide" style="display:none">
							<strong>해당 정보로 가입된 내역이 없습니다. </strong>
							<span class="desc">회원가입 후 이용해주시기바랍니다. </span>
							<p class="btn_wrap" style="width: 100px !important;">
								<a href="<?php echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'join'));?>">회원가입</a>
							</p>
						</div>

            <p class="btn_wrap center">
                <a href="javascript:void(0);" class="btn btn-primary _reno" id="check"><i class="pe-7s-check" aria-hidden="true"></i> 가입여부 확인</a>
                <a href="<?php echo $this->link->get(array('prefix'=>'order_login','controller'=>'accounts', 'action'=>'findpass'));?>" class="btn btn-primary _recheck" id="" style="display:none"><i class="pe-7s-check" aria-hidden="true"></i> 다시입력</a>
            </p>
        </fieldset>
    </form>
</div>
<!-- //계정찾기 폼 -->