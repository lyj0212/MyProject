<div id="tx_trex_container<?php echo $sequence; ?>" class="tx-editor-container">
<div id="tx_sidebar<?php echo $sequence; ?>" class="tx-sidebar">
	<div class="tx-sidebar-boundary">
		<ul class="tx-bar tx-bar-left tx-nav-attach">
			<li class="tx-list">
				<div unselectable="on" id="editor-attach-parent<?php echo $sequence; ?>" class="component_btn tx-btn-trans">
					<a href="javascript:;" id="editor-attach-btn<?php echo $sequence; ?>" class="attach"><span class="tx-searcher-title ico_file" style="width:15px;background-position:-106px 6px"></span>파일첨부</a>
				</div>
			</li>
		</ul>
		<!-- 사이드바 / 우측영역 -->
		<ul class="tx-bar tx-bar-right">
			<li class="tx-list">
				<div unselectable="on" class="tx-btn-lrbg tx-fullscreen" id="tx_fullscreen<?php echo $sequence; ?>">
					<a href="javascript:;" class="tx-icon" title="넓게쓰기 (Ctrl+M)">넓게쓰기</a>
				</div>
			</li>
		</ul>
		<ul class="tx-bar tx-bar-right tx-nav-opt">
			<li class="tx-list">
				<div unselectable="on" class="tx-switchtoggle" id="tx_switchertoggle<?php echo $sequence; ?>">
					<a href="javascript:;" title="에디터 타입">에디터</a>
				</div>
			</li>
		</ul>
	</div>
</div>

<!-- 툴바 - 기본 시작 -->
<!--
		@decsription
		툴바 버튼의 그룹핑의 변경이 필요할 때는 위치(왼쪽, 가운데, 오른쪽) 에 따라 <li> 아래의 <div>의 클래스명을 변경하면 된다.
		tx-btn-lbg: 왼쪽, tx-btn-bg: 가운데, tx-btn-rbg: 오른쪽, tx-btn-lrbg: 독립적인 그룹

		드롭다운 버튼의 크기를 변경하고자 할 경우에는 넓이에 따라 <li> 아래의 <div>의 클래스명을 변경하면 된다.
		tx-slt-70bg, tx-slt-59bg, tx-slt-42bg, tx-btn-43lrbg, tx-btn-52lrbg, tx-btn-57lrbg, tx-btn-71lrbg
		tx-btn-48lbg, tx-btn-48rbg, tx-btn-30lrbg, tx-btn-46lrbg, tx-btn-67lrbg, tx-btn-49lbg, tx-btn-58bg, tx-btn-46bg, tx-btn-49rbg
	-->
<div id="tx_toolbar_basic<?php echo $sequence; ?>" class="tx-toolbar tx-toolbar-basic">
<div class="tx-toolbar-boundary">
	<ul class="tx-bar tx-bar-left">
		<li class="tx-list">
			<div unselectable="on" class="		 tx-btn-lrbg 	tx-emoticon2" id="tx_emoticon2<?php echo $sequence; ?>">
				<a href="javascript:;" class="tx-icon" title="이모티콘">이모티콘</a>
			</div>
			<div id="tx_emoticon2_menu<?php echo $sequence; ?>" class="tx-emoticon2-menu tx-menu" unselectable="on"></div>
		</li>
	</ul>
<ul class="tx-bar tx-bar-left">
	<li class="tx-list">
		<div unselectable="on" class="tx-slt-42bg tx-fontsize" id="tx_fontsize<?php echo $sequence; ?>">
			<a href="javascript:;" title="글자크기">9pt</a>
		</div>
		<div id="tx_fontsize_menu<?php echo $sequence; ?>" class="tx-fontsize-menu tx-menu" unselectable="on"></div>
	</li>
</ul>
<ul class="tx-bar tx-bar-left tx-group-font">
	<li class="tx-list">
		<div unselectable="on" class="		 tx-btn-lbg 	tx-bold" id="tx_bold<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="굵게 (Ctrl+B)">굵게</a>
		</div>
	</li>
	<li class="tx-list">
		<div unselectable="on" class="		 tx-btn-bg 	tx-underline" id="tx_underline<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="밑줄 (Ctrl+U)">밑줄</a>
		</div>
	</li>
	<li class="tx-list hidden-xs">
		<div unselectable="on" class="		 tx-btn-bg 	tx-italic" id="tx_italic<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="기울임 (Ctrl+I)">기울임</a>
		</div>
	</li>
	<li class="tx-list">
		<div unselectable="on" class="		 tx-btn-bg 	tx-strike" id="tx_strike<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="취소선 (Ctrl+D)">취소선</a>
		</div>
	</li>
	<li class="tx-list">
		<div unselectable="on" class="		 tx-slt-tbg 	tx-forecolor" id="tx_forecolor<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="글자색">글자색</a>
			<a href="javascript:;" class="tx-arrow" title="글자색 선택">글자색 선택</a>
		</div>
		<div id="tx_forecolor_menu<?php echo $sequence; ?>" class="tx-menu tx-forecolor-menu tx-colorpallete" unselectable="on"></div>
	</li>
	<li class="tx-list">
		<div unselectable="on" class="		 tx-slt-brbg 	tx-backcolor" id="tx_backcolor<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="글자 배경색">글자 배경색</a>
			<a href="javascript:;" class="tx-arrow" title="글자 배경색 선택">글자 배경색 선택</a>
		</div>
		<div id="tx_backcolor_menu<?php echo $sequence; ?>" class="tx-menu tx-backcolor-menu tx-colorpallete" unselectable="on"></div>
	</li>
