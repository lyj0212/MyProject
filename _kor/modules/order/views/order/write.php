<!-- 게시판 입력 -->
<?php echo validation_errors(); ?>
<div class="table_wrap inputFrm">
	<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm')); ?>
	<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
	<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />
	<table class="bbs_table viewType"><!-- 뷰형식의 테이블일 때 "viewType"추가 -->
		<caption>게시물 입력 - 고객사명, 담당자명, 담당자연락처, 제목, 내용, 파일로 나뉘어 입력</caption>
		<colgroup><col style="width:145px" /><col /><col style="width:145px" /><col /></colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="clientName">고객사명</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('company', $data['company']); ?>" id="company" name="company" placeholder="고객사명 입력" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="customer">담당자명</label></th>
			<td><input type="text" value="<?php echo set_value('name', $data['name']); ?>" id="name" name="name" placeholder="담당자명 입력" /></td>
			<th scope="row"><label for="mobile1_2">담당자 연락처</label></th>
			<td>
				<div class="phone_wrap">
					<div class="select_box">
						<label for="mobile1_1">010</label>
						<select class="info_select" id="phone1" title="휴대폰 첫자리 선택">
							<option value="010" <?php echo set_select('phone1', '010', ($data['phone1']=='010')); ?>>010</option>
							<option value="011" <?php echo set_select('phone1', '011', ($data['phone1']=='011')); ?>>011</option>
							<option value="016" <?php echo set_select('phone1', '016', ($data['phone1']=='016')); ?>>016</option>
							<option value="017" <?php echo set_select('phone1', '017', ($data['phone1']=='017')); ?>>017</option>
							<option value="018" <?php echo set_select('phone1', '018', ($data['phone1']=='018')); ?>>018</option>
							<option value="019" <?php echo set_select('phone1', '019', ($data['phone1']=='019')); ?>>019</option>
						</select>
					</div>
					<span class="phone_addon">-</span>
					<input type="text" class="center" id="mobile1_2" name="phone2" value="<?php echo set_value('phone2', $data['phone2']); ?>" title="휴대폰 둘째자리 입력" />
					<span class="phone_addon">-</span>
					<input type="text" class="center" id="phone3" name="phone3" value="<?php echo set_value('phone3', $data['phone3']); ?>" title="휴대폰 마지막자리 입력" />
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="subject">제목</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('title', $data['title']); ?>"  id="subject" name="title" placeholder="제목 입력" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="detail_cnts">내용</label></th>
			<td colspan="3" class="editor">
          <?php
          $params = array(
              'input' => 'contents',
              'pid' => $data['id']
          );
          echo CI::$APP->_editor($params);
          ?>
			</td>
		</tr>
		<!--<tr>
			<th scope="row"><label for="file_attach1">파일첨부</label></th>
			<td colspan="3">
				<div class="fileForm">
					<span class="iptfile"><input type="file" id="file_attach1" name="file_attach" placeholder="첨부파일 등록" /></span>
				</div>
			</td>
		</tr>-->
		</tbody>
	</table>

	<div class="table_footer">
		<p class="btn_wrap btn_regist">
			<button type="submit" class="btn btn-primary"><span><i class="pe-7s-check" aria-hidden="true"></i>발주신청</span></button>
		</p>
	</div>
	</form>
</div>
<!-- //게시판 입력 -->

