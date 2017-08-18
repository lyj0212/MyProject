				
				<?php echo form_open($this->link->get(array('action'=>'tf_control')), array('id'=>'transferForm', 'class'=>'form-horizontal', 'name'=>'transfer')); ?>
				<div class="transfer_wrap">
					<h2><em>원활한 소통과 전문적인 기업서비스 제공을 위한</em> 소통 플랫폼의 활용은?</h2>
					<ul class="platform_intro">
					<li>
						<div class="thumb hope_platform">
							<strong>기업 관련 다양한 전문 정보 제공</strong>
							<p class="txtp">정부지원사업을 포함하여 다양한 정보를 모아서 <br/>제공함으로 SW융합 클러스터 기업들은 <br/>간편하게 공유 및 스크랩</p>
						</div>
					</li>
					<li>
						<div class="thumb human_platform">
							<strong>네트워킹 활성화를 위한 인맥추천 서비스</strong>
							<p class="txtp">SW융합클러스터 기업 및 참여 기관에 대한 <br/>인맥 추천 서비스를 통하여 협업할 수 있는 장을 <br/>마련</p>
						</div>
					</li>
					<li>
						<div class="thumb schedule_platform">
							<strong>편리한 업무를 위한 일정관리 서비스</strong>
							<p class="txtp">주고 받은 업무정보 중 날짜기반의 메타정보가 <br/>있는 정보만 모아서 자동으로 일정을 등록하고 <br/>등록된 일정을 함께 수정하고 관리할 수 있는 <br/>서비스를 제공</p>
						</div>
					</li>
					</ul>
					<div class="basic_box scroll">
						<p class="txtp">SW융합클러스터 대덕센터에서는  다양한 사업에 참여했던 기관 및 기업들과의 소통과 정보제공, 협력을 위해 별도의 소통 플랫폼을 활용합니다.<br/>플랫폼에서는 보다 전문적인 기업 서비스 (정부지원사업 매칭, 유관기관 및 기업 네트워킹, 기업일정관리 서비스 등)를 제공합니다.<br/>아래 정보 이관을 신청하시면 기존 계정으로 간편하게 서비스를 사용할 수 있습니다.</p>
						<p class="txtp">
							- 회원가입 : 아이디, 비밀번호, 이메일 주소<br/>
							- 기업정보 : 회사명, 사업자등록번호, 설립일자, 법인등록번호, 기업상장여부, 소재지, 대표자명, 이메일, 사업장 주소, 대표전화, 팩스번호, 주업태, 주업종, <br/><span style="padding-left:70px;">주제품명, 홈페이지</span>
						</p>
					</div>
					
                    <?php if (empty($data['created'])) { ?>
					<div class="checkbox">
						<label>
							<input type="checkbox" id="agree" name="agree" data-msg-required="약관에 동의해 주세요." class="transfer_chk"/>
							<i class="iCheck">체크</i>
							<span><a href="javascript:void(0);" title="이용약관, 개인정보 이용지침에 동의해 주시겠습니까?" data-target=".termsAgreeChk_wrap" data-toggle="modal">이용약관, 개인정보 이용지침에 동의</a></span>
						</label>
					</div>
					<?php } ?>
                    
                    <?php if (!empty($data['created'])) { ?>
					<div class="btn_wrap text-center">
					       <?php echo date('Y년m월d일 H시i분', strtotime($data['created'])); ?>  정보이관 되었습니다.
					</div>
                    <?php } ?>
                    
					<div class="btn_wrap text-center">
					    <?php if (!empty($data['created'])) { ?>
					       <a class="btn btn-lg btn-primary" href="http://cube.y-bridge.co.kr" target="_blank" title="새창에서 열림"><span class="icon-popup-4">플랫폼 서비스 바로가기</span></a>
					    <?php }  else { ?>
						<button type="button" class="btn btn-lg btn-primary transfer_send"><span class="icon-shuffle">정보이관</span></button>
						<?php } ?>
					</div>
				</div>
				</form>

				<!-- 모달 윈도우 : 이용약관동의 -->
				<div id="pagelet-modal" class="termsAgreeChk_wrap modal fade" aria-hidden="false" aria-labelledby="pagelet-modalLabel" role="dialog" tabindex="-1">
					<div class="modalPopup_wrap modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">서비스 약관</h2>
							</div>
							<div class="modal-body">
								<div class="termsAgree_wrap">
									<h3>이용약관</h3>
									<div class="basic_box scroll" tabindex="0" style="height:128px">
										<?php echo $data[0]['contents']; ?>
									</div>

									<h3>개인정보 수집 및 이용</h3>
									<div class="basic_box scroll" tabindex="0" style="height:128px">
									    <?php echo $data[1]['contents']; ?>
									</div>
								</div>
								<div class="btn_wrap text-center">
									<a class="btn btn-primary" href="#" aria-label="Close" data-dismiss="modal"><span class="icon-ok-circle">확인</span></a>
								</div>
							</div>
							<p class="close_modal"><a href="#" aria-label="Close" data-dismiss="modal"><span class="modal_close">닫기</span></a></p>
							<!--<button aria-label="Close" data-dismiss="modal" class="close_modal" type="button"><span class="modal_close">닫기</span></button>-->
						</div>
					</div>
				</div>
				<!-- //모달 윈도우 -->