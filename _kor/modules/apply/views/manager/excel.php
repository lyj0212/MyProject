<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="application/vnd.ms-excel; charset=utf-8" />
	<title>접수관리</title>
</head>

<body>
<div style="font-size:15pt; font-weight:bold;text-align: center;"><?php echo $title; ?></div>

<table border="1">
	<thead>
	<tr>
		<th bgcolor="#dcdcdc">번호</th>
		<th bgcolor="#dcdcdc">회사명</th>
		<th bgcolor="#dcdcdc">대표자</th>
		<th bgcolor="#dcdcdc">사업자등록번호</th>
		<th bgcolor="#dcdcdc">담당자</th>
		<th bgcolor="#dcdcdc">담당자 이메일</th>
		<th bgcolor="#dcdcdc">담당자 연락처</th>
		<th bgcolor="#dcdcdc">사업분류</th>
		<th bgcolor="#dcdcdc" width="450">공고명</th>
		<th bgcolor="#dcdcdc">접수일</th>
		<th bgcolor="#dcdcdc">접수상태</th>
		<th bgcolor="#dcdcdc">결과</th>

	</tr>
	</thead>
	<tbody>
	<?php $num = count($data['data']); foreach($data['data'] as $item) : ?>
		<tr>
			<td class="centered"><?php echo $num; $num--; ?></td>
			<td class="centered"><?php echo $item['compname']; ?></td>
			<td class="centered"><?php echo $item['ceo_name']; ?></td>
			<td class="centered"><?php echo $item['comp_num']; ?></td>
			<td class="centered"><?php echo $item['chief_name']; ?></td>
			<td class="centered"><?php echo $item['chief_email']; ?></td>
			<td class="centered"><?php echo $item['chief_phone1'].(!empty($item['chief_phone2']) ? '-'.$item['chief_phone2'] : '').(!empty($item['chief_phone3']) ? '-'.$item['chief_phone3'] : ''); ?></td>
			<td class="centered"><?php echo $item['category_title']; ?></td>
			<td class="centered"><?php echo $item['subject']; ?></td>
			<td class="centered"><?php echo date('Y-m-d', strtotime($item['created'])); ?></td>
			<td class="centered">
                <?php switch($item['state']) : case '2' : ?>
                    접수
                <?php break; case '3' : ?>
                    승인
                <?php endswitch; ?>
			</td>
            <td class="centered">
                <?php switch($item['result']) : case '1' : ?>
                    대기
                <?php break; case '2' : ?>
                    미선정
                <?php break; case '3' : ?>
                    선정
                <?php endswitch; ?>
            </td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>


</body>
</html>