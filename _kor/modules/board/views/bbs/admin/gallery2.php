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

		<span class="input-group-btn">
			<select name="highlight" class="form-control input-sm">
				<option value="">-- 상태 --</option>
				<option value="3" <?php echo set_select('highlight', '3', (get_field('highlight')=='3')); ?>>새 게시물</option>
				<option value="1" <?php echo set_select('highlight', '1', (get_field('highlight')=='1')); ?>>일정 게시물</option>
				<option value="2" <?php echo set_select('highlight', '2', (get_field('highlight')=='2')); ?>>중요 게시물</option>
			</select>
		</span>

		<span class="input-group-btn">
			<select name="search_field" class="form-control input-sm">
				<option value="">-- 항목 --</option>
				<option value="subject" <?php echo set_select('search_field', 'subject', (get_field('search_field')=='subject')); ?>>제목</option>
				<option value="contents" <?php echo set_select('search_field', 'contents', (get_field('search_field')=='contents')); ?>>내용</option>
				<option value="name" <?php echo set_select('search_field', 'name', (get_field('search_field')=='name')); ?>>작성자</option>
				<!--option value="comment" <?php echo set_select('search_field', 'comment', (get_field('search_field')=='comment')); ?>>댓글</option-->
			</select>
		</span>

		<input type="text" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
		<span class="input-group-btn">
			<button type="submit" class="btn btn-info">검색</button>
		</span>
	</div>
	</form>
</div>

<div class="table_wrap gallery_wrap">
	<?php if( ! empty($notices['data'])) : ?>
	<table class="bbs_table table-hover">
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
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['_num']; ?></td>
			<?php break; case 'subject' : ?>
				<td class="ellipsis <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></td>
			<?php break; case 'name' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['name']; ?></td>
			<?php break; case 'ext' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo (isset($artices['files'][$item['id']][0]['file_ext'])) ? ext($artices['files'][$item['id']][0]['file_ext']) : ext(); ?></td>
			<?php break; case 'thumb' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><div class="thumbnail"><img src="<?php echo $item['thumb']; ?>" /></div></td>
			<?php break; case 'hit' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['hit']; ?></td>
			<?php break; case 'created' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['created'])); ?></td>
			<?php break; case 'modified' : ?>
				<td class="centered <?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['modified'])); ?></td>
			<?php endswitch; ?>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?php endif; ?>
	
	<div id="gridgallery" style="width:100%;">
		<?php foreach($artices['data'] as $n=>$item) : ?>
		<div class="grid">
			<div class="imgholder">
				<a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>">
					<img src="<?php echo $item['thumb']; ?>" alt="<?php echo $item['subject']; ?>" />
				</a>
			</div>

			<?php foreach($this->setup['list'] as $value) : ?>
				<?php switch($value) : case 'subject' : ?>
				<strong class="ellipsis <?php echo $this->list_object[$value]['class']; ?>"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></strong>
				<!-- 내용 -->
				<?php if(trim(strip_tags($item['contents']))):?>
					<div class="photo_cnts"><?php echo strip_tags($item['contents']);?></div>
				<?php endif;?>
				<!-- 내용 -->
				<div class="photobtm_wrap">
				<?php break; case 'name' : ?>
					<div class="meta <?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['name']; ?></div>
				<?php break; case 'hit' : ?>
					<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['hit']; ?></div>
				<?php break; case 'created' : ?>
					<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['created'])); ?></div>
				<?php break; case 'modified' : ?>
					<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['modified'])); ?></div>
				<?php endswitch; ?>
			<?php endforeach; ?>
				</div>
		</div>
		<?php endforeach; ?>
	</div>
	
	<?php if(empty($notices['data']) AND empty($artices['data'])) : ?>
	<div class="no_lstwrap">
		<p class="no_article">등록된 내용이 없습니다.</p>
	</div>
	<?php endif; ?>

	<div class="table_footer">
		<?php echo $artices['paging_element']; ?>
		<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
		<p class="btn_wrap text-right"><a class="btn btn-primary" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>"><span class="icon-pencil">글쓰기</span></a></p>
		<?php endif; ?>
	</div>
</div>