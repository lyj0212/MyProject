<div class="search_wrap">
	<h2 class="hide">검색 폼</h2>
		<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'search_category'=>NULL, 'search_page'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
	<fieldset>
	<legend>검색 폼</legend>
		<div class="search_frm">
			<div class="input-group">
				<div class="select_box_wrap">
					<div class="select_box category">
						<label for="search_category">사업분류 선택</label>
						<select name="search_category" id="search_category" class="info_select">
							<option value="all">전체공고</option>
							<?php foreach($category['data'] as $item) : ?>
							<option value="<?php echo $item['id']; ?>" <?php echo set_select('search_category', $item['id'], (get_field('search_category') == $item['id'])); ?>><?php echo $item['title']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="select_box">
						<label for="search_field">구분항목</label>
						<select name="search_field" id="search_field" class="info_select">
							<option value="">구분항목</option>
							<option value="all">전체</option>
							<option value="subject" <?php echo set_select('search_field', 'subject', (get_field('search_field')=='subject')); ?>>제목</option>
							<option value="contents" <?php echo set_select('search_field', 'contents', (get_field('search_field')=='contents')); ?>>내용</option>
							<option value="name" <?php echo set_select('search_field', 'name', (get_field('search_field')=='name')); ?>>등록자</option>
						</select>
					</div>
				</div>
				<label for="search_keyword" class="hide">검색어 입력</label>
				<input type="text" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" title="검색어 입력" />
				<span class="input-group-btn">
					<button type="submit" class="btn btn-primary">검색 <i class="icon-search"></i></button>
				</span>
			</div>
			<div class="select_box dataTables_length">
				<label for="search_page">10개씩 보기</label>
				<select name="search_page" id="search_page" class="info_select">
					<option value="10" <?php echo set_select('search_page', 10, (get_field('search_page')==10)); ?>>10개씩 보기</option>
					<option value="25" <?php echo set_select('search_page', 25, (get_field('search_page')==20)); ?>>25개씩 보기</option>
					<option value="50" <?php echo set_select('search_page', 50, (get_field('search_page')==50)); ?>>50개씩 보기</option>
					<option value="100" <?php echo set_select('search_page', 100, (get_field('search_page')==100)); ?>>100개씩 보기</option>
				</select>
			</div>
		</div>
	</fieldset>
	</form>
</div>

<div class="tablewrap">
	<div class="table_header">
		<p class="page_num"><span>전체 <em><?php echo number_format($total_cnt);?></em>건</span> <span>현재페이지(<em><?php echo $page->cur_page;?></em>/<?php echo $page->total_page;?>)</span></p>
	</div>

	<table class="bbs_table text-center">
	<caption>보도자료 리스트</caption>
	<colgroup>
		<col width="65px" /><col width="160px" /><col /><col width="115px" /><col width="115px" /><col width="115px" /><!--col width="75px" /-->
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">사업분류</th>
		<th scope="col">공고명</th>
		<th scope="col">담당자</th>
		<th scope="col">진행상황</th>
		<th scope="col">등록일자</th>
		<!--th scope="col">조회수</th-->
	</tr>
	</thead>
	<tbody>
	<?php foreach($data as $item) : ?>
	<tr>
		<td scope="row"><?php echo $item['_num']; ?></td>
		<td><?php echo $item['category_title']; ?></td>
		<td class="title"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'],'pid'=>NULL)); ?>"><?php echo $item['subject']; ?></a></td>
		<td><?php echo $item['chief_name']; ?></td>
		<td><span class="receive_state <?php echo $item['receive_state'];?>"><?php echo $item['receive_state_text'];?></span></td>
		<td><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
		<!--td><?php echo $item['hit']; ?></td-->
	</tr>
	<?php endforeach;?>
	<?php if(empty($data)) : ?>
		<tr>
			<td colspan="6">등록된 내용이 없습니다.</td>
		</tr>
	<?php endif; ?>
	</tbody>
	</table>

	<?php echo $paging_element; ?>
</div>