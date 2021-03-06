<div class="well well-sm top_search">
	<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'category'=>NULL, 'highlight'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />

	<div class="input-group input-group-sm">
		<?php if($this->setup['use_category'] == 'Y') : ?>
			<span class="input-group-btn">
				<select name="category" class="form-control input-sm">
					<option value="">-- 분류 --</option>
					<?php foreach($category['data'] as $item) : ?>
						<option value="<?php echo $item['id']; ?>" <?php echo set_select('category', $item['id'], (get_field('category')==$item['id'])); ?>><?php echo $item['title']; ?></option>
					<?php endforeach; ?>
				</select>
			</span>
		<?php endif; ?>

		<?php if($this->link->base['map'] != 'pms') : ?>
		<span class="input-group-btn">
			<select name="highlight" class="form-control input-sm">
				<option value="">-- 상태 --</option>
				<option value="3" <?php echo set_select('highlight', '3', (get_field('highlight')=='3')); ?>>새 게시물</option>
				<option value="1" <?php echo set_select('highlight', '1', (get_field('highlight')=='1')); ?>>일정 게시물</option>
				<option value="2" <?php echo set_select('highlight', '2', (get_field('highlight')=='2')); ?>>중요 게시물</option>
			</select>
		</span>
		<?php endif; ?>

		<span class="input-group-btn">
			<select name="search_field" class="form-control input-sm">
				<option value="">-- 항목 --</option>
				<option value="subject" <?php echo set_select('search_field', 'subject', (get_field('search_field')=='subject')); ?>>제목</option>
				<option value="contents" <?php echo set_select('search_field', 'contents', (get_field('search_field')=='contents')); ?>>내용</option>
				<option value="name" <?php echo set_select('search_field', 'name', (get_field('search_field')=='name')); ?>>작성자</option>
				<option value="comment" <?php echo set_select('search_field', 'comment', (get_field('search_field')=='comment')); ?>>댓글</option>
			</select>
		</span>

		<input type="text" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
		<span class="input-group-btn">
			<button type="submit" class="btn btn-info">검색</button>
		</span>
	</div>
	</form>
</div>

<table class="bbs_table table-hover" data="draggable-table">
<thead>
<tr>
	<?php foreach($this->setup['list'] as $value) : ?>
	<th width="<?php echo $this->list_object[$value]['size']; ?>" class="<?php echo $this->list_object[$value]['class']; ?>" ><?php echo $this->list_object[$value]['name']; ?></th>
	<?php endforeach; ?>
</tr>
</thead>
<tbody>
<?php foreach($notices['data'] as $item) : ?>
<tr class="notice">
	<?php foreach($this->setup['list'] as $value) : ?>
		<?php switch($value) : case 'no' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>">
				<?php if( ! empty($data['id']) AND $data['id'] == $item['id']) : ?>
					<i class="glyphicon glyphicon-circle-arrow-right"></i>
				<?php else : ?>
					공지
				<?php endif; ?>
			</td>
		<?php break; case 'category' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['category_title']; ?></td>
		<?php break; case 'subject' : ?>
			<td class="ellipsis draggable <?php echo $this->list_object[$value]['class']; ?>"><?php if( ! empty($item['ishighlight']) AND $item['ishighlight']=='Y') : ?><img src="/images/sub/highlight.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?><?php if( ! empty($item['isschedule'])) : ?><img src="/images/sub/schedule.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?> <a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a><?php if($item['total_comment'] > 0) : ?><span class="total_comment">[<?php echo number_format($item['total_comment']); ?>]</span><?php endif; ?><?php if(empty($item['isreaded'])) : ?><img src="/_res/img/new_icon.gif" class="new_icon" /><?php endif; ?></td>
		<?php break; case 'name' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo CI::$APP->_user_link($item['ismember']); ?>" class="_popup" data-maxwidth="480"><?php echo $item['name']; ?></a></td>
		<?php break; case 'ext' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo (isset($artices['files'][$item['id']][0]['ext_icon'])) ? $artices['files'][$item['id']][0]['ext_icon'] : ext(); ?></td>
		<?php break; case 'thumb' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>" class="thumbnail"><img src="<?php echo $item['thumb']; ?>" /></a></td>
		<?php break; case 'hit' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['hit']; ?></td>
			<?php break; case 'status' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>">
				<?php switch($item['status']) : case '1' : ?>접수<?php break; case '2' : ?>완료<?php break; case '3' : ?>반려<?php break; default : ?>대기<?php endswitch; ?>
			</td>
		<?php break; case 'created' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
		<?php break; case 'modified' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y-m-d', strtotime($item['modified'])); ?></td>
		<?php endswitch; ?>
	<?php endforeach; ?>
</tr>
<?php endforeach; ?>

<?php foreach($artices['data'] as $item) : ?>
<tr>
	<?php foreach($this->setup['list'] as $value) : ?>
		<?php switch($value) : case 'no' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>">
			<?php if( ! empty($data['id']) AND $data['id'] == $item['id']) : ?>
				<i class="glyphicon glyphicon-circle-arrow-right"></i>
			<?php else : ?>
				<?php echo $item['_num']; ?>
			<?php endif; ?>
			</td>
		<?php break; case 'category' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['category_title']; ?></td>
		<?php break; case 'subject' : ?>
			<td class="ellipsis draggable <?php echo $this->list_object[$value]['class']; ?>"><?php if( ! empty($item['ishighlight']) AND $item['ishighlight']=='Y') : ?><img src="/images/sub/highlight.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?><?php if( ! empty($item['isschedule'])) : ?><img src="/images/sub/schedule.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?> <a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a><?php if($item['total_comment'] > 0) : ?><span class="total_comment">[<?php echo number_format($item['total_comment']); ?>]</span><?php endif; ?><?php if(empty($item['isreaded'])) : ?><img src="/_res/img/new_icon.gif" class="new_icon" /><?php endif; ?></td>
		<?php break; case 'name' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo CI::$APP->_user_link($item['ismember']); ?>" class="_popup" data-maxwidth="480"><?php echo $item['name']; ?></a></td>
		<?php break; case 'ext' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo (isset($artices['files'][$item['id']][0]['ext_icon'])) ? $artices['files'][$item['id']][0]['ext_icon'] : ext(); ?></td>
		<?php break; case 'thumb' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>" class="thumbnail"><img src="<?php echo $item['thumb']; ?>" /></a></td>
		<?php break; case 'hit' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['hit']; ?></td>
			<?php break; case 'status' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>">
				<?php switch($item['status']) : case '1' : ?><span class="label label-info">접수</span><?php break; case '2' : ?><span class="label label-success">완료</span><?php break; case '3' : ?><span class="label label-warning">반려</span><?php break; default : ?><span class="label label-default">대기</span><?php endswitch; ?>
			</td>
		<?php break; case 'created' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
		<?php break; case 'modified' : ?>
			<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y-m-d', strtotime($item['modified'])); ?></td>
		<?php endswitch; ?>
	<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if(empty($notices['data']) AND empty($artices['data'])) : ?>
<div class="well well-sm clearfix centered">등록된 내용이 없습니다.</div>
<?php endif; ?>

<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
<div class="well well-sm clearfix">
	<div class="pull-right">
		<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>">글쓰기</a>
	</div>
</div>
<?php endif; ?>

<?php echo $artices['paging_element']; ?>