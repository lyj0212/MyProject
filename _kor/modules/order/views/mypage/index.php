<div class="orderState_wrap">
	<h2>발주현황 한눈에 보기</h2>
	<div class="orderState_lst">
		<ul>
			<li class="no1">
				<div class="orderState">
					<strong>발주대기</strong>
					<span><em><?php echo $order1; ?></em>건</span>
				</div>
			</li>
			<li class="no2">
				<div class="orderState">
					<strong>발주완료</strong>
					<span><em><?php echo $order2; ?></em>건</span>
				</div>
			</li>
			<li class="no3">
				<div class="orderState">
					<strong>발주취소</strong>
					<span><em><?php echo $order3; ?></em>건</span>
				</div>
			</li>
		</ul>
	</div>
</div>

<ul class="mypage_wrap">
	<li>
		<div class="notice">
			<h2>공지사항</h2>
			<ul>
				<?php foreach ($notice['data'] as $item) : ?>
					<li><a href="<?php echo $this->link->get(array('module' => 'order_notice', 'controller' => 'bbs', 'action'=>'view', 'id'=>$item['id'], 'tableid' => 'notice')); ?>"><?php echo $item['subject']; ?></a><span class="date">[<?php echo date('Y-m-d', strtotime($item['created'])); ?>]</span></li>
				<?php endforeach;?>
			</ul>
		</div>
	</li>
	<li>
		<div class="location">
			<h2>오시는길</h2>
			<ul>
				<li>전화 : 010-5415-1695</li>
				<li>이메일 : mail@sdi.co.kr</li>
				<li>팩스 : 051-956-0053</li>
				<li>주소 : 부산광역시 남구 신선로 365, 10공학관 105호</li>
			</ul>
			<p class="golink">
				<a href="http://map.naver.com/?searchCoord=01648c63b7db462350c94a2d041a4bbc92bf0be6ce9886e35861bcf51a0fa5a1&query=67aA7IKw6rSR7Jet7IucIOuCqOq1rCDsi6DshKDroZwgMzY1LCAxMOqzte2Vmeq0gCAxMDXtmLg%3D&menu=location&tab=1&lng=095cb659692c3ca331a89ad3893c2705&mapMode=0&mpx=08290108%3A35.1160053%2C129.0907175%3AZ12%3A0.0164904%2C0.0079883&lat=1e065617ac6921d9d61ef2cd093f1bc2&dlevel=12&enc=b64" target="_blank">
					네이버 길찾기 바로가기
				</a>
			</p>
		</div>
	</li>
	<li>
		<div class="manual">
			<p><a href="#">MANUAL 가이드</a></p>
		</div>
	</li>
</ul>


