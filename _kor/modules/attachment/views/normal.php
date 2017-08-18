<ul id="fsUploadProgress<?php echo $sequence; ?>" class="attacharea_normal clearfix"<?php if( ! empty($files)) : ?> style="display:block"<?php endif; ?>>
	<?php foreach($files as $item) : ?>
		<?php if(in_array(str_replace('.', '', strtolower($item['file_ext'])), $image_ext)) : ?>
			<li class="uploadify-queue-item image">
			<div class="progressContainer" title="<?php echo $item['orig_name']; ?>">
				<div class="thumb">
					<img src="<?php echo site_url($item['upload_path'].$item['raw_name'].$item['file_ext']); ?>">
				</div>
				<div class="caption">
					<a href="#none" class="btn_attach_del" rel="<?php echo $item['id']; ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_delete.gif" /></a>
				</div>
			</div>
			<div class="progressName">
				<?php echo $item['orig_name']; ?>
			</div>
			<div class="progressSize"><?php echo human_filesize($item['file_size']); ?></div>
			</li>
		<?php else : ?>
			<li class="uploadify-queue-item file">
			<div class="progressContainer" title="<?php echo $item['orig_name']; ?>">
				<div class="progressName">
					<?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?>
				</div>
				<div class="progressSize"><?php echo human_filesize($item['file_size']); ?></div>
				<div class="caption">
					<a href="#none" class="btn_attach_del" rel="<?php echo $item['id']; ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_delete.gif" /></a>
				</div>
			</div>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>

<div class="uploader<?php echo $sequence; ?>"></div>
