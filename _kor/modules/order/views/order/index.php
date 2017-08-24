<!-- 발주관리 목록 -->
<h2 class="hide">발주관리 목록</h2>
<div class="table_wrap">
	<div class="table_header">
		<p class="page_total"><i class="ti-face-smile"></i> 전체 <em><?php echo $total; ?></em></p>
		<div class="search_wrap">
        <?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
				<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'category'=>NULL, 'highlight'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
				<fieldset>
					<legend>게시물검색 폼</legend>
					<div class="searchfrm">
						<div class="select_wrap">
							<div class="select_box">
								<label for="type">발주상태</label>
								<select class="info_select" id="type" name="search_type">
									<option value="">구분 선택</option>
									<option value="대기" <?php echo set_select('search_type', '대기', (get_field('search_type')=='대기')); ?>>대기</option>
									<option value="완료" <?php echo set_select('search_type', '완료', (get_field('search_type')=='완료')); ?>>완료</option>
									<option value="취소" <?php echo set_select('search_type', '취소', (get_field('search_type')=='취소')); ?>>취소</option>
								</select>
							</div>
						</div>
						<div class="select_wrap">
							<div class="select_box">
								<label for="search_field3">구분 선택</label>
								<select class="info_select" id="search_field3" name="search_field">
									<option value="">구분 선택</option>
									<option value="title" <?php echo set_select('search_field', 'title', (get_field('search_field')=='title')); ?>>제목</option>
									<option value="contents" <?php echo set_select('search_field', 'contents', (get_field('search_field')=='contents')); ?>>내용</option>
									<option value="etc" <?php echo set_select('search_field', 'etc', (get_field('search_field')=='etc')); ?>>비고</option>
								</select>
							</div>
						</div>
						<div class="search_ipt">
							<input type="text" class="text_search" name="search_keyword" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>"  />
							<span class="btnsch">
										<button class="btn_search" type="submit"><i class="pe-7s-search" aria-hidden="true"></i> 검색</button>
									</span>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<table class="bbs_table table_hover center">
		<caption>발주관리 목록 - 번호, 제목, 발주상태, 비고로 나뉘어 설명</caption>
		<colgroup>
			<col style="width:70px" /><col /><col style="width:120px" /><col style="width:300px" />
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">제목</th>
			<th scope="col">발주상태</th>
			<th scope="col">비고</th>
		</tr>
		</thead>
		<tbody>
    <?php if( !empty($data)) : ?>
        <?php foreach($data as $item) : ?>
				<tr>
					<td scope="row"><?php echo $item['_num']; ?></td>
					<td class="left"><a href="#" class="boardTit"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['title']; ?></a><?php if($item['total_comment'] > 0) : ?><span class="total_comment">[<?php echo number_format($item['total_comment']); ?>]</span><?php endif; ?><?php if(empty($item['isreaded'])) : ?><img src="/_res/img/new_icon.gif" class="new_icon" /><?php endif; ?></a></td>
					<td>발주<?php echo $item['type']; ?></td>
					<td class="left"><?php echo $item['etc']; ?></td>
				</tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if(empty($data)) : ?>
			<tr>
				<td scope="row" colspan="4">
					<div class="no_lstwrap">
						<p class="no_article">등록된 게시물이 없습니다.</p>
					</div>
				</td>
			</tr>
    <?php endif; ?>
		</tbody>
	</table>


	<div class="table_footer">
      <?php if( !empty($data)) : ?>
          <?php echo $paging_element; ?>
      <?php endif; ?>
	</div>

	<div class="table_footer">
		<p class="btn_wrap btn_regist">
			<a href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>" class="btn btn-primary"><span><i class="pe-7s-check" aria-hidden="true"></i>발주신청</span></a>
		</p>
	</div>