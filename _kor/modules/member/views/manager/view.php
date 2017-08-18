<div class="form-horizontal">
<fieldset>
<h5><strong>기본정보</strong></h5>
    <div class="form-group">
    	<label class="col-sm-2 control-label">그룹</label>
    	<div class="col-sm-10 form-control-static">
    		<?php echo $data['title']; ?>
    	</div>
    </div>
    <div class="form-group">
    	<label class="col-sm-2 control-label">이름</label>
    	<div class="col-sm-10 form-control-static">
    		<?php echo $data['name']; ?>
    	</div>
    </div>
    <div class="form-group">
    	<label class="col-sm-2 control-label">아이디</label>
    	<div class="col-sm-10 form-control-static">
    		<?php echo $data['userid']; ?>
    	</div>
    </div>

</fieldset>

<div class="well well-sm clearfix">
    <div class="btn-group pull-right">
        <?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
            <a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn btn-default btn-sm">수정</a>
        <?php endif; ?>
        <?php if($this->auth->check(array('action'=>'delete')) == TRUE) : ?>
            <a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" class="btn btn-default btn-sm" onclick="return confirm('삭제 하시겠습니까?');">삭제</a>
        <?php endif; ?>
        <a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn btn-default btn-sm">목록보기</a>
    </div>
</div>
</div>
