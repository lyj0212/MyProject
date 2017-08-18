<div class="attacharea"<?php if( ! empty($files)) : ?> style="display:block"<?php endif; ?>>
	<h2 class="h2">첨부된 파일</h2>

	<div class="attachcontainer areaimage clearfix"<?php if( ! empty($image)) : ?> style="display:block"<?php endif; ?>>
		<h5><span class="icon image"></span>그림</h5>
		<ul id="fsUploadProgress_image">
			<?php foreach($image as $item) : ?>
			<li class="uploadify-queue-item">
			<div class="progressContainer" title="<?php echo $item['orig_name']; ?>">
				<div class="thumb">
					<img src="<?php echo site_url($item['upload_path'].$item['raw_name'].$item['file_ext']); ?>">
				</div>
				<?php if($edit == TRUE) : ?>
				<div class="caption">
					<a href="#none" class="btn_attach_ins" data-url="<?php echo site_url($item['upload_path'].$item['raw_name'].$item['file_ext']); ?>" data-type="image" data-orig_name="<?php echo $item['orig_name']; ?>" data-download_link="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE); ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_upload.gif" /></a>
					<a href="#none" class="btn_attach_del" rel="<?php echo $item['id']; ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_delete.gif" /></a>
				</div>
				<?php endif; ?>
			</div>
			<div class="progressName">
				<?php echo $item['orig_name']; ?>
			</div>
			<div class="progressSize"><?php echo human_filesize($item['file_size']); ?></div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="attachcontainer areamedia clearfix"<?php if( ! empty($media)) : ?> style="display:block"<?php endif; ?>>
		<h5><span class="icon media"></span>미디어</h5>
		<ul id="fsUploadProgress_media">
			<?php foreach($media as $item) : ?>
			<li class="uploadify-queue-item">
			<div class="progressContainer" title="<?php echo $item['orig_name']; ?>">
				<div class="progressName">
					<?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?>
				</div>
				<div class="progressSize"><?php echo human_filesize($item['file_size']); ?></div>
				<?php if($edit == TRUE) : ?>
				<div class="caption">
					<a href="#none" class="btn_attach_del" rel="<?php echo $item['id']; ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_delete.gif" /></a>
				</div>
				<?php endif; ?>
			</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="attachcontainer areafile clearfix"<?php if( ! empty($file)) : ?> style="display:block"<?php endif; ?>>
		<h5><span class="icon file"></span>파일</h5>
		<ul id="fsUploadProgress_file">
			<?php foreach($file as $item) : ?>
			<li class="uploadify-queue-item">
			<div class="progressContainer" title="<?php echo $item['orig_name']; ?>">
				<div class="progressName">
					<?php echo ext($item['file_ext']); ?> <?php echo $item['orig_name']; ?>
				</div>
				<div class="progressSize"><?php echo human_filesize($item['file_size']); ?></div>
				<?php if($edit == TRUE) : ?>
				<div class="caption">
					<a href="#none" class="btn_attach_ins" data-url="" data-type="file" data-orig_name="<?php echo $item['orig_name']; ?>" data-download_link="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE); ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_upload.gif" /></a>
					<a href="#none" class="btn_attach_del" rel="<?php echo $item['id']; ?>"><img src="<?php echo $editor_path; ?>images/icon/editor/btn_a_delete.gif" /></a>
				</div>
				<?php endif; ?>
			</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>

</div>

<div class="uploader<?php echo $sequence; ?>"></div>