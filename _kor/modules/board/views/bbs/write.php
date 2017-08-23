<?php echo validation_errors(); ?>
<!-- 게시판 입력 -->
<div class="table_wrap inputFrm">
	<?php echo form_open($this->link->get(array('action'=>'save')), array('id'=>'writeForm')); ?>
	<input type="hidden" name="id" value="<?php echo set_value('id', $data['id']); ?>" />
	<input type="hidden" name="contents" value="<?php echo set_value('contents', $data['contents']); ?>" />
	<legend>입력 폼</legend>
	<fieldset>
		<table class="bbs_table viewType"><!-- 뷰형식의 테이블일 때 "viewType"추가 -->
			<caption>게시물 입력 - 제목, 내용, 파일로 나뉘어 입력</caption>
			<colgroup><col style="width:145px" /><col /></colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="subject">제목</label></th>
				<td>
					<?php if(isset($custom_alarm) AND $custom_alarm == 'Y') : ?>
						<div class="bbs_alarm">
							<label class="checkbox-inline"><input type="checkbox" name="alarm" value="Y"<?php if(empty($data['id'])) : ?> checked="checked"<?php endif; ?> /> 알리기<?php if( ! empty($custom_alarm_info)) : ?><?php echo $custom_alarm_info; ?><?php endif; ?></label>
						</div>
					<?php endif; ?>
					<div class="<?php if($this->setup['use_category'] == 'Y') : ?>bbs_titleipt use_category<?php else : ?>bbs_titleipt<?php endif; ?>">
              <?php if($this->setup['use_category'] == 'Y') : ?>
								<div class="select_box">
									<label for="category">분류</label>
									<select id="category" name="category" class="info_select">
										<option value="">-- 분류 --</option>
                      <?php foreach($category['data'] as $item) : ?>
												<option value="<?php echo $item['id']; ?>" <?php echo set_select('category', $item['id'], ($item['id']==$data['category'])); ?>><?php echo $item['title']; ?></option>
                      <?php endforeach; ?>
									</select>
								</div>
              <?php endif; ?>
						<div><input type="text" id="subject" name="subject" value="<?php echo set_value('subject', $data['subject']); ?>" placeholder="제목 입력" title="제목 입력" /></div>
					</div>
				</td>
			</tr>
      <?php if($this->auth->admin() == TRUE && $this->link->get_segment('tableid', FALSE) != 'photo' && $this->link->get_segment('tableid', FALSE) != 'order') : ?>
				<!--<tr>
					<th scope="row">공지글 여부</th>
					<td>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="isnotice" value="Y" <?php /*echo set_checkbox('isnotice', 'Y', ($data['isnotice']=='Y')); */?> />
								<i class="iCheck">체크</i>
								<span>공지사항</span>
							</label>
						</div>
					</td>
				</tr>-->
      <?php endif; ?>
			<tr>
				<th scope="row"><label for="detail_cnts">내용</label></th>
				<td>
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
				<td>
					<div class="fileForm">
						<span class="iptfile"><input type="file" id="file_attach1" name="file_attach" placeholder="첨부파일 등록" /></span>
					</div>
				</td>
			</tr>-->
			</tbody>
		</table>
		<div class="table_footer">
			<p class="btn_wrap ft_right">
				<button type="submit" class="btn btn-primary">등록</button>
				<a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn">취소</a>
			</p>
		</div>
</div>
<!-- //게시판 입력 -->
