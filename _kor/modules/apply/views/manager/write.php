<?php echo validation_errors(); ?>
<?php echo form_open_multipart($this->link->get(array('action'=>'save')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="modify_count" value="<?php echo set_value('modify_count', $data['modify_count']); ?>" />

<fieldset>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업분류</label>
		<div class="col-sm-10 row">
			<div class="col-md-2 col-sm-3 pdr5">
				<select name="category" id="category" class="form-control input-sm">
					<option value="">-- 분류 --</option>
					<?php foreach($category['data'] as $item) : ?>
					<option value="<?php echo $item['id']; ?>" <?php echo set_select('category', $item['id'], ($item['id']==$data['category'])); ?>><?php echo $item['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업공고</label>
		<div class="col-sm-10 row">
			<div class="col-sm-4">
				<select name="subject" id="subject" class="form-control input-sm">
					<option value="">-- 사업공고를 선택해 주세요 --</option>
					<?php if( ! empty($data['id'])) : ?>
						<?php foreach($subject['data'] as $item) : ?>
						<option value="<?php echo $item['id']; ?>" <?php echo set_select('subject', $item['id'], ($item['id']==$data['pid'])); ?>><?php echo $item['subject']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?> 
				</select>
			</div>
		</div>
	</div>
</fieldset>

<br/>
<br/>
<fieldset>
	<h5><strong>담당자정보등록</strong></h5>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>회원 아이디</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="hidden" name="ismember" value="<?php echo set_value('ismember', $data['ismember']); ?>" />
					<input type="text" name="userid" class="form-control input-sm required" readonly="readonly" value="<?php echo set_value('userid', $data['userid']); ?>" placeholder="아이디 입력"/>
					<span class="input-group-btn">
						<a href="<?php echo $this->link->get(array('action'=>'member_search', 'sh_text'=>NULL, 'page'=>NULL)); ?>" class="btn btn-default btn-sm _popup" data-maxwidth="600">회원검색</a>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자명</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="chief_name" class="form-control input-sm required" value="<?php echo set_value('chief_name', $data['chief_name']); ?>" placeholder="이름 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자 이메일</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="email" name="chief_email" class="form-control input-sm required" value="<?php echo set_value('chief_email', $data['chief_email']); ?>" placeholder="이메일 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자 연락처</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="number" name="chief_phone1" class="form-control input-sm number" maxlength="3" value="<?php echo set_value('chief_phone1', $data['chief_phone1']); ?>" title="연락처 첫자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="chief_phone2" class="form-control input-sm number" maxlength="4" value="<?php echo set_value('chief_phone2', $data['chief_phone2']); ?>" title="연락처 중간자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="chief_phone3" class="form-control input-sm number" maxlength="4" value="<?php echo set_value('chief_phone3', $data['chief_phone3']); ?>"title="연락처 끝자리 입력" />
				</div>
			</div>
		</div>
	</div>
</fieldset>

<br/>
<br/>
<fieldset>
	<h5><strong>기업정보등록</strong></h5>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>회사명</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="compname" class="form-control input-sm required" value="<?php echo set_value('compname', $data['compname']); ?>" placeholder="회사명 입력" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>설립일자</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="date" name="found_day" class="form-control input-sm required" value="<?php echo set_value('found_day', $data['found_day']); ?>" placeholder="설립일 입력" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업자등록번호</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="number" name="comp_num1" value="<?php echo set_value('comp_num1', $data['comp_num1']); ?>" class="form-control input-sm" title="사업자등록번호 첫자리 입력" />
					<span class="input-group-btn">-</span>
					<input type="number" name="comp_num2" value="<?php echo set_value('comp_num2', $data['comp_num2']); ?>" class="form-control input-sm" title="사업자등록번호 중간자리 입력" />
					<span class="input-group-btn">-</span>
					<input type="number" name="comp_num3" value="<?php echo set_value('comp_num3', $data['comp_num3']); ?>" class="form-control input-sm" title="사업자등록번호 끝자리 입력" />
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">법인등록번호</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="number" name="corporate_num1" value="<?php echo set_value('corporate_num1', $data['corporate_num1']); ?>" class="form-control input-sm" title="법인등록번호 첫자리 입력" />
					<span class="input-group-btn">-</span>
					<input type="number" name="corporate_num2" value="<?php echo set_value('corporate_num2', $data['corporate_num2']); ?>" class="form-control input-sm" title="법인등록번호 중간자리 입력" />
					<span class="input-group-btn">-</span>
					<input type="number" name="corporate_num3" value="<?php echo set_value('corporate_num3', $data['corporate_num3']); ?>" class="form-control input-sm" title="법인등록번호 끝자리 입력" />
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업체구분</label>
		<div class="col-sm-10 row">
			<div class="col-sm-4">
				<span class="input-group-btn">
					<select id="comp_status1" name="comp_status1" class="form-control input-sm">
						<option value="">대분류</option>
						<option value="s1" <?php echo (($data['comp_status1'] ==  's1') ? 'selected' : '');?>>개인사업체</option>
						<option value="s2" <?php echo (($data['comp_status1'] ==  's2') ? 'selected' : '');?>>정부기관</option>
						<option value="s3" <?php echo (($data['comp_status1'] ==  's3') ? 'selected' : '');?>>법인</option>
					</select>
				</span>
				<span class="input-group-btn">
					<select id="comp_status2" name="comp_status2" class="form-control input-sm">
						<option value="">중분류</option>
						<option value="s3_1" class="s3" <?php echo ($data['comp_status2'] ==  's3_1') ? 'selected' : '';?>>법인(영리)</option>
						<option value="s3_2" class="s3" <?php echo ($data['comp_status2'] ==  's3_2') ? 'selected' : '';?>>법인(비영리)</option>
					</select>
				</span>
				<span class="input-group-btn">
					<select id="comp_status3" name="comp_status3" class="form-control input-sm">
						<option value="">소분류</option>
						<option value="s3_1_1" class="s3_1" <?php echo ($data['comp_status3'] ==  's3_1_1') ? 'selected' : '';?>>중소기업(벤처)</option>
						<option value="s3_1_2" class="s3_1" <?php echo ($data['comp_status3'] ==  's3_1_2') ? 'selected' : '';?>>중소기업(비벤처)</option>
						<option value="s3_1_3" class="s3_1" <?php echo ($data['comp_status3'] ==  's3_1_3') ? 'selected' : '';?>>대기업</option>
						<option value="s3_2_1" class="s3_2" <?php echo ($data['comp_status3'] ==  's3_2_1') ? 'selected' : '';?>>없음</option>
					</select>
				</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>벤처기업지정</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group-btn">
					<label class="radio-inline">
						<input type="radio" name="venture_ap" value="N" <?php echo set_checkbox('venture_ap', 'N', ($data['venture_ap']=='N')); ?> />
						<span>미지정</span>
					</label>
					<label class="radio-inline">
						<input type="radio" name="venture_ap" value="Y" <?php echo set_checkbox('venture_ap', 'Y', ($data['venture_ap']=='Y')); ?>/>
						<span>지정</span>
					</label>
				</div>
				<div class="input-group-btn">
					<input type="date" name="venture_date" class="form-control input-sm" value="<?php echo set_value('venture_date', $data['venture_date']); ?>" placeholder="지정일 입력" />
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">벤처기업번호</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" name="venture_num1" class="form-control input-sm required" value="<?php echo set_value('venture_num1', $data['venture_num1']); ?>" title="벤처기업번호 첫자리 입력" /> 
					<span class="input-group-btn">-</span>
					<input type="text" name="venture_num2" class="form-control input-sm required" value="<?php echo set_value('venture_num2', $data['venture_num2']); ?>" title="벤처기업번호 중간자리 입력" /> 
					<span class="input-group-btn">-</span>
					<input type="text" name="venture_num3" class="form-control input-sm required" value="<?php echo set_value('venture_num3', $data['venture_num3']); ?>" title="벤처기업번호 끝자리 입력" /> 
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>기업상장여부</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<label class="radio-inline">
					<input type="radio" name="listed_check" value="E" <?php echo set_checkbox('listed_check', 'E', ($data['listed_check'] == 'E')); ?>/>
					<span>거래소</span>
				</label>
				<label class="radio-inline">
					<input type="radio" name="listed_check" value="K" <?php echo set_checkbox('listed_check', 'K', ($data['listed_check'] == 'K')); ?>/>
					<span>코스닥</span>
				</label>
				<label class="radio-inline">
					<input type="radio" name="listed_check" value="N" <?php echo set_checkbox('listed_check', 'N', ($data['listed_check'] == 'N')); ?>/>
					<span>비상장</span>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">상장일자</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="date" name="listed_day" class="form-control input-sm" value="<?php echo set_value('listed_day', $data['listed_day']); ?>" placeholder="상장일 입력" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>기업부설연구소</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<label class="radio-inline">
					<input type="radio" name="attached_lab" value="Y" <?php echo set_checkbox('attached_lab', 'Y', ($data['attached_lab'] == 'Y')); ?>/>
					<span>유</span>
				</label>
				<label class="radio-inline">
					<input type="radio" name="attached_lab" value="N" <?php echo set_checkbox('attached_lab', 'N', ($data['attached_lab'] == 'N')); ?>/>
					<span>무</span>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">소재지</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="attached_addr" class="form-control input-sm" value="<?php echo set_value('attached_addr', $data['attached_addr']); ?>" placeholder="소재지 입력" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>대표자명</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="ceo_name" class="form-control input-sm" value="<?php echo set_value('ceo_name', $data['ceo_name']); ?>" placeholder="대표자명 입력" />
			</div>
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>이메일</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="email" name="comp_email" class="form-control input-sm" value="<?php echo set_value('comp_email', $data['comp_email']); ?>" placeholder="이메일 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업장주소</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" name="zipcode" class="form-control input-sm" value="<?php echo set_value('zipcode', $data['zipcode']); ?>" />
					<span class="input-group-btn">
						<a href="<?php echo CI::$APP->_zipcode_link(); ?>" class="btn btn-default btn-sm _popup" data-maxwidth="600">우편번호검색</a>
					</span>
				</div>
			</div>
			<div class="col-lg-12 row">
				<div class="col-sm-8">
					<input type="text" name="address1" class="form-control input-sm" value="<?php echo set_value('address1', $data['address1']); ?>" placeholder="사업장 주소 입력"/>
				</div>
			</div>
			<div class="col-lg-12 row">
				<div class="col-sm-8">
					<input type="text" name="address2" class="form-control input-sm" value="<?php echo set_value('address2', $data['address2']); ?>" placeholder="사업장 상세주소 입력"/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업장구분</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<label class="radio-inline">
					<input type="radio" name="address_status" value="Y" <?php echo set_checkbox('address_status', 'Y', ($data['address_status'] == 'Y')); ?> /> 자가
				</label>
				<label class="radio-inline">
					<input type="radio" name="address_status" value="N" <?php echo set_checkbox('address_status', 'N', ($data['address_status'] == 'N')); ?> /> 임대
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>대표전화</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="number" name="phone1" class="form-control input-sm number" value="<?php echo set_value('phone1', $data['phone1']); ?>" title="대표전화 첫자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="phone2" class="form-control input-sm number" value="<?php echo set_value('phone2', $data['phone2']); ?>" title="대표전화 중간자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="phone3" class="form-control input-sm number" value="<?php echo set_value('phone3', $data['phone3']); ?>" title="대표전화 끝자리 입력"/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>팩스</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<div class="input-group">
					<input type="number" name="fax1" class="form-control input-sm number" value="<?php echo set_value('fax1', $data['fax1']); ?>" title="팩스 첫자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="fax2" class="form-control input-sm number" value="<?php echo set_value('fax2', $data['fax2']); ?>" title="팩스 중간자리 입력"/>
					<span class="input-group-btn">-</span>
					<input type="number" name="fax3" class="form-control input-sm number" value="<?php echo set_value('fax3', $data['fax3']); ?>" /title="팩스 끝자리 입력"/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>주업태</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="business_status" class="form-control input-sm" value="<?php echo set_value('business_status', $data['business_status']); ?>" placeholder="주업태 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>주업종</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="business_type" class="form-control input-sm" value="<?php echo set_value('business_type', $data['business_type']); ?>" placeholder="주업종 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">주제품명</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="content" class="form-control input-sm" value="<?php echo set_value('content', $data['content']); ?>" placeholder="주제품명 입력"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">홈페이지</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="homepage" class="form-control input-sm" value="<?php echo set_value('homepage', $data['homepage']); ?>" placeholder="홈페이지 입력"/>
			</div>
		</div>
	</div>
</fieldset>

<br/>
<br/>
<fieldset>
	<h5><strong>제출서류</strong></h5>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업자등록증</label>
		<div class="col-sm-3">
			<?php echo (!empty($data['fileList']['com_file1']) ? $data['fileList']['com_file1'] : ''); ?>
			<input type="file" class="form-control" name="add_file[com_file1][]" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>재무재표(최근2년)</label>
		<div class="col-sm-3">
			<?php echo (!empty($data['fileList']['com_file2']) ? $data['fileList']['com_file2'] : ''); ?>
			<input type="file" class="form-control" name="add_file[com_file2][]" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>각종인증서류</label>
		<div class="col-sm-3">
			<?php echo (!empty($data['fileList']['com_file3']) ? $data['fileList']['com_file3'] : ''); ?>
			<input type="file" class="form-control" name="add_file[com_file3][]" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>산업재산권 서류</label>
		<div class="col-sm-3">
			<?php echo (!empty($data['fileList']['com_file4']) ? $data['fileList']['com_file4'] : ''); ?>
			<input type="file" class="form-control" name="add_file[com_file4][]" />
		</div>
	</div>

	<div class="form-group" data-area="add">
		<label class="col-sm-2 control-label">추가 사업 서류</label>
		<div class="col-sm-5">
			<div class="input-group">
				<input type="text" name="add_file[add_file_text][]" class="form-control input-sm" placeholder="추가 사업 서류명 입력"/>
				<span class="input-group-btn"></span>
				<input type="file" name="add_file[add_file][]" class="form-control input-sm" />
				<span class="input-group-btn"></span>
				<span class="input-group-btn">
					<a href="#" class="btn btn-primary btn-sm _add"><span><i></i>추가</span></a><a href="#" class="btn btn-danger btn-sm _del"><span>삭제</span></a>
				</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div style="margin-left:290px;">
			<?php echo (!empty($data['fileList']['add_file']) ? $data['fileList']['add_file'] : ''); ?>
		</div>
	</div>
</fieldset>

<fieldset>
<div class="well well-centered">
		<button type="submit" class="btn btn-primary btn-sm">변경내용 저장</button>
		<a class="btn btn-default btn-sm" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
	</div>
</fieldset>

</form>