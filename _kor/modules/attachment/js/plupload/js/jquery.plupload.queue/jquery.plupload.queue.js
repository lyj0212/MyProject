/**
 * jquery.plupload.queue.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

/* global jQuery:true, alert:true */

/**
jQuery based implementation of the Plupload API - multi-runtime file uploading API.

To use the widget you must include _jQuery_. It is not meant to be extended in any way and is provided to be
used as it is.

@example
	<!-- Instantiating: -->
	<div id="uploader">
		<p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
	</div>

	<script>
		$('#uploader').pluploadQueue({
			url : '../upload.php',
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"}
			],
			rename: true,
			flash_swf_url : '../../js/Moxie.swf',
			silverlight_xap_url : '../../js/Moxie.xap',
		});
	</script>

@example
	// Retrieving a reference to plupload.Uploader object
	var uploader = $('#uploader').pluploadQueue();

	uploader.bind('FilesAdded', function() {
		
		// Autostart
		setTimeout(uploader.start, 1); // "detach" from the main thread
	});

@class pluploadQueue
@constructor
@param {Object} settings For detailed information about each option check documentation.
	@param {String} settings.url URL of the server-side upload handler.
	@param {Number|String} [settings.chunk_size=0] Chunk size in bytes to slice the file into. Shorcuts with b, kb, mb, gb, tb suffixes also supported. `e.g. 204800 or "204800b" or "200kb"`. By default - disabled.
	@param {String} [settings.file_data_name="file"] Name for the file field in Multipart formated message.
	@param {Array} [settings.filters=[]] Set of file type filters, each one defined by hash of title and extensions. `e.g. {title : "Image files", extensions : "jpg,jpeg,gif,png"}`. Dispatches `plupload.FILE_EXTENSION_ERROR`
	@param {String} [settings.flash_swf_url] URL of the Flash swf.
	@param {Object} [settings.headers] Custom headers to send with the upload. Hash of name/value pairs.
	@param {Number|String} [settings.max_file_size] Maximum file size that the user can pick, in bytes. Optionally supports b, kb, mb, gb, tb suffixes. `e.g. "10mb" or "1gb"`. By default - not set. Dispatches `plupload.FILE_SIZE_ERROR`.
	@param {Number} [settings.max_retries=0] How many times to retry the chunk or file, before triggering Error event.
	@param {Boolean} [settings.multipart=true] Whether to send file and additional parameters as Multipart formated message.
	@param {Object} [settings.multipart_params] Hash of key/value pairs to send with every file upload.
	@param {Boolean} [settings.multi_selection=true] Enable ability to select multiple files at once in file dialog.
	@param {Boolean} [settings.prevent_duplicates=false] Do not let duplicates into the queue. Dispatches `plupload.FILE_DUPLICATE_ERROR`.
	@param {String|Object} [settings.required_features] Either comma-separated list or hash of required features that chosen runtime should absolutely possess.
	@param {Object} [settings.resize] Enable resizng of images on client-side. Applies to `image/jpeg` and `image/png` only. `e.g. {width : 200, height : 200, quality : 90, crop: true}`
		@param {Number} [settings.resize.width] If image is bigger, it will be resized.
		@param {Number} [settings.resize.height] If image is bigger, it will be resized.
		@param {Number} [settings.resize.quality=90] Compression quality for jpegs (1-100).
		@param {Boolean} [settings.resize.crop=false] Whether to crop images to exact dimensions. By default they will be resized proportionally.
	@param {String} [settings.runtimes="html5,flash,silverlight,html4"] Comma separated list of runtimes, that Plupload will try in turn, moving to the next if previous fails.
	@param {String} [settings.silverlight_xap_url] URL of the Silverlight xap.
	@param {Boolean} [settings.unique_names=false] If true will generate unique filenames for uploaded files.

	@param {Boolean} [settings.dragdrop=true] Enable ability to add file to the queue by drag'n'dropping them from the desktop.
	@param {Boolean} [settings.rename=false] Enable ability to rename files in the queue.
	@param {Boolean} [settings.multiple_queues=true] Re-activate the widget after each upload procedure.
*/
(function($, o) {
	var uploaders = {};

	function _(str) {
		return plupload.translate(str) || str;
	}

	function renderUI(id, target) {
		// Remove all existing non plupload items
		target.contents().each(function(i, node) {
			node = $(node);

			if (!node.is('.plupload')) {
				node.remove();
			}
		});

		target.prepend(
			'<div class="plupload_wrapper plupload_scroll">' +
				'<div id="' + id + '_container" class="plupload_container">' +
					'<div class="plupload">' +
						'<div class="plupload_content">' +
							'<div class="plupload_file_size">파일첨부 : <span class="plupload_total_file_size">0 b</span></div>' +

							'<ul id="' + id + '_filelist" class="plupload_filelist"></ul>' +

							'<div class="plupload_filelist_footer">' +
								'<div class="plupload_file_name">' +
									'<span class="plupload_upload_status"></span>' +
								'</div>' +
								'<div class="plupload_file_action"></div>' +
								'<div class="plupload_file_status"><span class="plupload_total_status"></span></div>' +
								'<div class="plupload_progress">' +
									'<div class="plupload_progress_container">' +
										'<div class="plupload_progress_bar"></div>' +
									'</div>' +
								'</div>' +
								'<div class="plupload_clearer">&nbsp;</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
				'<input type="hidden" id="' + id + '_count" name="' + id + '_count" value="0" />' +
			'</div>'
		);
	}

	$.fn.pluploadQueue = function(settings) {
		if (settings) {
			this.each(function() {
				var uploader, target, id, contents_bak;

				target = $(this);
				id = target.attr('id');

				if (!id) {
					id = plupload.guid();
					target.attr('id', id);
				}

				contents_bak = target.html();
				renderUI(id, target);

				settings = $.extend({
					dragdrop : true,
					container : id
				}, settings);

				if(!settings.browse_button)
				{
					target.prepend('<button type="button" id="' + id + '_browse" class="btn btn-default btn-sm"><i class="fa fa-folder-open-o"></i> 파일찾기</button>');
					settings.browse_button = id + '_browse';
				}

				// Enable drag/drop (see PostInit handler as well)
				if (settings.dragdrop) {
					settings.drop_element = id + '_filelist';
				}

				uploader = new plupload.Uploader(settings);

				uploaders[id] = uploader;

				function handleStatus(file) {
					var actionClass;

					if (file.status == plupload.DONE) {
						actionClass = 'plupload_done';
					}

					if (file.status == plupload.FAILED) {
						actionClass = 'plupload_failed';
					}

					if (file.status == plupload.QUEUED) {
						actionClass = 'plupload_delete';
					}

					if (file.status == plupload.UPLOADING) {
						actionClass = 'plupload_uploading';
					}

					var icon = $('#' + file.id).attr('class', actionClass).find('.plupload_file_action a').css('display', 'block');
					if (file.hint) {
						icon.attr('title', file.hint);	
					}
				}

				function updateTotalProgress() {
					$('span.plupload_total_status', target).html(uploader.total.percent + '%');
					$('div.plupload_progress_bar', target).css('width', uploader.total.percent + '%');
					$('span.plupload_upload_status', target).html(
						uploader.total.uploaded != uploader.files.length ?
						o.sprintf(_('Uploaded %d/%d files'), uploader.total.uploaded, uploader.files.length)
						:
						'업로드 완료'
					);
				}

				function updateList() {
					var fileList = $('ul.plupload_filelist', target).html(''), inputCount = 0, inputHTML;

					$.each(uploader.files, function(i, file) {
						inputHTML = '';

						if (file.status == plupload.DONE) {
							if (file.target_name) {
								inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_tmpname" value="' + plupload.xmlEncode(file.target_name) + '" />';
							}

							inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_name" value="' + plupload.xmlEncode(file.name) + '" />';
							inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_status" value="' + (file.status == plupload.DONE ? 'done' : 'failed') + '" />';
	
							inputCount++;

							$('#' + id + '_count').val(inputCount);
						}

						fileList.append(
							'<li id="' + file.id + '" title="'+ file.name +'">' + file.name +
								'<div class="plupload_file_info">' +
									'<div class="plupload_file_action"><a href="#"></a></div>' +
									'<div class="plupload_file_status">' + file.percent + '%</div>' +
									'<div class="plupload_file_size">' + plupload.formatSize(file.size) + '</div>' +
								'</div>' +
								'<div class="plupload_clearer">&nbsp;</div>' +
								inputHTML +
							'</li>'
						);

						if (file.status == plupload.DONE && file.response)
						{
							if(settings.editor_mode == true)
							{
								$('#' + file.id).prepend('<a href="#none" class="btn_attach_ins" data-type="' + file.response.file.type + '" data-url="' + file.response.file.file_url + '" data-orig_name="' + file.response.file.orig_name + '" data-download_link="' + file.response.file.download_link + '"><img src="/_kor/modules/attachment/js/plupload/js/jquery.plupload.queue/img/plupload_file_btnattach_upload.png" /></a>');
								//$('#' + file.id).find('.plupload_file_name').append
							}
							$('#' + file.id).prepend('<a href="#none" class="btn_attach_del" rel="' + file.response.file.id + '"><img src="/_kor/modules/attachment/js/plupload/js/jquery.plupload.queue/img/plupload_file_btnattach_del.png" /></a>');
						}

						handleStatus(file);

						$('#' + file.id + '.plupload_delete a').click(function(e) {
							$('#' + file.id).remove();
							uploader.removeFile(file);

							e.preventDefault();
						});

						$('#' + file.id + ' .btn_attach_del').click(function(e) {
							e.preventDefault();

							var param = {};
							param[token_name] = token_hash;
							param['id'] = $(this).attr('rel');

							var $this = $(this);
							$.post(
								'/attachment/files/delete', param, function(msg) {
									if(msg == 'ok')
									{
										$('#' + file.id).remove();
										uploader.removeFile(file);
									}
									else
									{
										alert(msg);
									}
							});
						});

					});

					$('span.plupload_total_file_size', target).html(plupload.formatSize(uploader.total.size));

					if (uploader.total.queued === 0) {
						$('span.plupload_add_text', target).html(_('Add Files'));
					} else {
						$('span.plupload_add_text', target).html(o.sprintf(_('%d files queued'), uploader.total.queued));
					}

					$('a.plupload_start', target).toggleClass('plupload_disabled', uploader.files.length == (uploader.total.uploaded + uploader.total.failed));

					// Scroll to end of file list
					fileList[0].scrollTop = fileList[0].scrollHeight;

					updateTotalProgress();

					// Re-add drag message if there is no files
					if (!uploader.files.length && uploader.features.dragdrop && uploader.settings.dragdrop) {
						$('#' + id + '_filelist').append('<li class="plupload_droptext">' + _('Drag files here.') + '</li>');
						$('.plupload_wrapper', target).slideUp(function() {
							$('._uploader_area').css({padding : 0});
							Resize_Box();
						});
						if(uploader.settings.editor_mode != true)
						{
							$(uploader.settings.browse_button).parent().removeClass('mb10');
						}

						$(document).one('dragover', function(e) {
							$('._uploader_area').css({padding : '10px'});
							if(uploader.settings.editor_mode != true)
							{
								$(uploader.settings.browse_button).parent().addClass('mb10');
							}
							$('.plupload_wrapper', target).slideDown(function() {
								Resize_Box();
							});
						});
					}

					if (uploader.files.length > 0 && (!uploader.features.dragdrop || !uploader.settings.dragdrop)) {
						if(uploader.settings.editor_mode != true)
						{
							$(uploader.settings.browse_button).parent().addClass('mb10');
						}
						$('.plupload_wrapper', target).removeClass('hide');
					}

					if (!uploader.files.length && (!uploader.features.dragdrop || !uploader.settings.dragdrop)) {
						if(uploader.settings.editor_mode != true)
						{
							$(uploader.settings.browse_button).parent().removeClass('mb10');
						}
						$('.plupload_wrapper', target).addClass('hide');
					}

					Resize_Box();
				}

				function destroy() {
					delete uploaders[id];
					uploader.destroy();
					target.html(contents_bak);
					uploader = target = contents_bak = null;
				}

				uploader.bind("UploadFile", function(up, file) {
					$('#' + file.id).addClass('plupload_current_file');
				});

				uploader.bind('Init', function(up, res) {
					// Enable rename support
					if (!settings.unique_names && settings.rename) {
						target.on('click', '#' + id + '_filelist div.plupload_file_name span', function(e) {
							var targetSpan = $(e.target), file, parts, name, ext = "";

							// Get file name and split out name and extension
							file = up.getFile(targetSpan.parents('li')[0].id);
							name = file.name;
							parts = /^(.+)(\.[^.]+)$/.exec(name);
							if (parts) {
								name = parts[1];
								ext = parts[2];
							}

							// Display input element
							targetSpan.hide().after('<input type="text" />');
							targetSpan.next().val(name).focus().blur(function() {
								targetSpan.show().next().remove();
							}).keydown(function(e) {
								var targetInput = $(this);

								if (e.keyCode == 13) {
									e.preventDefault();

									// Rename file and glue extension back on
									file.name = targetInput.val() + ext;
									targetSpan.html(file.name);
									targetInput.blur();
								}
							});
						});
					}

					$('a.plupload_start', target).click(function(e) {
						if (!$(this).hasClass('plupload_disabled')) {
							uploader.start();
						}
						e.preventDefault();
					});

					$('a.plupload_stop', target).click(function(e) {
						e.preventDefault();
						uploader.stop();
					});

					$('a.plupload_start', target).addClass('plupload_disabled');

					$('.plupload_wrapper', target).closest('form').one('dragover', function(e) {
						$('._uploader_area', target).css({padding : '10px'});
						if ((uploader.features.dragdrop && uploader.settings.dragdrop)) {
							if(uploader.settings.editor_mode != true)
							{
								$(uploader.settings.browse_button).parent().addClass('mb10');
							}
							$('.plupload_wrapper', target).slideDown(function() {
								Resize_Box();
							});
						}
					});
				});

				uploader.bind("Error", function(up, err) {
					var file = err.file, message;

					if (file) {
						message = err.message;

						if (err.details) {
							message += " (" + err.details + ")";
						}

						if (err.code == plupload.FILE_SIZE_ERROR) {
							alert(_("Error: File too large:") + " " + file.name);
						}

						if (err.code == plupload.FILE_EXTENSION_ERROR) {
							alert(_("Error: Invalid file extension:") + " " + file.name);
						}
						
						file.hint = message;
						$('#' + file.id).attr('class', 'plupload_failed').find('a').css('display', 'block').attr('title', message);
					}

					if (err.code === plupload.INIT_ERROR) {
						setTimeout(function() {
							destroy();
						}, 1);
					}
				});

				uploader.bind("PostInit", function(up) {
					// features are populated only after input components are fully instantiated
					if (up.settings.dragdrop && up.features.dragdrop) {
						//$(up.settings.browse_button).after(' <i class="fa fa-question-circle fa-lg" title="드래그하여 파일 첨부가 가능합니다."></i>').next().css({cursor : 'pointer', color : '#7E94BF'}).tooltip({container : 'body'});
						$(up.settings.browse_button).attr({title:'드래그하여 파일 첨부가 가능합니다.'}).tooltip({container : 'body'});

						$('#' + id + '_filelist').append('<li class="plupload_droptext">' + _("Drag files here.") + '</li>');

						$('#' + id + '_filelist').on('dragover', function() {
							$(this).addClass('darg_over');
						}).on('dragleave drop', function() {
							$(this).removeClass('darg_over');
						});
					}

					if(!up.features.dragdrop || !up.settings.dragdrop)
					{
						$('#' + id + '_filelist').css({'border':0, 'margin':0});
					}
				});

				uploader.init();

				uploader.bind('StateChanged', function() {
					if (uploader.state === plupload.STARTED) {
						$('li.plupload_delete a,div.plupload_buttons', target).hide();
						$('span.plupload_upload_status,div.plupload_progress,a.plupload_stop', target).css('display', 'block');
						$('span.plupload_upload_status', target).html(
							o.sprintf(_('Uploaded %d/%d files'), uploader.total.uploaded, uploader.files.length)
						);

						if (settings.multiple_queues) {
							$('span.plupload_total_status,span.plupload_total_file_size', target).show();
						}
					} else {
						updateList();
						$('a.plupload_stop,div.plupload_progress', target).hide();
						$('a.plupload_delete', target).css('display', 'block');

						if (settings.multiple_queues && uploader.total.uploaded + uploader.total.failed == uploader.files.length) {
							$(".plupload_buttons,.plupload_upload_status", target).css("display", "inline");
							$(".plupload_start", target).addClass("plupload_disabled");
							$('span.plupload_total_status', target).hide();
						}
					}
				});

				uploader.bind('FilesAdded', function(up) {
					if(settings.max_file_count > 0 && (up.files.length > settings.now_file_count))
					{
						while(up.files.length > settings.now_file_count)
						{
							if(up.files.length > settings.now_file_count)
								uploader.removeFile(up.files[settings.now_file_count]);
						}
						alert('파일첨부는 최대 ' + settings.max_file_count + '개 까지 가능합니다.');
					}

					$('.plupload_wrapper', target).slideDown(function() {
						Resize_Box();
					});
					updateList();
					uploader.start();
				});

				uploader.bind('FilesRemoved', function() {
					// since the whole file list is redrawn for every change in the queue
					// we need to scroll back to the file removal point to avoid annoying
					// scrolling to the bottom bug (see #926)
					var scrollTop = $('#' + id + '_filelist').scrollTop();
					updateList();
					$('#' + id + '_filelist').scrollTop(scrollTop);
				});

				uploader.bind('FileUploaded', function(up, file, response) {
					try {
						response = jQuery.parseJSON(response.response);
						if(response.error.code == '500')
						{
							alert(response.error.message);
							file.status = plupload.FAILED;
						}
					} catch(e) {
					}

					file.response = response;
					handleStatus(file);
				});

				uploader.bind("UploadProgress", function(up, file) {
					// Set file specific progress
					$('#' + file.id + ' div.plupload_file_status', target).html(file.percent + '%');

					handleStatus(file);
					updateTotalProgress();
				});

				uploader.setSettings = function(option, value)
				{
					settings[option] = value;
				};

				// Call setup function
				if (settings.setup) {
					settings.setup(uploader);
				}
			});

			return this;
		} else {
			// Get uploader instance for specified element
			return uploaders[$(this[0]).attr('id')];
		}
	};
})(jQuery, mOxie);
