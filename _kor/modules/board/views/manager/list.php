<table class="bbs_table table-hover">
<thead>
<tr>
	<th width="60" class="hidden-xs">번호</th>
	<th>게시판 이름</th>
	<?php if($this->router->fetch_class() != 'board_manager') : ?>
	<th>게시판 아이디</th>
	<?php endif; ?>
	<th class="hidden-sm hidden-xs">타입</th>
	<th class="hidden-xs">생성일</th>
	<th class="hidden-sm hidden-xs">수정일</th>
	<?php if($this->router->fetch_class() == 'board_manager') : ?>
		<th class="hidden-xs">PMS 공개</th>
	<?php endif; ?>
	<th width="120">수정</th>
</tr>
</thead>
<tbody>
<?php foreach($data as $item) : ?>
<tr>
	<td class="hidden-xs centered"><?php echo $item['_num']; ?></td>
	<td class="ellipsis centered"><a href="<?php echo $this->link->get(array('map'=>'', 'prefix'=>$item['tableid'], 'controller'=>'bbs', 'action'=>'index', 'tableid'=>$item['tableid'])); ?>" target="_blank"><?php echo $item['title']; ?></a></td>
	<?php if($this->router->fetch_class() != 'board_manager') : ?>
		<td class="centered"><a href="<?php echo $this->link->get(array('map'=>'', 'prefix'=>$item['tableid'], 'controller'=>'bbs', 'action'=>'index', 'tableid'=>$item['tableid'])); ?>" target="_blank"><?php echo $item['tableid']; ?></a></td>
	<?php endif; ?>
	<td class="hidden-sm hidden-xs centered"><?php echo strtoupper($item['type']); ?></td>
	<td class="hidden-xs centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
	<td class="hidden-sm hidden-xs centered"><?php echo date('Y-m-d', strtotime($item['modified'])); ?></td>
	<?php if($this->router->fetch_class() == 'board_manager') : ?>
		<td class="hidden-xs centered"><?php if($item['pms_public'] == 'Y') : ?>예<?php else : ?>아니오<?php endif; ?></td>
	<?php endif; ?>
	<td class="centered">
		<a class="btn btn-primary btn-xs" href="<?php echo $this->link->get(array('action'=>'write', 'tableid'=>$item['tableid'])); ?>">수정</a>
		<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete', 'tableid'=>$item['tableid'])); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


<div class="well well-sm clearfix">
	<div class="pull-right">
		<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'write')); ?>">게시판 추가</a>
	</div>
</div>

<?php echo $paging_element; ?>
