<p class="text-info bg-info" style="padding:20px"><span lang="en" class="label label-danger">Important</span> 진행 중인 상태의 사업 공고만 접수하기 버튼이 표시됩니다.</p>
<div class="well well-sm top_search">
	<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'search_category'=>NULL, 'search_subject'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
	<div class="input-group input-group-sm">
		<span class="input-group-btn">
			<select name="search_category" class="form-control input-sm">
				<option value="all">-- 전체분류 --</option>
			<?php foreach($category['data'] as $item) : ?>
				<option value="<?php echo $item['id']; ?>" <?php echo set_select('search_category', $item['id'], (get_field('search_category') == $item['id'])); ?>><?php echo $item['title']; ?></option>
			<?php endforeach; ?>
			</select>
		</span>
		<span class="input-group-btn">
			<select name="search_field" class="form-control input-sm">
				<option value="all">-- 전체 --</option>
				<option value="subject" <?php echo set_select('search_field', 'subject', (get_field('search_field')=='subject')); ?>>공고명</option>
				<option value="name" <?php echo set_select('search_field', 'name', (get_field('search_field')=='name')); ?>>이름</option>
				<option value="compname" <?php echo set_select('search_field', 'compname', (get_field('search_field')=='compname')); ?>>회사명</option>
			</select>
		</span>
		<input type="text" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
		<span class="input-group-btn">
			<button type="submit" class="btn btn-info">검색</button>
			<a class="btn btn-small btn-success excelBtn" data-toggle="dropdown" href="#" >
				엑셀다운로드
			</a>
		</span>
	</div>
	</form>
</div>
<table class="bbs_table table-hover" style="table-layout: fixed;">
	<thead>
		<tr>
			<th width="70" class="hidden-xs ">번호</th>
			<th width="140" class="">회사명/대표자</th>
			<th width="110" class="">사업자등록번호</th>
			<th width="145" >사업분류</th>
			<th class="">공고명</th>
			<th width="90" class="hidden-xs hidden-sm">접수일</th>
			<th width="70" class="hidden-xs hidden-sm">접수상태</th>
			<th width="110" class="hidden-xs hidden-sm">결과</th>
			<th width="100" class="hidden-xs hidden-sm">수정권한</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($data['data'] as $item) : ?>
		<tr>
			<td class="centered hidden-xs"><?php echo $item['_num']; ?></td>
			<td class="centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['compname']; ?>/<?php echo $item['ceo_name']; ?></a></td>
			<td class="centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['comp_num']; ?></a></td>
			<td class="centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['category_title'];?></a></td>
			<td class="centered ellipsis"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>" title="<?php echo $item['subject']; ?>" class="_tooltip"><?php echo $item['subject']; ?></a></td>
			<td class="centered hidden-xs hidden-sm"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
			<td class="centered hidden-xs hidden-sm">
				<?php switch($item['state']) : case '2' : ?>
					<a href="<?php echo $this->link->get(array('action'=>'change_state', 'id'=>$item['id'], 'state'=>3, 'field'=>'state')); ?>" class="label label-success label-">접수</a>
				<?php break; case '3' : ?>
					<a href="<?php echo $this->link->get(array('action'=>'change_state', 'id'=>$item['id'], 'state'=>2, 'field'=>'state')); ?>" class="label label-primary">승인</a>
				<?php endswitch; ?>
			</td>
			<td class="centered hidden-xs hidden-sm">
				<i class="bbs_status_process fa fa-spinner fa-spin pull-left" style="display:none; margin-top:8px"></i>
				<i class="bbs_status_save fa fa-check pull-left" style="display:none; margin-top:8px; color:rgb(113, 174, 9);"></i>
				<select class="form-control input-sm select_result" data-id="<?php echo $item['id']; ?>" style="width:100px;">
					<option value="0" <?php echo set_select('select_result', '1', ($item['result']=='1')); ?>>대기</option>
					<option value="1" <?php echo set_select('select_result', '2', ($item['result']=='2')); ?>>미선정</option>
					<option value="2" <?php echo set_select('select_result', '3', ($item['result']=='3')); ?>>선정</option>
				</select>
			</td>
			<td class="centered hidden-xs hidden-sm">
				<?php switch($item['ismodify']) : case 'N' : ?>
					<a href="<?php echo $this->link->get(array('action'=>'change_state', 'id'=>$item['id'], 'state'=>2, 'field'=>'ismodify')); ?>" onclick="return confirm('수정 가능으로 변경시 회원이 접수 내용을 수정할 수 있습니다. \n수정 권한을 변경하시겠습니까? \n수정횟수 : <?php echo $item['modify_count'];?>');" class="label label-grey label-">수정 불가능</a>
				<?php break; case 'Y' : ?>
					<a href="<?php echo $this->link->get(array('action'=>'change_state', 'id'=>$item['id'], 'state'=>1, 'field'=>'ismodify')); ?>" class="label label-purple">수정 가능</a>
				<?php endswitch; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php if(empty($data['data'])) : ?>
<div class="well well-sm clearfix centered">등록된 내용이 없습니다.</div>
<?php endif; ?>

<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
<div class="well well-sm clearfix">
	<div class="btn-group pull-right">
		<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>">등록</a>
	</div>
</div>
<?php endif; ?>

<?php echo $data['paging_element']; ?>
