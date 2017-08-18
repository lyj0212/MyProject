<script>
	$(function() {
		var _target_form;
		_target_form = $('#tx_trex_container<?php echo $sequence; ?>').parents('form').attr('id');
		if(_target_form == '' || _target_form == 'undefined' ||  _target_form == null) {
			$('#tx_trex_container<?php echo $sequence; ?>').parents('form').attr('id', 'writeForm<?php echo $sequence; ?>');
			_target_form = $('#tx_trex_container<?php echo $sequence; ?>').parents('form').attr('id');
		}

		var config = {
			txHost: '',
			txPath: '<?php echo $editor_path; ?>',
			txService: 'daumeditor',
			txProject: 'daumeidotr',
			initializedId: "<?php echo $sequence; ?>",
			wrapper: "tx_trex_container<?php echo $sequence; ?>",
			form: _target_form,
			txIconPath: "<?php echo $editor_path; ?>images/icon/editor/",
			txDecoPath: "<?php echo $editor_path; ?>images/deco/contents/",
			canvas: {
				initHeight: 120,
				styles: {
					fontSize: '13px',
					fontFamily: "" + $('div.plani_content').css('fontFamily') + ""
				},
				showGuideArea: true
			},
			events: {
				preventUnload: false
			},
			sidebar: {
				attachbox: {
					show: true
				}
			},
			toolbar: {
				fontfamily: {
					options: [
						{ label: ' 맑은고딕 (<span class="tx-txt">가나다라</span>)', title: '맑은고딕', data: '"맑은 고딕",AppleGothic,sans-serif', klass: 'tx-gulim' },
						{ label: ' 한컴돋움 (<span class="tx-txt">가나다라</span>)', title: '한컴돋움', data: '"한컴돋움",AppleGothic,sans-serif', klass: 'tx-gulim' },
						{ label: ' 굴림 (<span class="tx-txt">가나다라</span>)', title: '굴림', data: 'Gulim,굴림,AppleGothic,sans-serif', klass: 'tx-gulim' },
						{ label: ' 바탕 (<span class="tx-txt">가나다라</span>)', title: '바탕', data: 'Batang,바탕', klass: 'tx-batang' },
						{ label: ' 돋움 (<span class="tx-txt">가나다라</span>)', title: '돋움', data: 'Dotum,돋움', klass: 'tx-dotum' },
						{ label: ' 궁서 (<span class="tx-txt">가나다라</span>)', title: '궁서', data: 'Gungsuh,궁서', klass: 'tx-gungseo' },
						{ label: ' Tahoma (<span class="tx-txt">abcde</span>)', title: 'Tahoma', data: 'Tahoma', klass: 'tx-tahoma' },
						{ label: ' Arial (<span class="tx-txt">abcde</span>)', title: 'Arial', data: 'Arial', klass: 'tx-arial' },
						{ label: ' Verdana (<span class="tx-txt">abcde</span>)', title: 'Verdana', data: 'Verdana', klass: 'tx-verdana' },
						{ label: ' Arial Black (<span class="tx-txt">abcde</span>)', title: 'Arial Black', data: 'Arial Black', klass: 'tx-arial-black' },
						{ label: ' Book Antiqua (<span class="tx-txt">abcde</span>)', title: 'Book Antiqua', data: 'Book Antiqua', klass: 'tx-book-antiqua' },
						{ label: ' Comic Sans MS (<span class="tx-txt">abcde</span>)', title: 'Comic Sans MS', data: 'Comic Sans MS', klass: 'tx-comic-sans-ms' },
						{ label: ' Courier New (<span class="tx-txt">abcde</span>)', title: 'Courier New', data: 'Courier New', klass: 'tx-courier-new' },
						{ label: ' Georgia (<span class="tx-txt">abcde</span>)', title: 'Georgia', data: 'Georgia', klass: 'tx-georgia' },
						{ label: ' Helvetica (<span class="tx-txt">abcde</span>)', title: 'Helvetica', data: 'Helvetica', klass: 'tx-helvetica' },
						{ label: ' Impact (<span class="tx-txt">abcde</span>)', title: 'Impact', data: 'Impact', klass: 'tx-impact' }
					]
				}
			},
			size: {
				//contentWidth: 700
			}
		};
		var chk_enter = true; //canvas.observeJob 포커스 submit 한번만
		EditorJSLoader.ready(function(Editor) {
			<?php if($sequence == 1) : ?>
			Trex.module('add hotkey, ctrl+enter',
				function(editor, toolbar, sidebar, canvas, config){
					canvas.observeJob(Trex.Ev.__CANVAS_PANEL_KEYDOWN, function(ev){
						if (ev.ctrlKey && ev.keyCode == $tx.KEY_RETURN) {
							$tx.stop(ev);
							if(chk_enter)
							{
								if($('#'+_target_form).submit())
								{
									chk_enter = false;
								}
							}
						}
					});
				}
			);
			<?php endif; ?>

			new Editor(config);

			if($('#'+_target_form).find('input[name="<?php echo $input; ?>"]').val()) {
				Editor.onPanelLoadComplete(function () {
					Editor.switchEditor('<?php echo $sequence; ?>');
					Editor.modify({
						content : $('#'+_target_form).find('input[name="<?php echo $input; ?>"]').val()
					});
				});
			}
		});

		$('#'+_target_form).on('submit', function(e) {
			e.preventDefault();
			Editor.switchEditor('<?php echo $sequence; ?>');
			$('#'+_target_form).find('input[name="<?php echo $input; ?>"]').val(Editor.getContent());
			Editor.save();
		});
	});
</script>