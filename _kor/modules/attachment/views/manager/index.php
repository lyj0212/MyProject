<div class="righted">
	전체 : <?php echo human_filesize($total_size); ?> 사용중
</div>

<table class="bbs_table table-hover">
<thead>
<tr>
	<th width="30"><input type="checkbox" class="_checkbox_all" /></th>
	<th width="60" class="hidden-xs">번호</th>
	<th width="120">회사</th>
	<th >파일명</th>
	<th class="hidden-xs hidden-sm hidden-md">실제위치</th>
	<th width="80">크기</th>
	<th width="80">사용여부</th>
	<th width="100" class="hidden-xs">작성일</th>
	<th width="80">관리</th>
</tr>
</thead>
<tbody>
<?php foreach($data as $item) : ?>
<tr>
	<td class="centered"><input type="checkbox" class="_checkbox" value="<?php echo $item['id']; ?>" /></td>
	<td class="hidden-xs centered"><?php echo $item['_num']; ?></td>
	<td class="centered"><?php echo $item['company_name']; ?></td>
	<td class="ellipsis">
		<?php if(in_array(strtolower($item['file_ext']), array('.gif', '.bmp', '.jpg', '.png'))) : ?>
			<img src="/<?php echo $item['upload_path']; ?><?php echo $item['raw_name']; ?><?php echo $item['file_ext']; ?>" style="width:50px; margin-right:5px" />
		<?php endif; ?>
		<?php echo $item['orig_name']; ?>
	</td>
	<td class="hidden-xs hidden-sm hidden-md ellipsis"><?php echo $item['upload_path']; ?><?php echo $item['raw_name']; ?><?php echo $item['file_ext']; ?></td>
	<td class="centered"><?php echo human_filesize($item['file_size']); ?></td>
	<td class="centered"><?php if($item['use']=='Y') : ?>사용중<?php else : ?><span style="color:red">미사용</span><?php endif; ?></td>
	<td class="hidden-xs centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
	<td class="centered">
		<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');"><i class="glyphicon glyphicon-trash"></i> 삭제</a>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<div class="well well-sm clearfix">
	<div class="pull-right">
		<a href="#" class="btn btn-sm btn-danger delete_checked">선택삭제</a>
		<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'delete_temporary')); ?>" onclick="return confirm('미사용 파일을 모두 삭제 하시겠습니까?');">미사용 파일 일괄 삭제</a>
	</div>
</div>

<?php echo $paging_element; ?>
