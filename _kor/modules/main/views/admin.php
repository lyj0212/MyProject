<!--[if IE]><script language="javascript" type="text/javascript" src="/_kor/modules/main/js/excanvas.min.js"></script><![endif]-->

<div style="margin:0; padding:0; line-height:auto;" class="clearfix">
	<?php echo form_open($this->link->get(array('action'=>'convert')), array('class'=>'form-search')); ?>
	<input type="hidden" name="redirect" value="<?php echo $this->link->get(array('action'=>'admin')); ?>" />
	<input type="hidden" name="encoding" value="false" />

	<select name="year" class="span2">
	<?php foreach(range(date('Y')-2, date('Y')) as $years) : ?>
		<option<?php if($years == $year) : ?> selected="selected"<?php endif; ?> value="<?php echo $years; ?>"><?php echo $years; ?></option>
	<?php endforeach; ?>
	</select>
	<select name="month" class="span2">
	<?php foreach(range(1, 12) as $months) : ?>
		<option<?php if($months == $month) : ?> selected="selected"<?php endif; ?> value="<?php echo $months; ?>"><?php echo sprintf('%02d', $months); ?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" value="이동" class="btn btn-primary" />
	</form>
</div>

<div id="linechart" class="clearfix" style="height:350px"></div>

<div id="piechart1" style="height:350px"></div>
<div id="piechart2" style="height:350px"></div>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="clearfix">
<tr>
	<td valign="top" class="dashboard_cell">
		<div class="dashboard_box ">
			<p style=""><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" />전체 방문수 <span style="font-weight:bold"><?php echo number_format($total_pc + $total_mobile); ?></span></p><p style="margin:-5px 0 15px 15px"><span style="font-size:8pt">(PC : <strong><?php echo number_format($total_pc); ?></strong>, MOBILE : <strong><?php echo number_format($total_mobile); ?>)</strong></span></p>
			<p style=""><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" /><?php echo sprintf('%d', $month); ?>월 방문수 <span style="font-weight:bold"><?php echo number_format($month_pc + $month_mobile); ?></span></p><p style="margin:-5px 0 15px 15px"><span style="font-size:8pt">(PC : <strong><?php echo number_format($month_pc); ?></strong>, MOBILE : <strong><?php echo number_format($month_mobile); ?>)</strong></span></p>
			<p style=""><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" />오늘 방문수 <span style="font-weight:bold"><?php echo number_format($today_pc + $today_mobile); ?></span></p><p style="margin:-5px 0 0 15px"><span style="font-size:8pt">(PC : <strong><?php echo number_format($today_pc); ?></strong>, MOBILE : <strong><?php echo number_format($today_mobile); ?>)</strong></span></p>
		</div>
	</td>
	<td width="33%" valign="top" class="dashboard_cell">
		<div class="dashboard_box ">
			<p style="font-weight:bold"><?php echo sprintf('%d', $month); ?>월 검색 사이트를 통한 방문수</p>
			<?php if(count($search_data)) : ?>
			<table>
			<?php foreach($search_data as $item) : ?>
			<tr style="height:20px; color:#555555">
				<td style="padding-right:10px"><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" /><span><?php echo $item['site']; ?></span></td>
				<td style="color:##7187a7; font-weight:bold;"><?php echo number_format($item['hit']); ?>회</td>
			</tr>
			<?php endforeach; ?>
			</table>
			<?php else : ?>
			검색 사이트를 통한 방문이 없습니다.
			<?php endif; ?>
		</div>
	</td>
	<td width="33%" valign="top" class="dashboard_cell">
		<div class="dashboard_box">
			<p style="font-weight:bold"><?php echo sprintf('%d', $month); ?>월 검색 사이트 검색어</p>
			<?php if(count($keyword_data)) : ?>
			<table>
			<?php foreach($keyword_data as $item) : ?>
			<tr style="height:20px; color:#555555">
				<td style="padding-right:10px"><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" /><span title="<?php echo $item['keyword']; ?>"><?php echo str_cut($item['keyword'], 23); ?></span></td>
				<td style="color:##7187a7; font-weight:bold;"><?php echo number_format($item['hit']); ?>회</td>
			</tr>
			<?php endforeach; ?>
			</table>
			<?php else : ?>
			검색 사이트를 통한 방문이 없습니다.
			<?php endif; ?>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" class="dashboard_cell">
		<div class="dashboard_box">
			<p style="font-weight:bold"><?php echo sprintf('%d', $month); ?>월 브라우져 Top10</p>
			<table>
			<?php foreach($browser_data as $item) : ?>
			<tr style="height:20px; color:#555555">
				<td style="padding-right:10px"><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" /><span><?php echo $item['agent']; ?></span></td>
				<td style="color:##7187a7; font-weight:bold;"><?php echo number_format($item['rows']); ?>회</td>
			</tr>
			<?php endforeach; ?>
			</table>
		</div>
	</td>
	<td valign="top" class="dashboard_cell">
		<div class="dashboard_box">
			<p style="font-weight:bold"><?php echo sprintf('%d', $month); ?>월 운영체제 Top10</p>
			<table>
			<?php foreach($platform_data as $item) : ?>
			<tr style="height:20px; color:#555555">
				<td style="padding-right:10px"><img src="/_kor/modules/main/images/bullet.png" style="margin:5px 10px 0 0; vertical-align:top" /><span><?php echo $item['platform']; ?></span></td>
				<td style="color:##7187a7; font-weight:bold;"><?php echo number_format($item['rows']); ?>회</td>
			</tr>
			<?php endforeach; ?>
			</table>
		</div>
	</td>
	<td valign="top" class="dashboard_cell">
	</td>
</tr>
</table>

<div class="clear"></div>