</ul>
<ul class="tx-bar tx-bar-left tx-group-etc">
	<li class="tx-list">
		<div unselectable="on" class="		 tx-btn-lrbg 	tx-link" id="tx_link<?php echo $sequence; ?>">
			<a href="javascript:;" class="tx-icon" title="링크 (Ctrl+K)">링크</a>
		</div>
		<div id="tx_link_menu<?php echo $sequence; ?>" class="tx-link-menu tx-menu"></div>
	</li>
	</ul>
</div>
</div>
<!-- 툴바 - 기본 끝 -->

<!-- 편집영역 시작 -->
<!-- 에디터 Start -->
<div id="tx_canvas<?php echo $sequence; ?>" class="tx-canvas">
	<div id="tx_loading<?php echo $sequence; ?>" class="tx-loading"><div><img src="<?php echo $editor_path; ?>images/icon/editor/loading2.png" width="113" height="21" align="absmiddle"/></div></div>
	<div id="tx_canvas_wysiwyg_holder<?php echo $sequence; ?>" class="tx-holder" style="display:block;">
		<iframe id="tx_canvas_wysiwyg<?php echo $sequence; ?>" name="tx_canvas_wysiwyg" allowtransparency="true" frameborder="0"></iframe>
	</div>
	<div class="tx-source-deco">
		<div id="tx_canvas_source_holder<?php echo $sequence; ?>" class="tx-holder">
			<textarea id="tx_canvas_source<?php echo $sequence; ?>" rows="30" cols="30"></textarea>
		</div>
	</div>
	<div id="tx_canvas_text_holder<?php echo $sequence; ?>" class="tx-holder">
		<textarea id="tx_canvas_text<?php echo $sequence; ?>" rows="30" cols="30"></textarea>
	</div>
</div>
<!-- 높이조절 Start -->
<div id="tx_resizer<?php echo $sequence; ?>" class="tx-resize-bar">
	<div class="tx-resize-bar-bg"></div>
	<img id="tx_resize_holder<?php echo $sequence; ?>" src="<?php echo $editor_path; ?>images/icon/editor/skin/01/btn_drag01.gif" width="58" height="12" unselectable="on" alt="" />
</div>

<!-- 편집영역 끝 -->
<!-- 첨부박스 시작 -->
<!-- 파일첨부박스 Start -->
<div id="tx_attach_div<?php echo $sequence; ?>" class="tx-attach-div">
	<div id="tx_attach_txt<?php echo $sequence; ?>" class="tx-attach-txt">파일 첨부</div>
	<div id="tx_attach_box<?php echo $sequence; ?>" class="tx-attach-box">
		<div class="tx-attach-box-inner">
			<div id="tx_attach_preview<?php echo $sequence; ?>" class="tx-attach-preview"><p></p><img src="<?php echo $editor_path; ?>images/icon/editor/pn_preview.gif" width="147" height="108" unselectable="on"/></div>
			<div class="tx-attach-main">
				<div id="tx_upload_progress<?php echo $sequence; ?>" class="tx-upload-progress"><div>0%</div><p>파일을 업로드하는 중입니다.</p></div>
				<ul class="tx-attach-top">
					<li id="tx_attach_delete<?php echo $sequence; ?>" class="tx-attach-delete"><a>전체삭제</a></li>
					<li id="tx_attach_size<?php echo $sequence; ?>" class="tx-attach-size">
						파일: <span id="tx_attach_up_size<?php echo $sequence; ?>" class="tx-attach-size-up"></span>/<span id="tx_attach_max_size<?php echo $sequence; ?>"></span>
					</li>
					<li id="tx_attach_tools<?php echo $sequence; ?>" class="tx-attach-tools">
					</li>
				</ul>
				<ul id="tx_attach_list<?php echo $sequence; ?>" class="tx-attach-list"></ul>
			</div>
		</div>
	</div>
</div>
<!-- 첨부박스 끝 -->
</div>
<!-- 에디터 컨테이너 끝 -->

<div class="plani_content"></div>

<?php
$params = array(
	'target_button' => sprintf('editor-attach-btn%s', $sequence),
	'pid' => $pid,
	'target' => $sequence,
	'editor_sequence' => $sequence,
	'skin' => 'daumeditor',
	'files' => ( ! empty($files)) ? $files : array(),
);
echo CI::$APP->_uploader($params);
?>