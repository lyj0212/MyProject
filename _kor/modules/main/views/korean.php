				<!-- 본문 컨텐츠 영역 -->
				<div class="mCnts_wrap">
					<div class="mCnts1">
						<div class="mVisual_wrap">
							<h3 class="hide">비주얼</h3>
							<ul class="obj">
							<li class="no1">
								<div class="mVisual_desc">
									<img src="/images/main/visual_img01.jpg" alt="대덕연구단지와 대덕 테크노 밸리를 잇는 산업 혁신 클러스터" />
									<div class="title">
										<strong>대덕SW융합클러스터 D-cube[D<sup>3</sup> 클러스터]</strong>
										<span class="desc">대덕연구단지와 대덕 테크노 밸리를 잇는 <strong>산업 혁신 <em>클러스터</em></strong></span>
									</div>
								</div>
							</li>
							<li class="no2">
								<div class="mVisual_desc">
									<img src="/images/main/visual_img02.jpg" alt="대덕연구단지와 대덕 테크노 밸리를 잇는 산업 혁신 클러스터" />
									<div class="title">
										<strong>대덕SW융합클러스터 D-cube[D<sup>3</sup> 클러스터]</strong>
										<span class="desc">대덕연구단지와 대덕 테크노 밸리를 잇는 <strong>산업 혁신 <em>클러스터</em></strong></span>
									</div>
								</div>
							</li>
							</ul>
							<div class="control">
								<div class="pivot"></div>
								<div class="autoCtrl"></div>
							</div>
						</div>

						<div class="schedule_wrap">
							<h3><span>일정안내</span></h3>
							<div class="schedulelst_wrap">
								<ul class="schedule_lst">
									<?php if(empty($schedule['data'])) :?>
										<li><a href="/events">등록된 일정이 없습니다.</a></li>
									<?php else :?>
										<?php foreach($schedule['data'] as $k => $item) : ?>
										<li><a href="/events"><span class="date">[<?php echo date('Y.m.d', strtotime($item['start_date'])); ?>]</span><?php echo $item['title'];?></a></li>
										<?php endforeach;?>
									<?php endif;?>
								</ul>
							</div>
						</div>

						<div class="quickLink_wrap">
							<ul>
								<?php foreach($category['data'] as $k => $item) : ?>
									<li class="no<?php echo $k+1;?>"><a href="<?php echo $this->link->get(array('module'=>'apply', 'controller'=>'bizannounce', 'action'=>'index', 'id' => NULL, 'search_category'=>encode_url($item['id'])), TRUE); ?>"><span><?php echo str_replace('지원사업', '<br/>지원사업', $item['title']); ?></span></a></li> 
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					
					<div class="mCnts2">
						<div class="news_wrap">
							<h3>공지사항/보도자료</h3>
							<ul class="news_lst notice">
								<?php foreach($board['data'] as $item) : ?>
									<li><a href="<?php echo $this->link->get(array('prefix'=>$item['tableid'], 'module'=>'board', 'controller'=>'bbs', 'action'=>'view', 'tableid'=>$item['tableid'], 'id'=>$item['id']), TRUE); ?>"><em class="cate <?php echo $item['cate'];?>"><?php echo $item['cate_text'];?></em> <?php echo $item['subject']; ?></a><span class="date"><?php echo date('Y.m.d', strtotime($item['created'])); ?></span></li> 
								<?php endforeach; ?>
							</ul>
							<p class="more"><a href="/notice">공지사항/보도자료, 더보기</a></p>
						</div>

						<div class="news_wrap">
							<h3>사업공고</h3>
							<ul class="news_lst">
								<?php foreach($business['data'] as $item) : ?>
								<li><a href="<?php echo $this->link->get(array('module'=>'apply', 'controller'=>'bizannounce', 'action'=>'view', 'id'=>$item['id'], 'pid'=>NULL), TRUE); ?>"><?php echo $item['subject']; ?></a><span class="date"><?php echo date('Y.m.d', strtotime($item['created'])); ?></span></li>
								<?php endforeach; ?>
							</ul>
							<p class="more"><a href="/apply">사업공고, 더보기</a></p>
						</div>
					</div>

					<div class="mCnts3">
						<ul class="etc_golinklst">
						<li class="no1"><a href="/facilities"><span>시설소개</span></a></li>
						<li class="no2"><a href="/form"><span>양식 및 서식</span></a></li>
						<li class="no3"><a href="/conference"><span>회의실 소개</span></a></li>
						<li class="no4"><a href="/rule"><span>법령 및 규정</span></a></li>
						</ul>

						<p class="etc_platformintro">
							<a href="/platformsvc">
								<strong>SW 클러스터 플랫폼 소개</strong>
								<span class="desc">SW 클러스터 플랫폼을 소개합니다.<br/>사업의 정의 및 추진체계 및 활성화 전략을 알아보세요.</span>
							</a>
						</p>

						<div class="etc_servicelink">
							<p class="location"><a href="/location">찾아오시는길</a></p>
							<div class="organ_links">
								<dl class="network">
								<dt><a href="#">SW융합클러스터 네트워크</a></dt>
								<dd class="organ_links_obj">
									<div class="organ_links_lst">
										<ul>
										<li><a href="http://www.msip.go.kr/" target="_blank" title="새창에서 열림">미래부</a></li>
										<li><a href="https://www.jbcluster.kr/" target="_blank" title="새창에서 열림">SW융합클러스터 전북센터</a></li>
										<li><a href="https://www.biplex.or.kr/" target="_blank" title="새창에서 열림">SW융합클러스터 송도센터</a></li>
										<li><a href="https://www.busansw.net/" target="_blank" title="새창에서 열림">SW융합클러스터 센텀센터</a></li>
										</ul>
									</div>
								</dd>
								</dl>

								<dl>
								<dt><a href="#">전국SW진흥기관</a></dt>
								<dd class="organ_links_obj">
									<div class="organ_links_lst">
										<ul>
										<li><a href="http://www.dicia.or.kr" target="_blank" title="새창에서 열림">대전정보문화산업진흥원</a></li>
										<li><a href="http://www.nipa.kr" target="_blank" title="새창에서 열림">정보통신산업진흥원</a></li>
										<li><a href="http://www.gipa.or.kr" target="_blank" title="새창에서 열림">고양지식정보산업진흥원</a></li>
										<li><a href="http://www.inis.or.kr" target="_blank" title="새창에서 열림">인천정보산업진흥원</a></li>
										<li><a href="http://www.gtp.or.kr" target="_blank" title="새창에서 열림">경기테크노파크</a></li>
										<li><a href="http://www.ayventure.net" target="_blank" title="새창에서 열림">안양창조산업진흥원</a></li>
										<li><a href="http://www.dipa.or.kr" target="_blank" title="새창에서 열림">용인시디지털산업진흥원</a></li>
										<li><a href="http://www.gimc.or.kr" target="_blank" title="새창에서 열림">강원정보문화진흥원</a></li>
										<li><a href="http://www.gsipa.or.kr" target="_blank" title="새창에서 열림">강릉과학산업진흥원</a></li>
										<li><a href="http://www.cbkipa.net" target="_blank" title="새창에서 열림">충북지식산업진흥원</a></li>
										<li><a href="http://www.ctp.or.kr" target="_blank" title="새창에서 열림">충남테크노파크</a></li>
										<li><a href="http://www.jica.kr" target="_blank" title="새창에서 열림">전주정보문화산업진흥원</a></li>
										<li><a href="http://www.gitct.or.kr" target="_blank" title="새창에서 열림">광주정보문화산업진흥원</a></li>
										<li><a href="http://www.jcia.or.kr" target="_blank" title="새창에서 열림">전남정보문화산업진흥원</a></li>
										<li><a href="http://www.dip.or.kr" target="_blank" title="새창에서 열림">대구디지털산업진흥원</a></li>
										<li><a href="http://www.pohangtp.org" target="_blank" title="새창에서 열림">포항테크노파크</a></li>
										<li><a href="http://www.gntp.or.kr" target="_blank" title="새창에서 열림">경남테크노파크</a></li>
										<li><a href="http://www.busanit.or.kr" target="_blank" title="새창에서 열림">부산정보산업진흥원</a></li>
										<li><a href="http://www.uepa.or.kr" target="_blank" title="새창에서 열림">울산경제진흥원</a></li>
										<li><a href="http://www.jejutp.or.kr" target="_blank" title="새창에서 열림">제주테크노파크</a></li>
										</ul>
									</div>
								</dd>
								</dl>
							</div>
						</div>
					</div>
				</div>
				<!-- //본문 컨텐츠 영역 -->