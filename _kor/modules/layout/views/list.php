<table class="bbs_table table-hover">
<colgroup>
	<col width="60" class="hidden-phone" />
	<col width="*" />
	<col width="100" class="hidden-phone" />
	<col width="100" class="hidden-phone" />
	<col width="125" />
</colgroup>
<thead>
<tr>
	<th class="hidden-phone">번호</th>
	<th>레이아웃 이름</th>
	<th class="hidden-phone">작성일</th>
	<th class="hidden-phone">수정일</th>
	<th>관리</th>
</tr>
</thead>
<tbody>
<?php foreach($data as $item) : ?>
<tr>
	<td class="hidden-phone centered"><?php echo $item['_num']; ?></td>
	<td class="ellipsis"><a href="<?php echo $this->link->get(array('action'=>'write', 'id'=>$item['id'])); ?>"><?php echo $item['title']; ?></a></td>
	<td class="hidden-phone centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
	<td class="hidden-phone centered"><?php echo date('Y-m-d', strtotime($item['modified'])); ?></td>
	<td class="centered">
		<a class="btn btn-primary btn-xs" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>$item['id'])); ?>"><i class="icon-edit icon-white"></i> 수정</a>
		<a class="btn btn-danger btn-xs" href="<?php echo $this->link->get(array('action'=>'delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');"><i class="icon-trash icon-white"></i> 삭제</a>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


<div class="well well-sm clearfix">
	<div class="pull-right">
		<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'write')); ?>">레이아웃 추가</a>
	</div>
</div>

<?php echo $paging_element; ?>
