<ul id="commentArea-<?php echo $pid; ?>" class="comment_entries">
<?php foreach($entries['data'] as $item) : ?>
	<li id="comment-<?php echo $item['id']; ?>" class="clearfix"<?php if($item['depth'] > 0) : ?> style="margin-left:<?php echo $item['depth']*20; ?>px"<?php endif; ?>>
		<?php if($item['depth'] > 0) : ?>
			<div class="reply_ico">
				<img src="/_res/img/ico_reply.gif" width="9" height="10" alt="댓글" />
			</div>
		<?php endif; ?>
		<div class="comment_body">		
			<div class="profile_meta">
				<span class="writename"><?php echo $item['name']; ?></span>
				<span class="writedate"><?php echo $item['created']; ?><?php if(empty($item['isreaded']) AND $item['ismember'] != $this->account->get('id')) : ?><img src="/_res/img/new_icon.gif" class="new_icon" /><?php endif; ?></span>
			</div>
			<div class="comment_content">
				<?php echo nl2br($item['contents']); ?>
			</div>
			<?php if( ! empty($entries['files'][$item['id']])) : ?>
				<ul class="attachment attachment_comment clearfix">
					<?php if(count($entries['files'][$item['id']]) > 1) : ?>
						<li class="nobg">
							<a href="<?php echo $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'zipDown', 'pid'=>$item['id']), TRUE); ?>" class="_tooltip" title="모든 파일을 한번에 내려받습니다."><span lang="en" class="label label-purple mb20">ZIP DOWN</span></a>
						</li>
					<?php endif; ?>
					<?php foreach($entries['files'][$item['id']] as $file) : ?>
						<li>
							<a href="<?php echo $file['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($file['download_count']); ?> 회"><?php echo $file['ext_icon']; ?> <?php echo $file['orig_name']; ?><span class="file_size">(<?php echo human_filesize($file['file_size']); ?>)</span></a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div class="comment_btn">
			<?php if($this->auth->check(array('action'=>'comment')) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'comment_write', 'pid'=>$pid, 'tid'=>$item['id'])); ?>" class="_popup" data-reload="true" data-maxwidth="600">답글</a>
			<?php endif; ?>
			<?php if($this->auth->check(array('action'=>'comment_write', 'segment'=>array('cid'=>$item['id']))) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'comment_write', 'cid'=>$item['id'])); ?>" class="_popup" data-reload="true" data-maxwidth="600">수정</a>
			<?php endif; ?>
			<?php if($this->auth->check(array('action'=>'comment_delete', 'segment'=>array('cid'=>$item['id']))) == TRUE) : ?>
				<a href="<?php echo $this->link->get(array('action'=>'comment_delete', 'cid'=>$item['id'], 'redirect'=>encode_url())); ?>" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
			<?php endif; ?>
		</div>
	</li>
<?php endforeach; ?>
</ul>
