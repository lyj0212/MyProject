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
			<th scope="row"><label for="subject">제목</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('title', $data['title']); ?>"  id="subject" name="title" placeholder="제목 입력" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="clientName">업체명</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('company', $data['company']); ?>" id="company" name="company" placeholder="업체명 입력" /></td>
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
					<input type="text" class="center" id="mobile1_2" name="phone2" value="<?php echo set_value('phone2', $data['phone2']); ?>" title="휴대폰 둘째자리 입력" maxlength="4"/>
					<span class="phone_addon">-</span>
					<input type="text" class="center" id="phone3" name="phone3" value="<?php echo set_value('phone3', $data['phone3']); ?>" title="휴대폰 마지막자리 입력" maxlength="4" />
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="delivery_area">배송지</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('delivery_area', $data['delivery_area']); ?>"  id="delivery_area" name="delivery_area" placeholder="배송지 입력" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="delivery_date">배송요청일</label></th>
			<td><input type="date" value="<?php echo set_value('delivery_date', $data['delivery_date']); ?>" id="delivery_date" name="delivery_date" placeholder="배송요청일 입력" /></td>
			<th scope="row"><label for="build_date">시공일</label></th>
			<td><input type="date" value="<?php echo set_value('build_date', $data['build_date']); ?>" id="build_date" name="build_date" placeholder="시공일 입력" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="message">전달 사항</label></th>
			<td colspan="3"><input type="text" value="<?php echo set_value('message', $data['message']); ?>"  id="message" name="message" placeholder="전달 사항 입력" /></td>
		</tr>
		<!--<tr>
			<th scope="row"><label for="detail_cnts">내용</label></th>
			<td colspan="3" class="editor">
          <?php
