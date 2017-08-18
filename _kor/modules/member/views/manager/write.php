<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm', 'class'=>'form-horizontal')); ?>
<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
<input type="hidden" name="ismember" value="<?php echo set_value('ismember', $data['id']); ?>" />

<fieldset>
	<h5><strong>기본정보등록</strong></h5>
	<?php if($this->account->get('admin') == 'Y') : ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">그룹</label>
			<div class="col-sm-10 row">
				<div class="col-sm-3">
				 	<select name="group" class="form-control input-sm">
						<?php foreach($group['data'] as $item) : ?>
						<option value="<?php echo $item['id']; ?>" <?php echo set_select('group', $item['id'], ($data['group']==$item['id'])); ?>><?php echo $item['title']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>이름</label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="text" name="name" class="form-control input-sm required" value="<?php echo set_value('name', $data['name']); ?>" />
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>아이디(이메일)</label>
        <div class="col-sm-10 row">
            <div class="col-sm-4">
                <div class="input-group">
				    <input type="email" name="userid" class="form-control input-sm required" value="<?php echo set_value('userid', $data['userid']); ?>" />
                    <?php if (empty($data['userid'])) { ?>
                    <span class="input-group-btn">
                        <a href="" class="btn btn-sm btn-default userid_check">중복체크</a>
                    </span>
                    <?php } ?>
                </div>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label"><span style="color: #ff0000" > * </span>비밀번호<?php if( ! empty($data['id'])) : ?> 변경<?php endif; ?></label>
		<div class="col-sm-10 row">
			<div class="col-sm-3">
				<input type="password" name="passwd" class="form-control input-sm<?php if(empty($data['id'])) : ?> required<?php endif; ?>" value="<?php echo set_value('passwd', $data['passwd']); ?>" />
			</div>
			<?php if( ! empty($data['id'])) : ?>
				<p class="help-block">비밀번호 수정시에만 입력하세요.</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="well well-centered">
		<button type="submit" class="btn btn-sm btn-primary">변경내용 저장</button>
		<a class="btn btn-sm btn-default" href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>">돌아가기</a>
	</div>
</fieldset>

</form>