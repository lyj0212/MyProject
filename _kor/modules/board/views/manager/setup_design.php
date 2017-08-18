<?php echo validation_errors(); ?>

<?php echo form_open($this->link->get(array('action'=>'save_design')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="tableid" value="<?php echo $data['tableid']; ?>" />

<div class="bbs_example">
	<table class="bbs_table table-hover">
	<colgroup>
		<col width="60" class="hidden-xs" />
		<col width="*" />
		<col width="100" />
	</colgroup>
	<thead>
	<tr>
		<th class="hidden-xs" >번호</th>
		<th>제목</th>
		<th>작성일</th>
	</tr>
	</thead>
	<tbody>
	<tr class="notice">
		<td class="hidden-xs centered">공지</td>
		<td class="ellipsis">제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다.제목 테스트 입니다.<span class="badge">10</span></td>
		<td class="centered">2012-06-13</td>
	</tr>
	<tr>
		<td class="hidden-xs centered">2</td>
		<td class="ellipsis">제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다.제목 테스트 입니다.</td>
		<td class="centered">2012-06-13</td>
	</tr>
	<tr>
		<td class="hidden-xs centered">3</td>
		<td class="ellipsis">제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다.제목 테스트 입니다.</td>
		<td class="centered">2012-06-13</td>
	</tr>
	<tr>
		<td class="hidden-xs centered">4</td>
		<td class="ellipsis">제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다. 제목 테스트 입니다.제목 테스트 입니다.</td>
		<td class="centered">2012-06-13</td>
	</tr>
	</table>
	<div class="well clearfix">
		<div class="search_area hidden-xs">
			<input type="text" class="input-medium search-query" />
			<button type="submit" class="btn">검색</button>
		</div>
		<div class="btn_area">
			<a class="btn btn-success" href="">글쓰기</a>
		</div>
	</div>
</div>

<fieldset>
<div class="form-group">
	<label >대표 색</label>
	<div class="input-prepend input-append">
		<span class="add-on">#</span><input type="text" id="main_color" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
	</div>
	<p class="help-block"><span lang="en" class="label label-info">Information</span> 입력한 색을 기준으로 세부 항목의 색이 자동으로 선택 됩니다.</p>
</div>

<div class="form-group">
	<span class="span3">
		<label >타이틀 상단 라인</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[title_top_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>

	<span class="span3">
		<label >타이틀 하단 라인</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[title_bottom_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>

	<span class="span3">
		<label >타이틀 글자 색</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[title_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>

	<span class="span3">
		<label >타이틀 배경 색</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[title_bg_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>
</div>

<div class="form-group">
	<span class="span3">
		<label >공지사항 글자 색</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[notice_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>

	<span class="span3">
		<label >공지사항 배경 색</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[notice_bg_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>

	<span class="span3">
		<label >게시물간 라인 색</label>
		<div class="input-prepend input-append">
			<span class="add-on">#</span><input type="text" name="color[line_color]" class="color-picker span8" value="3366CC" style="ime-mode:disabled;" /><span class="add-on"></span>
		</div>
	</span>
</div>
</fieldset>

</form>
