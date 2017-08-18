<!--<p class="text-info bg-info" style="padding:20px"><span lang="en" class="label label-danger">Important</span> 진행 중인 상태의 사업 공고만 접수하기 버튼이 표시됩니다.</p>-->
<table class="bbs_table table-hover">
	<colgroup>
		<col width="60" class="hidden-phone"/>
		<col width="" />
		<col width="" class="hidden-phone" />
		<col width="" />
		<col width="" class="hidden-tablet hidden-phone" />
		<col width="" class="hidden-tablet hidden-phone" />
	</colgroup>
	<thead>
	<tr>
		<th class="hidden-phone">번호</th>
		<th class="hidden-phone">회원고유코드</th>
		<th>아이디</th>	
		<th>이름</th>
		<th class="hidden-tablet hidden-phone">마지막 로그인</th>
		<th class="hidden-tablet hidden-phone">가입일</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($data as $item) : ?>
	<tr height="100">
		<td class="hidden-phone centered"><?php echo $item['_num']; ?></td>
		<td class="hidden-phone centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['id']; ?></a></td>
		<td class="centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['userid']; ?></a></td>	
		<td class="centered"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['name']; ?></a></td>
		<td class="hidden-tablet hidden-phone centered"><?php echo ( !empty($item['last_login']) ? date('Y-m-d', strtotime($item['last_login'])) : '-'); ?></td>
		<td class="hidden-tablet hidden-phone centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<div class="well well-sm clearfix">
	<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-search')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
	<input type="hidden" name="search_field" value="name+userid+email+memo" />
	<div class="pull-left col-xs-6 col-sm-4 col-md-5 col-lg-4">
		<div class="input-group input-group-sm">
			<input type="text" name="search_keyword" class="form-control search-query" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
			<span class="input-group-btn">
				<button type="submit" class="btn btn-default ">검색</button>
			</span>
		</div>
	</div>
	</form>

	<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
	<div class="pull-right">
		<a class="btn btn-success" href="<?php echo $this->link->get(array('action'=>'write')); ?>">사용자 추가</a>
	</div>
	<?php endif; ?>
</div>

<?php echo $paging_element; ?>
