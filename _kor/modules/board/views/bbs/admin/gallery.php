<?php if( ! empty($notices['data'])) : ?>
<table class="bbs_table table-hover">
<colgroup>
	<?php foreach($this->setup['list'] as $value) : ?>
	<col width="<?php echo $this->list_object[$value]['size']; ?>" class="<?php echo $this->list_object[$value]['class']; ?>" />
	<?php endforeach; ?>
</colgroup>
<thead>
<tr>
	<?php foreach($this->setup['list'] as $value) : ?>
	<th class="<?php echo $this->list_object[$value]['class']; ?>" ><?php echo $this->list_object[$value]['name']; ?></th>
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
			<td class="ellipsis <?php echo $this->list_object[$value]['class']; ?>"><?php if( ! empty($item['ishighlight']) AND $item['ishighlight']=='Y') : ?><img src="/images/sub/highlight.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?><?php if( ! empty($item['isschedule'])) : ?><img src="/images/sub/schedule.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?> <a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></td>
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

<ul class="thumbnails">
	<?php foreach($artices['data'] as $n=>$item) : ?>
	<li class="span2">
		<a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>" class="thumbnail">
			<img src="<?php echo $item['thumb']; ?>" />
		</a>
		<?php foreach($this->setup['list'] as $value) : ?>
			<?php switch($value) : case 'subject' : ?>
				<div class="ellipsis <?php echo $this->list_object[$value]['class']; ?>"><?php if( ! empty($item['ishighlight']) AND $item['ishighlight']=='Y') : ?><img src="/images/sub/highlight.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?><?php if( ! empty($item['isschedule'])) : ?><img src="/images/sub/schedule.png" alt="Highlight" style="margin:0 3px 3px 0"><?php endif; ?> <a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></div>
			<?php break; case 'name' : ?>
				<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['name']; ?></div>
			<?php break; case 'hit' : ?>
				<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo $item['hit']; ?></div>
			<?php break; case 'created' : ?>
				<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['created'])); ?></div>
			<?php break; case 'modified' : ?>
				<div class="<?php echo $this->list_object[$value]['class']; ?>"><?php echo date('Y.m.d', strtotime($item['modified'])); ?></div>
			<?php endswitch; ?>
		<?php endforeach; ?>
	</li>
	<?php if(($n+1)%6==0 AND count($item) != $n+1) : ?></ul><ul class="thumbnails"><?php endif; ?>
	<?php endforeach; ?>
</ul>

<div class="well well-sm clearfix">
	<div class="pull-left col-xs-6 col-sm-4 col-md-5 col-lg-4">
		<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-inline')); ?>
		<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'category'=>NULL, 'highlight'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
		<input type="hidden" name="search_field" value="subject+contents+name" />
		<input type="hidden" name="category" value="<?php echo set_value('category', get_field('category')); ?>" />
		<input type="hidden" name="highlight" value="<?php echo set_value('highlight', get_field('highlight')); ?>" />
		<div class="input-group input-group-sm">
			<input type="text" name="search_keyword" class="form-control" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
			<span class="input-group-btn">
				<button type="submit" class="btn btn-default">검색</button>
			</span>
		</div>
		</form>
	</div>
	<?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
		<div class="pull-right">
			<a class="btn btn-sm btn-success" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>">글쓰기</a>
		</div>
	<?php endif; ?>
</div>

<?php echo $artices['paging_element']; ?>