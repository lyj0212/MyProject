<table class="bbs_table">
	<thead>
	<tr>
		<th><?php echo $data['subject']; ?></th>
	</tr>
	<tr>
		<td>
			<div class="bbs_writer"><?php echo $data['name']; ?></div>
			<div class="bbs_info">
				<strong>작성일</strong><span><?php echo date('Y.m.d H:i', strtotime($data['created'])); ?></span>
				<strong>조회수</strong><span><?php echo $data['hit']; ?></span>
			</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="contents">
			<?php echo $data['contents']; ?>
		</td>
	</tr>
	<?php if( ! empty($files[$data['id']])) : ?>
		<tr>
			<td>
				<ul class="attachment">
					<?php foreach($files[$data['id']] as $item) : ?>
						<li>
							<a href="<?php echo $item['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($item['download_count']); ?> 회"><?php echo $item['ext_icon']; ?> <?php echo $item['orig_name']; ?><span class="file_size">(<?php echo human_filesize($item['file_size']); ?>)</span></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>

<?php
$params = array(
	'pid' => $data['id']
);
//echo CI::$APP->_comment($params);
?>

<div class="well well-sm clearfix">
	<div class="btn-group pull-right">
		<?php if($this->auth->check(array('action'=>'change_status')) == TRUE AND in_array('status', $this->setup['list']) == TRUE) : ?>
			<i class="bbs_status_process fa fa-spinner fa-spin pull-left" style="display:none; margin-top:8px"></i>
			<i class="bbs_status_save fa fa-check pull-left" style="display:none; margin-top:8px; color:rgb(113, 174, 9);"></i>
			<select class="form-control input-sm select_status">
				<option value="">대기</option>
				<option value="1" <?php echo set_select('status', '1', ($data['status'] == '1')); ?>>접수</option>
				<option value="2" <?php echo set_select('status', '2', ($data['status'] == '2')); ?>>완료</option>
				<option value="3" <?php echo set_select('status', '3', ($data['status'] == '3')); ?>>반려</option>
			</select>
		<?php endif; ?>
		<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
			<a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn btn-default btn-sm">수정</a>
		<?php endif; ?>
		<?php if($this->auth->check(array('action'=>'delete')) == TRUE) : ?>
			<a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" onclick="return confirm('삭제 하시겠습니까?');" class="btn btn-default btn-sm">삭제</a>
		<?php endif; ?>
		<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn btn-default btn-sm">목록보기</a>
	</div>
</div>