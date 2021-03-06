<!-- 게시판 뷰 -->
<div class="table_wrap">
	<table class="bbs_table viewType"><!-- 뷰형식의 테이블일 때 "viewType"추가 -->
		<caption>게시물 뷰 - 번호, 제목, 등록일, 조회수로 나뉘어 설명</caption>
		<colgroup><col /></colgroup>
		<tbody>
		<tr>
			<td scope="row" class="subject">
				<strong><?php echo $data['title']; ?></strong>
			</td>
		</tr>
		<tr>
			<td scope="row" class="tblcnts_info">
				<div class="tblcnts_info_wrap">
					<dl>
						<dt>업체명</dt>
						<dd><?php echo $data['company']; ?></dd>
					</dl>
					<dl>
						<dt>담당자</dt>
						<dd><?php echo $data['name']; ?></dd>
						<dt>담당자 연락처</dt>
						<dd><?php echo $data['phone1']; ?>.<?php echo $data['phone2']; ?>.<?php echo $data['phone3']; ?></dd>
					</dl>
				</div>
			</td>
		</tr>
		<tr>
			<td scope="row" class="tblcnts_info">
				<div class="tblcnts_info_wrap">
					<dl>
						<dt>배송지</dt>
						<dd><?php echo $data['delivery_area']; ?></dd>
					</dl>
					<dl>
						<dt>배송요청일</dt>
						<dd><?php echo $data['delivery_date']; ?></dd>
						<dt>시공일</dt>
						<dd><?php echo $data['build_date']; ?></dd>
					</dl>
				</div>
			</td>
		</tr>
		<tr>
			<td scope="row" class="tblcnts_info">
				<div class="tblcnts_info_wrap">
					<dl>
						<dt>전달사항</dt>
						<dd><?php echo $data['message']; ?></dd>
					</dl>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<br />
	<table class="bbs_table viewType">
		<caption>조명 내역 입력</caption>
		<colgroup><col style="width:145px" /><col /><col style="width:125px" /><col style="width:145px"/><col style="width:145px"/></colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="detail_cnts">공간/용도</label></th>
			<th scope="row">품명</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">수량</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">조명색</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">램프</th>
		</tr>
      <?php foreach ($light_list['data'] as $item) : ?>
				<tr>
					<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $light_list['light_data'][$item['id']][0]['id']) ? '': $light_list['light_data'][$item['id']][0]['id'] ); ?>"/>
					<td><?php echo $item['title']; ?></td>
					<td><?php echo empty( $light_list['light_data'][$item['id']][0]['title']) ? '': $light_list['light_data'][$item['id']][0]['title'] ?></td>
					<td><?php echo empty( $light_list['light_data'][$item['id']][0]['cnt']) ? '': $light_list['light_data'][$item['id']][0]['cnt'] ?></td>
					<td><?php echo empty( $light_list['light_data'][$item['id']][0]['color']) ? '': $light_list['light_data'][$item['id']][0]['color'] ?></td>
					<td><?php echo empty( $light_list['light_data'][$item['id']][0]['lamp']) ? '': $light_list['light_data'][$item['id']][0]['lamp'] ?></td>
				</tr>
      <?php endforeach; ?>
		</tbody>
	</table>
    <?php if( ! empty($files[$data['id']])) : ?>
		<tr>
			<td class="attachment">
				<div class="attachment_wrap">
					<ul>
        		<?php foreach($files[$data['id']] as $item) : ?>
						<li>
							<a href="<?php echo $item['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($item['download_count']); ?> 회"><?php echo $item['ext_icon']; ?> <?php echo $item['orig_name']; ?><span class="file_size">(<?php echo human_filesize($item['file_size']); ?>)</span></a>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<div class="table_footer">
		<p class="btn_wrap ft_right">
        <?php if($this->auth->check(array('action'=>'write')) == TRUE && $data['type'] !='완료') : ?>
					<a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn">수정</a>
        <?php endif; ?>
        <?php if($this->auth->check(array('action'=>'delete')) == TRUE && $data['type'] !='완료') : ?>
					<a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" onclick="return confirm('삭제 하시겠습니까?');" class="btn">삭제</a>
        <?php endif; ?>
			<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn">목록</a>
		</p>
	</div>
</div>
<!-- //게시판 뷰 -->


<!-- 관리자 발주 신청 관리 -->
<?php if($this->account->get('group') == 13) : ?>
	<?php echo validation_errors(); ?>
	<div class="table_wrap inputFrm">
			<?php echo form_open($this->link->get(array('action'=>'order_save')), array('id'=>'writeForm')); ?>
			<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
			<table class="bbs_table viewType"><!-- 뷰형식의 테이블일 때 "viewType"추가 -->
			<caption>게시물 입력 - 고객사명, 담당자명, 담당자연락처, 제목, 내용, 파일로 나뉘어 입력</caption>
			<colgroup><col style="width:145px" /><col /></colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="clientName">발주상태</label></th>
				<td>
					<div class="phone_wrap">
						<div class="select_box">
							<label for="category">발주상태</label>
							<select class="info_select" id="category" title="발주상태 선택" name="type">
								<option value="">-- 발주상태 --</option>
								<option value="대기" <?php echo set_select('type', '대기', ($data['type']=='대기')); ?>>발주대기</option>
								<option value="완료" <?php echo set_select('type', '완료', ($data['type']=='완료')); ?>>발주완료</option>
								<option value="취소" <?php echo set_select('type', '취소', ($data['type']=='취소')); ?>>발주취소</option>
							</select>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="detail_cnts">비고</label></th>
				<td >
					<textarea id="etc" name="etc" ><?php echo nl2br($data['etc']); ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="table_footer">
			<p class="btn_wrap btn_regist">
				<button type="submit" class="btn btn-primary"><span><i class="pe-7s-check" aria-hidden="true"></i>상태수정</span></button>
			</p>
		</div>
		</form>
	</div>
<?php endif; ?>