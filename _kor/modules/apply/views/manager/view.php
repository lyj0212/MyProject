<p class="text-info bg-info" style="padding:20px"><span lang="en" class="label label-danger">Important</span> 회원이 신청한 사업분류와 사업공고가 표시됩니다.</p>
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업분류</label>
		<div class="col-sm-10 row">
			<div class="col-md-2 col-sm-3 pdr5">
				<select name="category" id="category" class="form-control input-sm" data-ismember="<?php echo $data['ismember'];?>">
					<option value="">-- 분류 --</option>
					<?php foreach($myCategory['data'] as $k => $item) : ?>
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
						<?php foreach($myBusiness['data'] as $k => $item) : ?>
						<option value="<?php echo $item['id']; ?>" data-apply="<?php echo $item['apply_id'];?>" <?php echo set_select('subject', $item['id'], ($item['id']==$data['pid'])); ?>><?php echo '['.$item['new_state'].'] '.$item['subject']; ?></option>
						<?php endforeach; ?> 
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">접수일</label>
		<div class="col-sm-10 form-control-static">
			<?php echo date('Y-m-d H:i:s', strtotime($data['created'])); ?>
		</div>
	</div>
	<?php if((int)$data['modify_count'] >0) : ?>
	<div class="form-group">
		<label class="col-sm-2 control-label">수정일</label>
		<div class="col-sm-10 form-control-static">
			<?php echo date('Y-m-d H:i:s', strtotime($data['modified'])); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">수정횟수</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['modify_count'].'회'; ?>
		</div>
	</div>
	<?php endif;?>
	
	<br/>
	<br/>
	<h5><strong>담당자정보</strong></h5>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>회원 아이디</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['userid']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자명</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['chief_name']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자 이메일</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['chief_email']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>담당자 연락처</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['chief_phone1'].(!empty($data['chief_phone2']) ? '-'.$data['chief_phone2'] : '').(!empty($data['chief_phone3']) ? '-'.$data['chief_phone3'] : ''); ?>
		</div>
	</div>
	
	<br/>
	<br/>
	<h5><strong>기업정보등록</strong></h5>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>회사명</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['compname']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>설립일자</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['found_day']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업자등록번호</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['comp_num']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">법인등록번호</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['corporate_num']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업체구분</label>
		<div class="col-sm-10 form-control-static">
			<?php
			switch($data['comp_status1']) {
				case 's1' : echo '개인사업체 ';
				break;
				case 's2' : echo '정부기관 ';
				break;
				case 's3' : echo '법인 ';
				break;
				case ''   : echo '없음';
				break;
				default;
			}
			switch($data['comp_status2']) {
				case 's1_1' : echo ' > 법인(영리)';
				break;
				case 's1_2' : echo ' > 법인(비영리)';
				break;
				default;
			}
			switch($data['comp_status3']) {
				case 's1_1_1' : echo ' > 중소기업(벤처)';
				break;
				case 's1_1_2' : echo ' > 중소기업(비벤처)';
				break;
				case 's1_1_3' : echo ' > 대기업';
				break;
				default;
			}
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>벤처기업지정</label>
		<div class="col-sm-10 form-control-static">
			<?php echo ($data['venture_ap'] == 'Y' ? '지정' : '미지정').(strtotime($data['venture_date']) == '' ? '' : ' ('.$data['venture_date'].')'); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">벤처기업번호</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['venture_num']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>기업상장여부</label>
		<div class="col-sm-10 form-control-static">
			<?php 
			if ($data['listed_check'] == 'E') {
				echo '거래소';
			} else if ($data['listed_check'] == 'K') {
				echo '코스닥';
			} else if ($data['listed_check'] == 'N') {
				echo '비상장';
			}
			?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">상장일자</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['listed_day']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>기업부설연구소</label>
		<div class="col-sm-10 form-control-static">
			<?php echo ($data['attached_lab'] == 'Y' ? '유' : '무'); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">소재지</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['attached_addr']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>대표자명</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['ceo_name']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>이메일</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['comp_email']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업장주소</label>
		<div class="col-sm-10 form-control-static">
			<?php echo (!empty($data['zipcode']) ? '('.$data['zipcode'].')' : ''); ?> <?php echo $data['address1']; ?> <?php echo $data['address2']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>사업장구분</label>
		<div class="col-sm-10 form-control-static">
			<?php echo ($data['address_status'] == 'Y' ? '자가' : '임대'); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>대표전화</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['phone1'].(!empty($data['phone2']) ? '-'.$data['phone2'] : '').(!empty($data['phone3']) ? '-'.$data['phone3'] : ''); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>팩스</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['fax1'].(!empty($data['fax2']) ? '-'.$data['fax2'] : '').(!empty($data['fax3']) ? '-'.$data['fax3'] : ''); ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>주업태</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['business_status']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>주업종</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['business_type']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">주제품명</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['content']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">홈페이지</label>
		<div class="col-sm-10 form-control-static">
			<?php echo $data['homepage']; ?>
		</div>
	</div>
	
	<br/>
	<br/>
	<h5><strong>제출서류</strong></h5>
	<?php 
	for($i=1; $i<=4; $i++):
		$item = array();
		switch ($i) {
			case '1' : $file_title = '사업자등록증'; break;
			case '2' : $file_title = '재무재표(최근2년)'; break;
			case '3' : $file_title = '각종인증서류'; break;
			case '4' : $file_title = '산업재산권 서류'; break;
			default  : $file_title = ''; break;
		}
		if(!empty($data['com_files']['com_file'.$i][0])) $item = $data['com_files']['com_file'.$i][0];
	?> 
	<div class="form-group">
		<label class="col-sm-2 control-label"><?php echo $file_title;?></label>
		<div class="col-sm-10 form-control-static">
			<?php if(!empty($item)):?>
			<div title="<?php echo $data['com_files']['com_file'.$i][0]['orig_name']; ?>">
				<span class="fileName"><a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$data['com_files']['com_file'.$i][0]['id']), TRUE); ?>"><?php echo ext($data['com_files']['com_file'.$i][0]['file_ext']); ?> <?php echo $data['com_files']['com_file'.$i][0]['orig_name']; ?></a></span>
				<span class="fileSize"><?php echo human_filesize($data['com_files']['com_file'.$i][0]['file_size']); ?></span>
			</div>
			<?php endif;?>
		</div>
	</div>
	<?php endfor;?>
	
	<?php if(!empty($data['add_files']['add_file'])) : ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">추가 사업 서류</label>
			<div class="col-sm-10 form-control-static">
				<ul>
					<?php foreach($data['add_files']['add_file'] as $k => $item) : ?>
					<li>
						<strong style="display:inline-block;width:200px;"><?php echo $item['file_text'];?></strong>
						<span style="margin-left:20px;" class="fileName"><a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE); ?>"><?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?></a></span>
						<span class="fileSize"><?php echo human_filesize($item['file_size']); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif;?>


	<div class="well well-sm clearfix">
		<div class="btn-group pull-right">
			<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn btn-default btn-sm">수정</a>
			<?php endif; ?>
			<?php if($this->auth->check(array('action'=>'delete')) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" onclick="return confirm('삭제 하시겠습니까?');" class="btn btn-default btn-sm">삭제</a>
			<?php endif; ?>
			<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn btn-default btn-sm">목록보기</a>
		</div>
	</div>
</div>