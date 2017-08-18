
<ul class="biz_papers_lst">
	<?php if( ! empty($files)) : ?>
		<?php foreach($files as $item) : ?>
			<li>
				<?php echo (!empty($item['file_text']) ? '<strong>'.$item['file_text'].'</strong>' : ''); ?>
				<div class="fileattach_lst">
					<span class="fileName"><a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE); ?>"><?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?></a></span>
					<span class="fileSize"><?php echo human_filesize($item['file_size']); ?></span>
					<span class="fileButton"><a href="#" class="btn_attach_del" rel="<?php echo $item['id']; ?>">삭제</a></span>
				</div>
			</li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>