<!-- 게시판 뷰 -->
<div class="table_wrap">
	<table class="bbs_table viewType"><!-- 뷰형식의 테이블일 때 "viewType"추가 -->
		<caption>게시물 뷰 - 번호, 제목, 등록일, 조회수로 나뉘어 설명</caption>
		<colgroup><col /></colgroup>
		<thead>
		<tr>
			<td scope="row" class="subject">
				<strong><?php echo $data['subject']; ?></strong>
			</td>
		</tr>
		<tr>
			<td scope="row" class="tblcnts_info">
				<div class="tblcnts_info_wrap">
					<dl>
						<dt>작성자</dt>
						<dd><?php echo $data['name']; ?></dd>
					</dl>
					<dl>
						<dt>작성일</dt>
						<dd><?php echo date('Y.m.d H:i', strtotime($data['created'])); ?></dd>
						<dt>조회수</dt>
						<dd><?php echo $data['hit']; ?></dd>
					</dl>
				</div>
			</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="tbl_cnts">
          <?php echo $data['contents']; ?>
			</td>
		</tr>
    <?php if( ! empty($files[$data['id']])) : ?>
			<tr>
				<td>
					<div class="attachment_wrap">
						<ul class="attachment">
                <?php foreach($files[$data['id']] as $item) : ?>
									<li>
										<a href="<?php echo $item['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($item['download_count']); ?> 회"><?php echo $item['ext_icon']; ?> <?php echo $item['orig_name']; ?><span class="file_size">(<?php echo human_filesize($item['file_size']); ?>)</span></a>
									</li>
                <?php endforeach; ?>
						</ul>
					</div>
				</td>
			</tr>
    <?php endif; ?>
		</tbody>
	</table>

	<div class="table_footer">
			<?php if($this->auth->check(array('action'=>'change_status')) == TRUE AND in_array('status', $this->setup['list']) == TRUE) : ?>
				<div class="btn_wrap">
					<i class="bbs_status_process fa fa-spinner fa-spin pull-left" style="display:none; margin-top:8px"></i>
					<i class="bbs_status_save fa fa-check pull-left" style="display:none; margin-top:8px; color:rgb(113, 174, 9);"></i>
					<select class="form-control input-sm select_status">
						<option value="">대기</option>
						<option value="1" <?php echo set_select('status', '1', ($data['status'] == '1')); ?>>접수</option>
						<option value="2" <?php echo set_select('status', '2', ($data['status'] == '2')); ?>>완료</option>
						<option value="3" <?php echo set_select('status', '3', ($data['status'] == '3')); ?>>반려</option>
					</select>
				</div>
			<?php endif; ?>
			<?php
			$params = array(
					'pid' => $data['id']
			);
			//echo CI::$APP->_comment($params);
			?>
		<p class="btn_wrap ft_right">
			<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn">수정</a>
      <?php endif; ?>
			<?php if($this->auth->check(array('action'=>'delete')) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" class="btn">삭제</a>
      <?php endif; ?>
			<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn">목록</a>
		</p>
	</div>
</div>
<!-- //게시판 뷰 -->
