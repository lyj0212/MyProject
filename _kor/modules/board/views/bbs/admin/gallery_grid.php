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

<div id="gridgallery" class="clearfix">
  <ul id="tiles">
	<?php foreach($artices['data'] as $n=>$item) : ?>
    <li>
      <a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>">
        <img src="<?php echo $item['thumb']; ?>" />
      </a>

      <?php if(in_array('subject', $this->setup['list']) OR in_array('created', $this->setup['list'])) : ?>
      <div class="gallery_pannel">
        <?php if(in_array('created', $this->setup['list'])) : ?>
          <div class="pull-right <?php echo $this->list_object['created']['class']; ?>"><?php echo date('Y.m.d', strtotime($item['created'])); ?></div>
        <?php endif; ?>
        <?php if(in_array('subject', $this->setup['list'])) : ?>
          <div class="ellipsis <?php echo $this->list_object['subject']['class']; ?>"><a href="<?php echo $this->link->get(array('action'=>'view', 'id'=>$item['id'])); ?>"><?php echo $item['subject']; ?></a></div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </li>
	<?php endforeach; ?>
  </ul>
</div>

<div class="well well-small clearfix">
	<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-search')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'index', 'page'=>NULL, 'search_field'=>NULL, 'search_keyword'=>NULL)); ?>" />
	<input type="hidden" name="search_field" value="subject+contents" />
	<div class="pull-left hidden-phone">
		<div class="input-append">
			<input type="text" name="search_keyword" class="input-medium search-query" value="<?php echo set_value('search_keyword', get_field('search_keyword')); ?>" />
			<button type="submit" class="btn">검색</button>
		</div>
	</div>
	</form>

	<div class="btn-group pull-right">
    <?php if($this->auth->check(array('action'=>'write', 'segment'=>array('id'=>FALSE))) == TRUE) : ?>
			<a class="btn btn-success" href="<?php echo $this->link->get(array('action'=>'write', 'id'=>NULL)); ?>">글쓰기</a>
		<?php endif; ?>
	</div>
</div>

<?php echo $artices['paging_element']; ?>
