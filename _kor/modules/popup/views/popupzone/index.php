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
	<th>제목</th>
	<th class="hidden-phone">순서</th>
	<th class="hidden-phone">작성일</th>
	<th>관리</th>
</tr>
</thead>
<tbody>
<?php foreach($data as $item) : ?>
<tr>
	<td class="hidden-phone centered"><?php echo $item['_num']; ?></td>
	<td class="ellipsis"><a href="<?php echo $this->link->get(array('action'=>'write', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></td>
	<td class="hidden-phone centered"><?php echo $item['sort']; ?></td>
	<td class="hidden-phone centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
	<td class="centered">
		<a class="btn btn-primary btn-mini" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>$item['id'])); ?>"><i class="icon-edit icon-white"></i> 수정</a>
		<a class="btn btn-danger btn-mini" href="<?php echo $this->link->get(array('action'=>'delete', 'id'=>$item['id'])); ?>" onclick="return confirm('삭제 하시겠습니까?');"><i class="icon-trash icon-white"></i> 삭제</a>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


<div class="well well-small clearfix">
	<div class="pull-right">
		<a class="btn btn-success" href="<?php echo $this->link->get(array('action'=>'write')); ?>">비주얼 추가</a>
	</div>
</div>

<?php echo $paging_element; ?>