				<!-- 회원가입 : 가입완료 -->
				<div class="login_member_wrap memJoin">
					<h1>회원가입</h1>
					<div class="progress_wrap">
						<h2 class="hide">회원가입 진행순서</h2>
						<ol>
						<li><span><i>STEP 1</i> 약관동의</span></li>
						<li><span><i>STEP 2</i> 개인정보입력</span></li>
						<li class="last on"><span><i>STEP 3</i> 가입완료</span></li>
						</ol>
					</div>
					<div class="login_member_frm memJoinInfoComp">
						<h2>가입완료 축하드립니다.</h2>
						<p class="txtp"><em>가입이 정상 완료되었습니다.</em> 이메일인증 후에 모든 서비스(로그인 및 사업 접수) 사용이 가능합니다. <br/><span class="underline">입력하신 이메일(아이디)로 인증안내 메일이 발송</span>되었습니다.</p>

						<div class="joinemail_confirm">
							<p class="txtp">가입 이메일 : <?php echo $data['userid']; ?></p>
						</div>

						<div class="btn_wrap text-center">
							<a href="/" class="btn btn-primary"><span>메인으로 이동</span></a>
						</div>
					</div>
				</div>
				<!-- //회원가입 : 가입완료 -->