<div class="tablewrap">

	<?php echo validation_errors(); ?>
	<?php echo form_open($this->link->get(array('action'=>'submit')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
	<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
	
	<div class="basic_box bizNotify_wrap">
		<dl class="bizNotify_info">
		<dt>분&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;류 :</dt>
		<dd><?php echo $data['category_title'];?></dd>
		<dt>사&nbsp;&nbsp;업&nbsp;&nbsp;명 :</dt>
		<dd><strong><?php echo $data['subject'];?></strong></dd>
		<dt>공고기관 :</dt>
		<dd><?php echo (!empty($data['startdate']) || !empty($data['enddate']) ? date('Y.m.d H:i', strtotime($data['startdate']))." ~ ".date('Y.m.d H:i', strtotime($data['enddate'])) : "-"); ?></dd>
		</dl>
	</div>

	<table class="bbs_table view">
	<caption>사업공고 접수 입력 폼</caption>
	<colgroup>
		<col width="80px" /><col width="155px" /><col /><col width="130px" /><col />
	</colgroup>
	<tbody>
	<tr>
		<th scope="rowgroup" rowspan="2">담당자 <br/>정보</th>
		<th scope="row">이름</th>
		<td colspan="3"><?php echo $data['chief_name'];?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">이메일</th>
		<td><?php echo $data['chief_email'];?></td>
		<th scope="row">연락처</th>
		<td><?php echo $data['chief_phone1'].(!empty($data['chief_phone2']) ? '-'.$data['chief_phone2'] : '').(!empty($data['chief_phone3']) ? '-'.$data['chief_phone3'] : ''); ?></td>
	</tr>
	<tr>
		<th scope="rowgroup" rowspan="12">기업<br/>정보 <br/>입력</th>
		<th scope="row">회사명</th>
		<td><?php echo $data['compname']; ?></td>
		<th scope="row">사업자등록번호</th>
		<td><?php echo $data['comp_num']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">설립일자</th>
		<td><?php echo $data['found_day']; ?></td>
		<th scope="row">법인등록번호</th>
		<td><?php echo $data['corporate_num']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">사업체구분</th>
		<td colspan="3">
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
				case 's3_1' : echo ' &gt; 법인(영리)';
				break;
				case 's3_2' : echo ' &gt; 법인(비영리)';
				break;
				default;
			}
			switch($data['comp_status3']) {
				case 's3_1_1' : echo ' &gt; 중소기업(벤처)';
				break;
				case 's3_1_2' : echo ' &gt; 중소기업(비벤처)';
				break;
				case 's3_1_3' : echo ' &gt; 대기업';
				break;
				default;
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row" class="blw">벤처기업지정</th>
		<td><?php echo ($data['venture_ap'] == 'Y' ? '지정' : '미지정').(strtotime($data['venture_date']) == '' ? '' : ' ('.$data['venture_date'].')'); ?></td>
		<th scope="row">벤처기업번호</th>
		<td><?php echo $data['venture_num']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">기업상장여부</th>
		<td>
			<?php 
			if ($data['listed_check'] == 'E') 
			{
				echo '거래소';
			} 
			else if ($data['listed_check'] == 'K') 
			{
				echo '코스닥';
			} 
			else if ($data['listed_check'] == 'N') 
			{
				echo '비상장';
			}
			?>
		</td>
		<th scope="row">상장일자</th>
		<td><?php echo (strtotime($data['listed_day']) == '' ? '' : ' ('.$data['listed_day'].')'); ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">기업부설연구소</th>
		<td><?php echo ($data['attached_lab'] == 'Y' ? '유' : '무'); ?></td>
		<th scope="row">소재지</th>
		<td><?php echo $data['attached_addr']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">대표자명</th>
		<td><?php echo $data['ceo_name']; ?></td>
		<th scope="row">이메일</th>
		<td><?php echo $data['comp_email']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">사업장주소</th>
		<td colspan="3"><?php echo ($data['address_status'] == 'Y' ? '(자가)' : '(임대)'); ?> <?php echo $data['address1']; ?> <?php echo $data['address2']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">대표전화</th>
		<td><?php echo $data['phone1'].(!empty($data['phone2']) ? '-'.$data['phone2'] : '').(!empty($data['phone3']) ? '-'.$data['phone3'] : ''); ?></td>
		<th scope="row">팩스번호</th>
		<td><?php echo $data['fax1'].(!empty($data['fax2']) ? '-'.$data['fax2'] : '').(!empty($data['fax3']) ? '-'.$data['fax3'] : ''); ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">주업태</th>
		<td><?php echo $data['business_status']; ?></td>
		<th scope="row">주업종</th>
		<td><?php echo $data['business_type']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">주제품명</th>
		<td colspan="3"><?php echo $data['content']; ?></td>
	</tr>
	<tr>
		<th scope="row" class="blw">홈페이지</th>
		<td colspan="3"><?php echo $data['homepage']; ?></td>
	</tr>
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
	<tr>
		<?php if($i == "1"):?>
		<th scope="rowgroup" rowspan="4">공통<br/>서류</th>
		<?php endif;?>
		<th scope="row"><?php echo $file_title;?></th>
		<td colspan="3">
			<?php if(!empty($item)):?>
			<div class="file_attach"><a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$data['com_files']['com_file'.$i][0]['id']), TRUE); ?>"><?php echo ext($data['com_files']['com_file'.$i][0]['file_ext']); ?> <?php echo $data['com_files']['com_file'.$i][0]['orig_name']; ?></a></div>
			<?php endif;?>
		</td>
	</tr>
	<?php endfor;?>
	
	<?php if(!empty($data['add_files']['add_file'])) : ?>
	<tr>
		<th scope="rowgroup">사업<br/>서류</th>
		<th scope="row"></th>
		<td colspan="3">
			<ul class="biz_papers_lst">
				<?php foreach($data['add_files']['add_file'] as $k => $item) : ?>
				<li><strong><?php echo $item['file_text'];?></strong> <a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE); ?>"><?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</td>
	</tr>
	<?php endif;?>
	</tbody>
	</table>

	<p class="btn_wrap text-center">
		<button type="submit" class="btn btn-primary"><span class="icon-ok-circle">최종접수</span></button>
		<a href="<?php echo $this->link->get(array('action'=>'apply', 'id'=>NULL, 'pid'=>$this->link->get_segment('pid', FALSE))); ?>" class="btn btn-default"><span class="icon-edit">수정</span></a>
		<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL, 'pid'=>NULL)); ?>" class="btn btn-default"><span class="icon-menu">목록</span></a>
	</p>
</form>
</div>