<div class="tablewrap iptForm">

	<?php echo validation_errors(); ?>
	<?php echo form_open_multipart($this->link->get(array('action'=>'save')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
	<input type="hidden" name="pid" value="<?php echo set_value('pid', $data['pid']); ?>" />
	<input type="hidden" name="category" value="<?php echo set_value('category', $data['category']); ?>" />
	<input type="hidden" name="id" value="<?php echo set_value('id', $myinfo['data']['id']); ?>" />
	
	<div class="basic_box bizNotify_wrap">
		<dl class="bizNotify_info">
		<dt>분&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;류 :</dt>
		<dd><?php echo $data['category_title'];?></dd>
		<dt>사&nbsp;&nbsp;업&nbsp;&nbsp;명 :</dt>
		<dd><strong><?php echo $data['subject'];?></strong></dd>
		<dt>공고기간 :</dt>
		<dd><?php echo (!empty($data['startdate']) || !empty($data['enddate']) ? date('Y.m.d H:i', strtotime($data['startdate']))." ~ ".date('Y.m.d H:i', strtotime($data['enddate'])) : "-"); ?></dd>
		</dl>
	</div>

	<table class="bbs_table view">
	<caption>사업공고 접수 입력 폼</caption>
	<colgroup>
		<col width="70px" /><col width="150px" /><col /><col width="130px" /><col />
	</colgroup>
	<tbody>
	<tr>
		<th scope="rowgroup" rowspan="2">담당자 <br/>정보</th>
		<th scope="row"><label for="chief_name">이름</label></th>
		<td colspan="3"><input type="text" name="chief_name" id="chief_name" class="form-control required" value="<?php echo $myinfo['data']['chief_name'];?>" placeholder="이름 입력" /></td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="chief_email">이메일</label></th>
		<td><input type="email" class="form-control required" name="chief_email" id="chief_email" value="<?php echo $myinfo['data']['chief_email'];?>" placeholder="이메일 입력" /></td>
		<th scope="row"><label for="chief_phone1">연락처</label></th>
		<td>
			<div class="telephone">
				<div class="input-group">
					<input type="text" name="chief_phone1" id="chief_phone1" class="form-control required" value="<?php echo $myinfo['data']['chief_phone1'];?>" title="연락처 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text" name="chief_phone2" id="chief_phone2" class="form-control required" value="<?php echo $myinfo['data']['chief_phone2'];?>" title="연락처 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text"  name="chief_phone3" id="chief_phone3" class="form-control required" value="<?php echo $myinfo['data']['chief_phone3'];?>" title="연락처 끝자리 입력" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="rowgroup" rowspan="12">기업<br/>정보 <br/>입력</th>
		<th scope="row"><label for="compname" class="compls">회사명</label></th>
		<td><input type="text" name="compname" id="compname" class="form-control required" value="<?php echo $myinfo['data']['compname'];?>" placeholder="회사명 입력" /></td>
		<th scope="row"><label for="comp_num1" class="compls">사업자등록번호</label></th>
		<td>
			<div class="corpregNum">
				<div class="input-group">
					<input type="number" name="comp_num1" id="comp_num1" class="form-control required" value="<?php echo $myinfo['data']['comp_num1'];?>" <?php echo (!empty($myinfo['data']['comp_num1']) ? 'readonly="readonly"' : '');?> maxlength="3" min="1" max="999" title="사업자등록번호 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="comp_num2" id="comp_num2" class="form-control required" value="<?php echo $myinfo['data']['comp_num2'];?>" <?php echo (!empty($myinfo['data']['comp_num2']) ? 'readonly="readonly"' : '');?> maxlength="2" min="1" max="99" title="사업자등록번호 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="comp_num3" id="comp_num3" class="form-control required" value="<?php echo $myinfo['data']['comp_num3'];?>" <?php echo (!empty($myinfo['data']['comp_num3']) ? 'readonly="readonly"' : '');?> maxlength="5" min="1" max="99999" title="사업자등록번호 끝자리 입력" />
				</div>
				<?php if(empty($myinfo['data']['comp_num'])) :?>
					<input type="hidden" name="comp_num_check" class="form-control"/>
				<span class="input-group-btn"><button type="button" class="btn btn-default _check" data-target="comp_num">중복검사</button></span>
				<?php endif;?>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="found_day" class="compls">설립일자</label></th>
		<td>
			<div class="datesel">
				<div class="input-group">
					<input type="date" name="found_day" id="found_day" class="form-control required" value="<?php echo $myinfo['data']['found_day'];?>" placeholder="설립일자 입력" />
					<i class="icon-calendar-3"><span class="hide">달력</span></i>
				</div>
			</div>
		</td>
		<th scope="row"><label for="corporate_num1">법인등록번호</label></th>
		<td>
			<div class="corpregNum">
				<div class="input-group">
					<input type="number" name="corporate_num1" id="corporate_num1" class="form-control required" value="<?php echo $myinfo['data']['corporate_num1'];?>" title="법인등록번호 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="corporate_num2" id="corporate_num2" class="form-control required" value="<?php echo $myinfo['data']['corporate_num2'];?>" title="법인등록번호 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="corporate_num3" id="corporate_num3" class="form-control required" value="<?php echo $myinfo['data']['corporate_num3'];?>" title="법인등록번호 끝자리 입력" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><span class="compls">사업체구분</span></th>
		<td colspan="3">
			<div class="select_box" style="width:175px">
				<label for="comp_status1">사업체구분</label>
				<select name="comp_status1" id="comp_status1" class="info_select input-sm comp_status1 required">
					<option value="">대분류</option>
					<option value="s1" <?php echo (($myinfo['data']['comp_status1'] ==  's1') ? 'selected' : '');?>>개인사업체</option>
					<option value="s2" <?php echo (($myinfo['data']['comp_status1'] ==  's2') ? 'selected' : '');?>>정부기관</option>
					<option value="s3" <?php echo (($myinfo['data']['comp_status1'] ==  's3') ? 'selected' : '');?>>법인</option>
				</select>
			</div>
			<!-- 법인일 경우에만 -->
			<div class="select_box" style="width:175px">
				<label for="comp_status2">법인 구분</label>
				<select name="comp_status2" id="comp_status2" class="info_select input-sm">
					<option value="">중분류</option>
					<option value="s3_1" class="s3" <?php echo (($myinfo['data']['comp_status2'] ==  's3_1') ? 'selected' : '');?>>법인(영리)</option>
					<option value="s3_2" class="s3" <?php echo (($myinfo['data']['comp_status2'] ==  's3_2') ? 'selected' : '');?>>법인(비영리)</option>
				</select>
			</div>
			<!-- 법인에서도 영리일 경우에만 -->
			<div class="select_box" style="width:175px">
				<label for="comp_status3">법인 영리 구분</label>
				<select name="comp_status3" id="comp_status3" class="info_select input-sm">
					<option value="">소분류</option>
					<option value="s3_1_1" class="s3_1" <?php echo (($myinfo['data']['comp_status3'] ==  's3_1_1') ? 'selected' : '');?>>중소기업(벤처)</option>
					<option value="s3_1_2" class="s3_1" <?php echo (($myinfo['data']['comp_status3'] ==  's3_1_2') ? 'selected' : '');?>>중소기업(비벤처)</option>
					<option value="s3_1_3" class="s3_1" <?php echo (($myinfo['data']['comp_status3'] ==  's3_1_3') ? 'selected' : '');?>>대기업</option>
					<option value="s3_2_1" class="s3_2" <?php echo (($myinfo['data']['comp_status3'] ==  's3_2_1') ? 'selected' : '');?>>없음</option>
				</select>
			</div>
			<!-- //법인에서도 영리일 경우에만 -->
			<!-- //법인일 경우에만 -->
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><span class="compls">벤처기업지정</span></th>
		<td>
			<div class="radio">
				<label>
					<input type="radio" name="venture_ap" class="required" value="Y" <?php echo set_checkbox('venture_ap', 'Y', ($myinfo['data']['venture_ap'] == 'Y')); ?>/>
					<i class="iCheck">체크</i>
					<span>상장</span>
				</label>
				<label>
					<input type="radio" name="venture_ap" class="required" value="N" <?php echo set_checkbox('venture_ap', 'Y', ($myinfo['data']['venture_ap'] == 'N')); ?>/>
					<i class="iCheck">체크</i>
					<span>비상장</span>
				</label>
			</div>
			<div class="datesel">
				<div class="input-group">
					<label for="venture_date" class="hide">벤처기업지정일</label>				
					<input type="date" name="venture_date" id="venture_date" class="form-control" value="<?php echo $myinfo['data']['venture_date'];?>" placeholder="벤처기업지정일 입력" />
					<i class="icon-calendar-3"><span class="hide">달력</span></i>
				</div>
			</div>
		</td>
		<th scope="row"><label for="venture_num1">벤처기업번호</label></th>
		<td>
			<div class="corpregNum">
				<div class="input-group">
					<input type="number" name="venture_num1" id="venture_num1" class="form-control" value="<?php echo $myinfo['data']['venture_num1'];?>" title="벤처기업번호 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="venture_num2" id="venture_num2" class="form-control" value="<?php echo $myinfo['data']['venture_num2'];?>" title="벤처기업번호 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="number" name="venture_num3" id="venture_num3" class="form-control" value="<?php echo $myinfo['data']['venture_num3'];?>" title="벤처기업번호 끝자리 입력" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><span class="compls">기업상장여부</span></th>
		<td>
			<div class="radio">
				<label>
					<input type="radio" name="listed_check" class="required" value="E" <?php echo set_checkbox('listed_check', 'E', ($myinfo['data']['listed_check'] == 'E')); ?>/>
					<i class="iCheck">체크</i>
					<span>거래소</span>
				</label>
				<label>
					<input type="radio" name="listed_check" class="required" value="K" <?php echo set_checkbox('listed_check', 'K', ($myinfo['data']['listed_check'] == 'K')); ?>/>
					<i class="iCheck">체크</i>
					<span>코스닥</span>
				</label>
				<label>
					<input type="radio" name="listed_check" class="required" value="N" <?php echo set_checkbox('listed_check', 'N', ($myinfo['data']['listed_check'] == 'N')); ?>/>
					<i class="iCheck">체크</i>
					<span>비상장</span>
				</label>
			</div>
		</td>
		<th scope="row"><label for="listed_day">상장일자</label></th>
		<td>
			<div class="datesel">
				<div class="input-group">
					<input type="date" name="listed_day" id="listed_day" class="form-control" value="<?php echo set_value('listed_day', $myinfo['data']['listed_day']); ?>" placeholder="상장일자 입력" />
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
					<input type="radio" name="attached_lab" class="required" value="Y" <?php echo set_checkbox('attached_lab', 'Y', ($myinfo['data']['attached_lab'] == 'Y')); ?>/>
					<i class="iCheck">체크</i>
					<span>유</span>
				</label>
				<label>
					<input type="radio" name="attached_lab" class="required" value="N" <?php echo set_checkbox('attached_lab', 'N', ($myinfo['data']['attached_lab'] == 'N')); ?>/>
					<i class="iCheck">체크</i>
					<span>무</span>
				</label>
			</div>
		</td>
		<th scope="row"><label for="attached_addr">소재지</label></th>
		<td><input type="text" name="attached_addr" id="attached_addr" class="form-control" value="<?php echo set_value('attached_addr', $myinfo['data']['attached_addr']); ?>" placeholder="소재지 입력" /></td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="ceo_name" class="compls">대표자명</label></th>
		<td><input type="text" name="ceo_name" id="ceo_name" class="form-control required" value="<?php echo set_value('ceo_name', $myinfo['data']['ceo_name']); ?>" placeholder="대표자명 입력" /></td>
		<th scope="row"><label for="comp_email" class="compls">이메일</label></th>
		<td><input type="text" name="comp_email" id="comp_email" class="form-control required" value="<?php echo set_value('ceo_name', $myinfo['data']['comp_email']); ?>" placeholder="이메일주소 입력" /></td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="address1" class="compls">사업장주소</label></th>
		<td colspan="3">
			<div class="company_address radioChk">
				<div class="input-group">
					<input type="text" name="address1" id="address1" class="form-control required" value="<?php echo set_value('address1', $myinfo['data']['address1']); ?>" placeholder="사업장 주소 입력" />
					<input type="text" name="address2" id="address2" class="form-control" value="<?php echo set_value('address2', $myinfo['data']['address2']); ?>" placeholder="사업장 상세주소 입력" title="사업장 상세주소 입력" />
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="address_status" value="Y" <?php echo set_checkbox('address_status', 'Y', ($myinfo['data']['address_status'] == 'Y')); ?>/>
						<i class="iCheck">체크</i>
						<span>자가</span>
					</label>
					<label>
						<input type="radio" name="address_status" value="N" <?php echo set_checkbox('address_status', 'N', ($myinfo['data']['address_status'] == 'N')); ?>/>
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
					<input type="text" name="phone1" id="phone1" class="form-control required" value="<?php echo set_value('phone1', $myinfo['data']['phone1']); ?>" title="대표전화 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text" name="phone2" id="phone2" class="form-control required" value="<?php echo set_value('phone2', $myinfo['data']['phone2']); ?>" title="대표전화 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text" name="phone3" id="phone3" class="form-control required" value="<?php echo set_value('phone3', $myinfo['data']['phone3']); ?>" title="대표전화 끝자리 입력" />
				</div>
			</div>
		</td>
		<th scope="row"><label for="fax1" class="compls">팩스번호</label></th>
		<td>
			<div class="telephone">
				<div class="input-group">
					<input type="text" name="fax1" id="fax1" class="form-control required" value="<?php echo set_value('fax1', $myinfo['data']['fax1']); ?>" title="팩스번호 첫자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text" name="fax2" id="fax2" class="form-control required" value="<?php echo set_value('fax2', $myinfo['data']['fax2']); ?>" title="팩스번호 중간자리 입력" />
				</div>
				<div class="input-group">
					<span class="input-group-addon">-</span>
					<input type="text" name="fax3" id="fax3" class="form-control required" value="<?php echo set_value('fax3', $myinfo['data']['fax3']); ?>" title="팩스번호 끝자리 입력" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="business_status" class="compls">주업태</label></th>
		<td><input type="text" name="business_status" id="business_status" class="form-control required" value="<?php echo set_value('fax3', $myinfo['data']['business_status']); ?>" placeholder="주업태 입력" /></td>
		<th scope="row"><label for="business_type" class="compls">주업종</label></th>
		<td><input type="text" name="business_type" id="business_type" class="form-control required" value="<?php echo set_value('business_type', $myinfo['data']['business_type']); ?>" placeholder="주업종 입력" /></td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="content">주제품명</label></th>
		<td colspan="3"><input type="text" id="content" name="content" class="form-control" value="<?php echo set_value('content', $myinfo['data']['content']); ?>" placeholder="주제품명 입력" /></td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="homepage">홈페이지</label></th>
		<td colspan="3"><input type="text" name="homepage" id="homepage" class="form-control" value="<?php echo set_value('homepage', $myinfo['data']['homepage']); ?>" placeholder="홈페이지 입력" /></td>
	</tr>
	<tr>
		<th scope="rowgroup" rowspan="4">공통<br/>서류</th>
		<th scope="row"><label for="biz_license" class="compls">사업자등록증</label></th>
		<td colspan="3">
			<?php echo (!empty($myinfo['data']['fileList']['com_file1']) ? $myinfo['data']['fileList']['com_file1'] : ''); ?>
			<div class="fileForm">
				<span class="iptfile"><input type="file" name="add_file[com_file1][]" id="biz_license" class="form-control <?php echo (empty($myinfo['data']['fileList']['com_file1']) ? 'required' : ''); ?>" placeholder="사업자등록증 파일 등록" /></span>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="financial_state" class="compls">재무재표(최근2년)</label></th>
		<td colspan="3">
			<?php echo (!empty($myinfo['data']['fileList']['com_file2']) ? $myinfo['data']['fileList']['com_file2'] : ''); ?>
			<div class="fileForm">
				<span class="iptfile"><input type="file" name="add_file[com_file2][]" id="financial_state" class="form-control <?php echo (empty($myinfo['data']['fileList']['com_file2']) ? 'required' : ''); ?>" placeholder="재무재표(최근2년) 파일 등록" /></span>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="certify_papers" class="compls">각종인증서류</label></th>
		<td colspan="3">
			<?php echo (!empty($myinfo['data']['fileList']['com_file3']) ? $myinfo['data']['fileList']['com_file3'] : ''); ?>
			<div class="fileForm">
				<span class="iptfile"><input type="file" name="add_file[com_file3][]" id="certify_papers" class="form-control <?php echo (empty($myinfo['data']['fileList']['com_file3']) ? 'required' : ''); ?>" placeholder="각종인증서류 파일 등록" /></span>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw"><label for="industry_papers" class="compls">산업재산권 서류</label></th>
		<td colspan="3">
			<?php echo (!empty($myinfo['data']['fileList']['com_file4']) ? $myinfo['data']['fileList']['com_file4'] : ''); ?>
			<div class="fileForm">
				<span class="iptfile"><input type="file" name="add_file[com_file4][]" id="industry_papers" class="form-control <?php echo (empty($myinfo['data']['fileList']['com_file4']) ? 'required' : ''); ?>" placeholder="산업재산권 서류 파일 등록" /></span>
				</div>
		</td>
	</tr>
	<tr>
		<th scope="rowgroup">사업<br/>서류</th>
		<th scope="row"></th>
		<td colspan="3">
			<div class="biz_papers_wrap" data-area="add">
				<span class="input-group-addon">
					<label for="add_file[add_file_text][]" class="hide">사업 서류명 입력</label>
					<input type="text" id="add_file[add_file_text][]" name="add_file[add_file_text][]" class="form-control" placeholder="서류명 입력" />
				</span>
				<div class="fileForm">
					<label for="add_file[add_file][]" class="hide">서류 파일 등록</label>
					<span class="iptfile"><input type="file" id="add_file[add_file][]" name="add_file[add_file][]" class="form-control" placeholder="서류 파일 등록" /></span>
				</div>
				<span class="input-group-btn">
					<button type="button" class="btn btn-default _add">추가</button>
					<button type="button" class="btn btn-delete _del">삭제</button>
				</span>
			</div>
			<!-- 서류 리스트 -->
			<?php echo (!empty($myinfo['data']['fileList']['add_file']) ? $myinfo['data']['fileList']['add_file'] : ''); ?>
		</td>
	</tr>
	</tbody>
	</table>

	<p class="btn_wrap text-center">
		<button type="submit" class="btn btn-primary"><span class="icon-ok-circle">접수확인</span></button>
		<a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$this->link->get_segment('pid', FALSE), 'pid'=>NULL)); ?>" class="btn btn-cancel"><span class="icon-cancel-circle">취소</span></a>
	</p>
</form>
</div>