/*          $params = array(
              'input' => 'contents',
              'pid' => $data['id']
          );
          echo CI::$APP->_editor($params);
          */?>
			</td>
		</tr>-->
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
<br>
	<table class="bbs_table viewType">
		<caption>조명 내역 입력</caption>
		<colgroup><col style="width:145px" /><col /><col style="width:125px" /><col style="width:145px"/><col style="width:145px"/></colgroup>
		<tbody>
		<tr>
			<th scope="row" colspan="5"><label for="detail_cnts">조명</label></th>
		</tr>
		<tr>
			<th scope="row"><label for="detail_cnts">공간/용도</label></th>
			<th scope="row">품명</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">수량</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">조명색</th>
			<th scope="row" style="text-align: center; padding-left: 0px;">램프</th>
		</tr>
		<?php foreach ($light_list['data'] as $item) : ?>
				<tr>
					<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $light_list['order_data'][$item['id']][0]['id']) ? '': $light_list['order_data'][$item['id']][0]['id'] ); ?>"/>
					<td><?php echo $item['title']; ?></td>
					<td><input type="text" value="<?php echo set_value('title', empty( $light_list['order_data'][$item['id']][0]['title']) ? '': $light_list['order_data'][$item['id']][0]['title'] ); ?>" name="title_<?php echo $item['id']; ?>" /></td>
					<td><input type="text" value="<?php echo set_value('cnt', empty( $light_list['order_data'][$item['id']][0]['cnt']) ? '': $light_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
					<td><input type="text" value="<?php echo set_value('color', empty( $light_list['order_data'][$item['id']][0]['color']) ? '': $light_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
					<td><input type="text" value="<?php echo set_value('lamp', empty( $light_list['order_data'][$item['id']][0]['lamp']) ? '': $light_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
				</tr>
		<?php endforeach; ?>
		<tr>
			<th scope="row" colspan="5"><label for="detail_cnts">기타 자재</label></th>
		</tr>
		<tr>
			<td rowspan="10">스위치</td>
			<th scope="row"><label for="detail_cnts">종류</label></th>
			<th scope="row"><label for="detail_cnts">브랜드</label></th>
			<th scope="row"><label for="detail_cnts">브랜드</label></th>
			<th scope="row"><label for="detail_cnts">브랜드</label></th>
		</tr>
    <?php foreach ($switch_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $switch_list['order_data'][$item['id']][0]['id']) ? '': $switch_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><?php echo $item['title']; ?></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $switch_list['order_data'][$item['id']][0]['cnt']) ? '': $switch_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $switch_list['order_data'][$item['id']][0]['color']) ? '': $switch_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $switch_list['order_data'][$item['id']][0]['lamp']) ? '': $switch_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<th scope="row" colspan="4"><label for="detail_cnts">기타 스위치</label></th>
		</tr>
    <?php foreach ($switchetc_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $switchetc_list['order_data'][$item['id']][0]['id']) ? '': $switchetc_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><input type="text" value="<?php echo set_value('title', empty( $switchetc_list['order_data'][$item['id']][0]['title']) ? '': $switchetc_list['order_data'][$item['id']][0]['title'] ); ?>" name="title_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $switchetc_list['order_data'][$item['id']][0]['cnt']) ? '': $switchetc_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $switchetc_list['order_data'][$item['id']][0]['color']) ? '': $switchetc_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $switchetc_list['order_data'][$item['id']][0]['lamp']) ? '': $switchetc_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<td rowspan="12">콘센트</td>
		</tr>
    <?php foreach ($socket_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $socket_list['order_data'][$item['id']][0]['id']) ? '': $socket_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><?php echo $item['title']; ?></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $socket_list['order_data'][$item['id']][0]['cnt']) ? '': $socket_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $socket_list['order_data'][$item['id']][0]['color']) ? '': $socket_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $socket_list['order_data'][$item['id']][0]['lamp']) ? '': $socket_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<td rowspan="4">감지기</td>
		</tr>
    <?php foreach ($sensor_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $sensor_list['order_data'][$item['id']][0]['id']) ? '': $sensor_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><?php echo $item['title']; ?></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $sensor_list['order_data'][$item['id']][0]['cnt']) ? '': $sensor_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $sensor_list['order_data'][$item['id']][0]['color']) ? '': $sensor_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $sensor_list['order_data'][$item['id']][0]['lamp']) ? '': $sensor_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<td rowspan="2">비디오폰</td>
		</tr>
    <?php foreach ($videophone_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $videophone_list['order_data'][$item['id']][0]['id']) ? '': $videophone_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><?php echo $item['title']; ?></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $videophone_list['order_data'][$item['id']][0]['cnt']) ? '': $videophone_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $videophone_list['order_data'][$item['id']][0]['color']) ? '': $videophone_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $videophone_list['order_data'][$item['id']][0]['lamp']) ? '': $videophone_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<td rowspan="2">도어락</td>
		</tr>
    <?php foreach ($doorlock_list['data'] as $item) : ?>
			<tr>
				<input type="hidden" name="coid_<?php echo $item['id']; ?>" value="<?php echo set_value('coid', empty( $doorlock_list['order_data'][$item['id']][0]['id']) ? '': $doorlock_list['order_data'][$item['id']][0]['id'] ); ?>"/>
				<td><?php echo $item['title']; ?></td>
				<td><input type="text" value="<?php echo set_value('cnt', empty( $doorlock_list['order_data'][$item['id']][0]['cnt']) ? '': $doorlock_list['order_data'][$item['id']][0]['cnt'] ); ?>" name="cnt_<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('color', empty( $doorlock_list['order_data'][$item['id']][0]['color']) ? '': $doorlock_list['order_data'][$item['id']][0]['color']); ?>" name="color<?php echo $item['id']; ?>" /></td>
				<td><input type="text" value="<?php echo set_value('lamp', empty( $doorlock_list['order_data'][$item['id']][0]['lamp']) ? '': $doorlock_list['order_data'][$item['id']][0]['lamp'] ); ?>" name="lamp_<?php echo $item['id']; ?>" /></td>
			</tr>
    <?php endforeach; ?>
		<tr>
			<td>비고 및 전달사항</td>
			<td><input type="text" value="<?php echo set_value('etc', $data['etc']); ?>" name="etc" /></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
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

