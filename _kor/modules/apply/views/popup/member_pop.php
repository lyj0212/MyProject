<?php echo validation_errors(); ?>
<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-horizontal')); ?>
<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'member_search', 'page'=>NULL,'sh_text'=>NULL)); ?>" />

<fieldset>
	<div class="form-group">
		<label class="col-sm-2 control-label">회원명</label>
		<div class="col-sm-10 row">
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" class="form-control" name="sh_text" id="sh_text" value="<?php echo set_value('sh_text', get_field('sh_text'));?>"/>
					<span class="input-group-btn"><button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button></span>
				</div>
			</div>
		</div>
	</div>
</fieldset>

</form>

<?php if(!empty($data)) : ?>
<div class="table_wrap">
	<table class="bbs_table table-hover">
		<colgroup>
			<col width="8%" />
			<col width="18%" />
			<col width="22%" />
			<col width="*" />
			<col width="15%" />
			<col width="12%" />
		</colgroup>
		<tr role="row">
			<th scope="col">번호</th>
			<th scope="col">이름</th>
			<th scope="col">아이디</th>
			<th scope="col">회사명</th>
			<th scope="col">가입일</th>
			<th scope="col">선택</th>
		</tr>
		<tbody>
		<?php foreach($data as $item) : ?>
			<tr role="row" class="odd">
				<td class="centered"><?php echo $item['_num']; ?></td>
				<td class="centered"><?php echo $item['name']; ?></td>
				<td class="centered"><?php echo $item['userid']; ?></td>
				<td class="centered"><?php echo $item['compname']; ?></td>
				<td class="centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
				<td class="centered"><a href="javascript:void(0);" class="btn btn-info btn-xs _get" data-id = "<?php echo $item['id']; ?>">선택</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif;?>

<?php if(empty($data)) : ?>
	<div class="well well-sm clearfix centered">등록된 내용이 없습니다.</div>
<?php else : ?>
	<?php echo $paging_element; ?>
<?php endif; ?>


<div class="modal-footer">
	<a href="#" class="btn btn-default btn-sm close_modal">닫기</a>
</div>


