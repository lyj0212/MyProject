<div class="alert alert-block alert-error centered">
	<?php echo $message; ?>
</div>
<div class="centered">
	<a href="/<?php echo $this->menu->current['map']; ?>" class="btn btn-default" onclick="history.back(); return false;">돌아가기</a>
	<?php if($this->account->is_logged() == FALSE) : ?>
		<a href="<?php echo $this->menu->login_page; ?>" class="btn">로그인</a>
	<?php endif; ?>
</div>
