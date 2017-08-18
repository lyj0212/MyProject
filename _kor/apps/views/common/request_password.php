<?php echo validation_errors(); ?>
<?php echo form_open('', array('class'=>'form-inline')); ?>
<form class="form-inline" method="post" action="/form/">
  <fieldset>
    <div class="control-group centered">
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on"><i class="icon-lock"></i></span>
          <input type="password" name="_request_passwd" maxlength="100" placeholder="비밀번호" />
         </div>
        <button type="submit" class="btn">확인</button>
        <span class="mt10 help-block">글 작성시 설정한 비밀번호를 입력해 주세요.</span>
      </div>
    </div>
  </fieldset>
</form>