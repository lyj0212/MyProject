				<!-- 본문 컨텐츠 영역 -->
				<div class="tablewrap iptForm">
					<?php echo form_open($this->link->get(array('action'=>'infoCorp_save')), array('id'=>'writeForm', 'class'=>'form-horizontal', 'name'=>'infoCorp')); ?>
					<table class="bbs_table view">
					<caption>기업정보입력 폼</caption>
					<colgroup>
						<col width="70px" /><col width="150px" /><col /><col width="130px" /><col />
					</colgroup>
					<tbody>
					<tr>
						<th scope="rowgroup" rowspan="12">기업<br/>정보 <br/>입력</th>
						<th scope="row"><label for="compname" class="compls">회사명</label></th>
						<td><input type="text" class="form-control" name="compname" id="compname" value="<?php echo set_value('compname', $data['compname']); ?>" placeholder="회사명 입력" /></td>
						<th scope="row"><label for="comp_num1" class="compls">사업자등록번호</label></th>
						<td>
							<div class="corpregNum">
								<div class="input-group">
									<input type="number" id="comp_num1"  name="comp_num1" value="<?php echo set_value('comp_num1', $data['comp_num1']); ?>" class="form-control _comp_num1" title="사업자등록번호 첫자리 입력" <?php echo ((!empty($data['comp_num1'])) ? 'readonly' : '');?> />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="comp_num2" value="<?php echo set_value('comp_num2', $data['comp_num2']); ?>" class="form-control _comp_num2" title="사업자등록번호 중간자리 입력" <?php echo ((!empty($data['comp_num2'])) ? 'readonly' : '');?>/>
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="comp_num3" value="<?php echo set_value('comp_num3', $data['comp_num3']); ?>" class="form-control _comp_num3" title="사업자등록번호 끝자리 입력" <?php echo ((!empty($data['comp_num3'])) ? 'readonly' : '');?>/>
								</div>
								<span class="input-group-btn">
								<?php if(!empty($data['comp_num1'])) {?>
								    <button type="button" class="btn btn-default company_Numcheck" value="Y" disabled>중복검사</button>
								<?php } else { ?>
								    <button type="button" class="btn btn-default company_Numcheck">중복검사</button>
                                <?php } ?>
                                </span>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="found_day" class="compls">설립일자</label></th>
						<td>
							<div class="datesel">
								<div class="input-group">
									<input type="date" class="form-control" id="found_day" name="found_day" value="<?php echo set_value('found_day', $data['found_day']); ?>" placeholder="설립일자 입력" />
									<i class="icon-calendar-3"><span class="hide">달력</span></i>
								</div>
							</div>
						</td>
						<th scope="row"><label for="corporate_num1">법인등록번호</label></th>
						<td>
							<div class="corpregNum">
								<div class="input-group">
									<input type="number" name="corporate_num1" value="<?php echo set_value('corporate_num1', $data['corporate_num1']); ?>" class="form-control _corporate_num1" title="법인자등록번호 첫자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="corporate_num2" value="<?php echo set_value('corporate_num2', $data['corporate_num2']); ?>" class="form-control _corporate_num2" title="법인자등록번호 중간자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="corporate_num3" value="<?php echo set_value('corporate_num3', $data['corporate_num3']); ?>" class="form-control _corporate_num3" title="법인자등록번호 끝자리 입력" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><span class="compls">사업체구분</span></th>
						<td colspan="3">
							<div class="select_box" style="width:175px">
								<label for="comp_status1">사업체구분</label>
                                <select id="comp_status1" name="comp_status1" class="info_select input-sm comp_status1">
                                    <option value="">대분류</option>
                                    <option value="s1" <?php echo (($data['comp_status1'] ==  's1') ? 'selected' : '');?>>개인사업체</option>
                                    <option value="s2" <?php echo (($data['comp_status1'] ==  's2') ? 'selected' : '');?>>정부기관</option>
                                    <option value="s3" <?php echo (($data['comp_status1'] ==  's3') ? 'selected' : '');?>>법인</option>
								</select>
							</div>
							<div class="select_box" style="width:175px">
								<label for="comp_status2">법인 구분</label>
							    <select id="comp_status2" name="comp_status2" class="info_select input-sm">
                                    <option value="">중분류</option>
                                    <option value="s3_1" class="s3" <?php echo (($data['comp_status2'] ==  's3_1') ? 'selected' : '');?>>법인(영리)</option>
                                    <option value="s3_2" class="s3" <?php echo (($data['comp_status2'] ==  's3_2') ? 'selected' : '');?>>법인(비영리)</option>
								</select>
							</div>
							<div class="select_box" style="width:175px">
								<label for="comp_status3">법인 영리 구분</label>
								<select id="comp_status3" name="comp_status3" class="info_select input-sm">
                                    <option value="">소분류</option>
                                    <option value="s3_1_1" class="s3_1" <?php echo (($data['comp_status3'] ==  's3_1_1') ? 'selected' : '');?>>중소기업(벤처)</option>
                                    <option value="s3_1_2" class="s3_1" <?php echo (($data['comp_status3'] ==  's3_1_2') ? 'selected' : '');?>>중소기업(비벤처)</option>
                                    <option value="s3_1_3" class="s3_1" <?php echo (($data['comp_status3'] ==  's3_1_3') ? 'selected' : '');?>>대기업</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><span class="compls">벤처기업지정</span></th>
						<td>
							<div class="radio">
								<label>
									<input type="radio" name="venture_ap" value="Y" <?php echo set_checkbox('venture_ap', 'Y', ($data['venture_ap'] == 'Y')); ?> />
									<i class="iCheck">체크</i>
									<span>상장</span>
								</label>
								<label>
									<input type="radio" name="venture_ap" value="N" <?php echo set_checkbox('venture_ap', 'N', ($data['venture_ap'] == 'N')); ?> />
									<i class="iCheck">체크</i>
									<span>비상장</span>
								</label>
							</div>
							<div class="datesel">
								<div class="input-group">
									<label for="venture_date" class="hide">벤처기업지정일</label>
									<input type="date" id="venture_date" name="venture_date" class="form-control" value="<?php echo set_value('venture_date', $data['venture_date']); ?>" placeholder="벤처기업지정일 입력" />
									<i class="icon-calendar-3"><span class="hide">달력</span></i>
								</div>
							</div>
						</td>
						<th scope="row"><label for="venture_num1">벤처기업번호</label></th>
						<td>
							<div class="corpregNum">
								<div class="input-group">
									<input type="number" id="venture_num1" name="venture_num1" value="<?php echo set_value('venture_num1', $data['venture_num1']); ?>" class="form-control _venture_num1" title="벤처기업번호 첫자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="venture_num2" value="<?php echo set_value('venture_num2', $data['venture_num2']); ?>" class="form-control _venture_num2" title="벤처기업번호 중간자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="venture_num3" value="<?php echo set_value('venture_num3', $data['venture_num3']); ?>" class="form-control _venture_num3" title="벤처기업번호 끝자리 입력" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><span class="compls">기업상장여부</span></th>
						<td>
							<div class="radio">
								<label>
									<input type="radio" name="listed_check" value="E" <?php echo set_checkbox('listed_check', 'E', ($data['listed_check'] == 'E')); ?> />
									<i class="iCheck">체크</i>
									<span>거래소</span>
								</label>
								<label>
									<input type="radio" name="listed_check" value="K" <?php echo set_checkbox('listed_check', 'K', ($data['listed_check'] == 'K')); ?> />
									<i class="iCheck">체크</i>
									<span>코스닥</span>
								</label>
								<label>
									<input type="radio" name="listed_check" value="N" <?php echo set_checkbox('listed_check', 'N', ($data['listed_check'] == 'N')); ?> />
									<i class="iCheck">체크</i>
									<span>비상장</span>
								</label>
							</div>
						</td>
						<th scope="row"><label for="listed_day">상장일자</label></th>
						<td>
							<div class="datesel">
								<div class="input-group">
									<input type="date" id="listed_day" name="listed_day" value="<?php echo set_value('listed_day', $data['listed_day']); ?>" class="form-control" placeholder="상장일자 입력" />
									<i class="icon-calendar-3"><span class="hide">달력</span></i>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><span class="compls">기업부설연구소</span></th>
						<td>
							<div class="radio">
								<label>
									<input type="radio" name="attached_lab" value="Y" <?php echo set_checkbox('attached_lab', 'Y', ($data['attached_lab'] == 'Y')); ?> />
									<i class="iCheck">체크</i>
									<span>유</span>
								</label>
								<label>
									<input type="radio" name="attached_lab" value="N" <?php echo set_checkbox('attached_lab', 'N', ($data['attached_lab'] == 'N')); ?> />
									<i class="iCheck">체크</i>
									<span>무</span>
								</label>
							</div>
						</td>
						<th scope="row"><label for="attached_addr">소재지</label></th>
						<td><input type="text" id="attached_addr" name="attached_addr" value="<?php echo set_value('attached_addr', $data['attached_addr']); ?>" class="form-control" placeholder="소재지 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="ceo_name" class="compls">대표자명</label></th>
						<td><input type="text" id="ceo_name" name="ceo_name" value="<?php echo set_value('ceo_name', $data['ceo_name']); ?>" class="form-control" placeholder="대표자명 입력" /></td>
						<th scope="row"><label for="userEmail" class="compls">이메일</label></th>
						<td><input type="email" id="userEmail" name="comp_email" value="<?php echo set_value('comp_email', $data['comp_email']); ?>" class="form-control" placeholder="이메일주소 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="address1" class="compls">사업장주소</label></th>
						<td colspan="3">
							<div class="company_address radioChk">
								<div class="input-group">
									<input type="text" id="address1" name="address1" class="form-control " value="<?php echo set_value('address1', $data['address1']); ?>" placeholder="사업장 주소 입력" />
									<input type="text" name="address2" class="form-control" value="<?php echo set_value('address2', $data['address2']); ?>" placeholder="사업장 상세주소 입력" title="사업장 상세주소 입력" />
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="address_status" value="Y" <?php echo set_checkbox('address_status', 'Y', ($data['address_status'] == 'Y')); ?> />
										<i class="iCheck">체크</i>
										<span>자가</span>
									</label>
									<label>
										<input type="radio" name="address_status" value="N" <?php echo set_checkbox('address_status', 'N', ($data['address_status'] == 'N')); ?> />
										<i class="iCheck">체크</i>
										<span>임대</span>
									</label>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="phone1" class="compls">대표전화</label></th>
						<td>
							<div class="telephone">
								<div class="input-group">
									<input type="number" id="phone1" name="phone1" class="form-control" value="<?php echo set_value('phone1', $data['phone1']); ?>" title="대표전화 첫자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="phone2" class="form-control" value="<?php echo set_value('phone2', $data['phone2']); ?>" title="대표전화 중간자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="phone3" class="form-control" value="<?php echo set_value('phone3', $data['phone3']); ?>" title="대표전화 끝자리 입력" />
								</div>
							</div>
						</td>
						<th scope="row"><label for="fax1" class="compls">팩스번호</label></th>
						<td>
							<div class="telephone">
								<div class="input-group">
									<input type="number" id="fax1" name="fax1" class="form-control" value="<?php echo set_value('fax1', $data['fax1']); ?>" title="팩스번호 첫자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="fax2" class="form-control" value="<?php echo set_value('fax2', $data['fax2']); ?>" title="팩스번호 중간자리 입력" />
								</div>
								<div class="input-group">
									<span class="input-group-addon">-</span>
									<input type="number" name="fax3" class="form-control" value="<?php echo set_value('fax3', $data['fax3']); ?>" title="팩스번호 끝자리 입력" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="business_status" class="compls">주업태</label></th>
						<td><input type="text" id="business_status" name="business_status" value="<?php echo set_value('business_status', $data['business_status']); ?>" class="form-control" placeholder="주업태 입력" /></td>
						<th scope="row"><label for="business_type" class="compls">주업종</label></th>
						<td><input type="text" id="business_type" name="business_type" value="<?php echo set_value('business_type', $data['business_type']); ?>" class="form-control" placeholder="주업종 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="content">주제품명</label></th>
						<td colspan="3"><input type="text" id="content" name="content" value="<?php echo set_value('content', $data['content']); ?>" class="form-control" placeholder="주제품명 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="homepage">홈페이지</label></th>
						<td colspan="3"><input type="text" id="homepage" name="homepage" value="<?php echo set_value('homepage', $data['homepage']); ?>" class="form-control" placeholder="홈페이지 입력" /></td>
					</tr>
					<!--tr>
						<th scope="rowgroup" rowspan="4">공통<br/>서류</th>
						<th scope="row"><label for="biz_license" class="compls">사업자등록증</label></th>
						<td colspan="3"><input type="file" class="form-control" name="biz_license" id="biz_license" value="" placeholder="사업자등록증 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="financial_state" class="compls">재무재표(최근2년)</label></th>
						<td colspan="3"><input type="file" class="form-control" name="financial_state" id="financial_state" value="" placeholder="재무재표(최근2년) 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="certify_papers" class="compls">각종인증서류</label></th>
						<td colspan="3"><input type="file" class="form-control" name="certify_papers" id="certify_papers" value="" placeholder="각종인증서류 입력" /></td>
					</tr>
					<tr>
						<th scope="row" class="blw"><label for="industry_papers" class="compls">산업재산권 서류</label></th>
						<td colspan="3"><input type="file" class="form-control" name="industry_papers" id="industry_papers" value="" placeholder="산업재산권 서류 입력" /></td>
					</tr>
					<tr>
						<th scope="rowgroup">사업<br/>서류</th>
						<th scope="row"></th>
						<td colspan="3">
							<div class="biz_papers_wrap">
								<span class="input-group-addon">
									<input type="text" class="form-control" name="papers_name1" id="papers_name1" value="" placeholder="서류명 입력" />
								</span>
								<input type="file" class="form-control" name="papers_file1" id="papers_file1" value="" placeholder="서류파일 입력" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default">추가</button>
								</span>
							</div>
							<ul class="biz_papers_lst">
							<li><strong>임대차계약서 사본</strong> <a href="#">임대차계약서 사본.hwp</a></li>
							<li><strong>주무관청의 설립허가증 사본</strong> <a href="#">주무관청의 설립허가증 사본.hwp</a></li>
							</ul>
						</td>
					</tr-->
					</tbody>
					</table>

					<p class="btn_wrap text-center">
						<button type="button" class="btn btn-primary infoCorp_submit"><span class="icon-ok-circle">저장</span></button>
						<a href="" class="btn btn-cancel"><span class="icon-cancel-circle">취소</span></a>
					</p>
					</form>
				</div>
