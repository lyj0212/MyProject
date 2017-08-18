<div class="search_wrap top_search">
    <?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
    <input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'category'=>NULL, 'highlight'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />

    <div class="search_frm">
        <div class="select_box">
            <label for="type">전체</label>
            <select id="type" name="type" class="info_select">
                <option value="">-- 발주상태 --</option>
                <option value="대기" <?php echo set_select('type', '대기', (get_field('type')=='대기')); ?>>발주대기</option>
                <option value="완료" <?php echo set_select('type', '완료', (get_field('type')=='완료')); ?>>발주완료</option>
                <option value="취소" <?php echo set_select('type', '취소', (get_field('type')=='취소')); ?>>발주취소</option>
            </select>
        </div>

        <div class="select_box">
            <label for="search_field">전체</label>
            <select id="search_field" name="search_field" class="info_select">
                <option value="">-- 항목 --</option>
                <option value="title" <?php echo set_select('search_field', 'title', (get_field('search_field')=='title')); ?>>제목</option>
                <option value="contents" <?php echo set_select('search_field', 'contents', (get_field('search_field')=='contents')); ?>>내용</option>
                <option value="name" <?php echo set_select('search_field', 'name', (get_field('search_field')=='name')); ?>>작성자</option>
                <!--option value="comment" <?php echo set_select('search_field', 'comment', (get_field('search_field')=='comment')); ?>>댓글</option-->
            </select>
        </div>

        <div class="input-group">
            <label for="search_keyword" class="hide">검색어 입력</label>
            <input type="text" id="search_keyword" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" title="검색어 입력" />
            <span class="input-group-btn">
				<button type="submit" class="btn btn-primary">검색 <i class="icon-search"></i></button>
			</span>
        </div>
    </div>
    </form>
</div>

<div class="table_wrap">
    <table class="bbs_table table-hover" data="draggable-table">
        <caption>목록</caption>
        <colgroup>
            <col width="70" class="hidden-xs" ><col  class="subject" ><col class="hidden-xs created" width="120" ><col  class="subject" width="200"/>
        </colgroup>
        <thead>
        <tr>
            <th class="hidden-xs" scope="col">번호</th>
            <th class="subject" scope="col">제목</th>
            <th class="hidden-xs created" scope="col">발주상태</th>
            <th class="hidden-xs" scope="col">비고</th>
        </tr>
        </thead>
        <tbody>
        <?php if( !empty($data)) : ?>
            <?php foreach($data as $item) : ?>
                <tr class="notice">
                    <td class="centered"><?php echo $item['_num']; ?></td>
                    <td class="ellipsis draggable"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['title']; ?></a><?php if($item['total_comment'] > 0) : ?><span class="total_comment">[<?php echo number_format($item['total_comment']); ?>]</span><?php endif; ?><?php if(empty($item['isreaded'])) : ?><img src="/_res/img/new_icon.gif" class="new_icon" /><?php endif; ?></td>
                    <td>발주<?php echo $item['type']; ?></td>
                    <td><?php echo $item['etc']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        </tbody>
    </table>

    <?php if(empty($data)) : ?>
        <div class="no_lstwrap">
            <p class="no_article">등록된 내용이 없습니다.</p>
        </div>
    <?php endif; ?>

    <div class="table_footer">
        <?php if( !empty($data)) : ?>
            <?php echo $paging_element; ?>
        <?php endif; ?>
        <?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
            <p class="btn_wrap text-right"><a class="btn btn-primary" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>"><span class="icon-pencil">글쓰기</span></a></p>
        <?php endif; ?>
    </div>
</div>