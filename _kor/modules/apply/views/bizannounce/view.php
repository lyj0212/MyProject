<div class="tablewrap">
	<table class="bbs_table viewType">
	<caption>"사업공고 - <?php echo $data['subject']; ?>" 상세보기</caption>
	<colgroup>
		<col width="110px" /><col />
	</colgroup>
	<thead>
	<tr>
		<td scope="row" colspan="2" class="subject"><?php echo $data['subject']; ?></td>
	</tr>
	<tr>
		<td scope="row" colspan="2">
			<div class="tbl_cnts_info">
				<dl>
				<dt>분류</dt>
				<dd><?php echo $data['category_title']; ?></dd>
				<dt>공고기간</dt>
				<dd><?php echo (!empty($data['startdate']) || !empty($data['enddate']) ? date('Y.m.d H:i', strtotime($data['startdate']))." ~ ".date('Y.m.d H:i', strtotime($data['enddate'])) : "-"); ?></dd>
				</dl>
				<dl>
				<dt>작성자</dt>
				<dd><?php echo $data['name']; ?></dd>
				<dt>작성일</dt>
				<dd><?php echo date('Y-m-d', strtotime($data['created'])); ?></dd>
				</dl>
			</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td scope="row" colspan="2" class="cnts">
			<?php echo $data['contents']; ?>
		</td>
	</tr>
	<?php if( ! empty($files[$data['id']])) : ?>
	<tr>
		<th scope="row">첨부파일</th>
		<td>
			<ul class="file_lst">
				<?php foreach($files[$data['id']] as $item) : ?>
				<li><a href="<?php echo $item['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($item['download_count']); ?> 회"><span class="icon-download-6"></span> <?php echo $item['orig_name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</td>
	</tr>
	<?php endif;?>
	</tbody>
	</table>

	<p class="btn_wrap text-center">
		<span class="page_prevnext">
			<?php if((int)($prev['data']['id'])>0) :?>
			<a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$prev['data']['id'])); ?>" class="btn btn-sm"><span>이전글</span></a>
			<?php endif;?>
			<?php if((int)($next['data']['id'])>0) :?>
			<a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$next['data']['id'])); ?>" class="btn btn-sm"><span>다음글</span></a>
			<?php endif;?>
		</span>
		<?php if((int)$data['new_state'] < 2) :?>
			<?php if($this->account->is_logged() !== FALSE) :?>
				<a href="<?php echo $this->link->get(array('action'=>'apply', 'id'=>NULL, 'pid'=>$data['id'])); ?>" class="btn btn-lg btn-primary"><span class="icon-pencil">접수 신청하기</span></a>
			<?php else : ?>
				<a href="<?php echo $this->link->get(array('prefix'=>'login','controller'=>'accounts','action'=>'index', 'id'=>NULL, 'pid'=>NULL, 'redirect'=>encode_url()));?>" onclick="return alert('로그인 후 이용해주세요.');" class="btn btn-lg btn-primary"><span class="icon-pencil">접수 신청하기</span></a>
			<?php endif; ?>
		<?php endif;?>
		<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL, 'pid'=>NULL)); ?>" class="btn btn-sm btn-list"><span class="icon-menu">목록</span></a>
	</p>
</div>