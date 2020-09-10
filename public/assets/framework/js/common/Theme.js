var mskApp = mskApp || {};
mskApp.theme = {
	init : function(){
		mskApp.ContentInitializer.add('richEditor', mskApp.theme.initRichEditor);
		mskApp.ContentInitializer.add('listSortable', mskApp.theme.initListSortable);
		mskApp.ContentInitializer.add('datepicker', mskApp.theme.initDatepicker);
		mskApp.ContentInitializer.add('datetimepicker', mskApp.theme.initDatetimepicker);
		mskApp.ContentInitializer.add('select2', mskApp.theme.initSelect2);
		mskApp.theme.customInit();
		mskApp.theme.handleGlobalEvent();
		mskApp.theme.initFileUpload();
		mskApp.theme.initDialog();
		mskApp.theme.initLangFieldSwitcher();
		mskApp.theme.initFullNavigation();
		mskApp.theme.initListPanel();
		mskApp.theme.initFormPanel();
		mskApp.theme.initViewPanel();
		mskApp.theme.initCollapse();
		mskApp.theme.initAccordion();
		mskApp.theme.initCustomTree();
	},
	customInit : function(){
		
	},
	initDialog : function(){
		$(document).on('shown.bs.modal', '.modal', function(e){
			$(this).show();
			mskApp.theme.setModalMaxHeight(this);
		});
		$(document).on('hidden.bs.modal', '.modal', function(e){
			$(this).data('bs.modal', null).remove();
		});
		$(window).resize(function() {
			if ($('.modal.in').length != 0) {
				mskApp.theme.setModalMaxHeight($('.modal.in'));
			}
		});
	},
	initLangFieldSwitcher : function(){
		$(document).on('click', '.lang_field_switcher', function(e){
			e.preventDefault();
			var target = $(this);
			var lang = target.attr('data-lang');
			$('.current_lang_field_switcher_label').text(target.attr('data-label'));
			$('.lang_field_switcher').removeClass('active');
			$('.translatable_field').hide();
			$('.translatable_field[data-lang='+lang+']').show();
			$('.lang_field_switcher[data-lang='+lang+']').addClass('active');
		});
	},
	setModalMaxHeight : function(element){
		this.$element     = $(element);  
		this.$content     = this.$element.find('.modal-content');
		var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
		var dialogMargin  = $(window).width() < 768 ? 20 : 60;
		var contentHeight = $(window).height() - (dialogMargin + borderWidth);
		var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
		var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
		var maxHeight     = contentHeight - (headerHeight + footerHeight);
		this.$content.css({'overflow': 'hidden'});
		this.$element.find('.modal-body').css({'max-height': maxHeight,'overflow-y': 'auto'});
	},
	initFileUpload : function(){
		$(document).on('drag dragstart dragend dragover dragenter dragleave drop', '.upload_parent_block', function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
		$(document).on('dragover dragenter', '.upload_parent_block', function(e) {
			$(this).addClass('is-dragover');
		});
		$(document).on('dragleave dragend drop', 'upload_parent_block', function(e) {
			$(this).removeClass('is-dragover');
		});
		$(document).on('drop', '.upload_parent_block', function(e) {
			$(this).removeClass('is-dragover');
		});
		$(document).on('drop', 'upload_parent_block', function(e) {
			mskApp.theme.createUploadContainer($(this).closest('.upload_parent_block')).onFileChange(e.originalEvent.dataTransfer.files);
		});
		$(document).on('click', '.upload_parent_block .btn_select_file', function(e) {
			e.preventDefault();
			mskApp.theme.createUploadContainer($(this).closest('.upload_parent_block')).selectFiles();
		});
		$(document).on('click', '.upload_parent_block .preview_item .btn_edit', function(e) {
			e.preventDefault();
			var target = $(this);
			mskApp.theme.createUploadContainer(target.closest('.upload_parent_block')).editUploadFromAction(target);
		});
		$(document).on('click', '.upload_parent_block .preview_item .btn_remove', function(e) {
			e.preventDefault();
			var target = $(this);
			var parentBlock = target.closest('.upload_parent_block');
			mskApp.theme.createUploadContainer(parentBlock).deleteUpload(target);
		});
	},
	createUploadContainer : function(parentBlock){
		return mskApp.FileUpload.createUploadContainer(parentBlock);
	},
	showDialog : function(content, modal, showClose, dialogClass, attributesStr){
		var dialog = $(mskApp.theme.getDialogContent(content, dialogClass, attributesStr, modal, showClose));
		options={};
		if(modal){
			options.backdrop = 'static';
		}
		dialog.modal(options);
		return dialog;
	},
	showFullDialog : function(content, showClose, headerText, footerContent, modal, dialogClass, attributesStr, innerDialogClass){
		var dialog = $(mskApp.theme.getFullDialogContent(content, showClose, headerText, footerContent, modal, dialogClass, attributesStr, innerDialogClass));
		options={};
		if(modal){
			options.backdrop = 'static';
		}
		dialog.modal(options);
		return dialog;
	},
	closeDialog : function(dialog){
		if((typeof(dialog)!=='undefined') && (dialog!=null)){
			dialog.modal('hide');
		}
	},
	getDialogContent : function(content, dialogClass, attributesStr, modal, showClose){
		var html = ''+
		'<div class="modal fade'+mskApp.Strings.addPrefix(dialogClass, ' ')+'" tabindex="-1" role="dialog" aria-hidden="true"'+mskApp.Strings.addPrefix(attributesStr, ' ')+'>'+
			'<div class="modal-dialog" role="document">'+
				content+
			'</div>'+
		'</div>';
		return html;
	},
	getFullDialogContent : function(content, showClose, headerText, footerContent, modal, dialogClass, attributesStr, innerDialogClass){
		var html = '<div class="modal fade'+mskApp.Strings.addPrefix(dialogClass, ' ')+'" tabindex="-1" role="dialog" aria-hidden="true"'+mskApp.Strings.addPrefix(attributesStr, ' ')+'><div class="modal-dialog'+mskApp.Strings.addPrefix(innerDialogClass, ' ')+'" role="document"><div class="modal-content">';
		if(!mskApp.Strings.isEmpty(headerText) || showClose){
			html += '<div class="modal-header">'+mskApp.Strings.addPrefixAndSuffix(headerText, '<h5 class="modal-title">', '</h5>')+(showClose ? '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : '')+'</div>';
		}
		html += '<div class="modal-body">'+content+'</div>'+mskApp.Strings.addPrefixAndSuffix(footerContent, '<div class="modal-footer">', '</div>')+'</div></div></div>';
		return html;
	},
	initFullNavigation : function(){
		$(document).on('click', '.full_navigation .nav_item > a', function(e){
			e.preventDefault();
			var target = $(this);
			var item = target.closest('.nav_item');
			
			var name = item.attr('data-name');
			if(name){
				item.siblings('.nav_item.active').removeClass('active');
				item.addClass('active');
				var navContents = item.closest('.full_navigation').children('.nav_contents');
				var navContent = navContents.children('.nav_content[data-name='+name+']');
				navContent.siblings('.nav_content').hide();
				mskApp.theme.loadNavContent(target, item, navContent);
			}
		});
	},
	initCollapse : function(){
		$(document).on('click', '.msk_collapse > .collapse_header', function(e) {
			e.preventDefault();
			var target = $(this);
			var currentBlock = target.closest('.msk_collapse');
			var blockToDisplay = currentBlock.children('.collapse_body');

			if(currentBlock.hasClass('open')){
				blockToDisplay.slideUp('slow', function(){
					currentBlock.addClass('closed').removeClass('open');
				});
			}else{
				blockToDisplay.slideDown('slow', function(){
					currentBlock.addClass('open').removeClass('closed');
				});
			}
		});
	},
	initAccordion : function(){
		$(document).on('click', '.msk_accordion:not(.has_specific_trigger) >.item > .accordion_header, .msk_accordion.has_specific_trigger >.item > .accordion_header .specific_trigger', function(e) {
			/*e.preventDefault();*/
			var target = $(this);
			var accordionBlock = target.closest('.msk_accordion');
			var currentBlock = target.closest('.item');
			var blockToDisplay = currentBlock.children('.accordion_body');

			/*Close other blocks*/
			if((accordionBlock.length > 0) && !accordionBlock.hasClass('multiple')){
				if(!currentBlock.hasClass('open')){
					var otherBlocks = accordionBlock.children('.item.open');
					otherBlocks.addClass('closed').removeClass('open');
					otherBlocks.each(function(){
						$(this).children('.accordion_body').hide();
					});
				}
			}

			if(currentBlock.hasClass('open')){
				blockToDisplay.slideUp('slow', function(){
					currentBlock.addClass('closed').removeClass('open');
				});
			}else{
				blockToDisplay.slideDown('slow', function(){
					currentBlock.addClass('open').removeClass('closed');
				});
			}
		});
	},
	initCustomTree : function(){
		$(document).on('click', '.custom_tree >.item > .tree_header .open_switcher', function(e) {
			e.preventDefault();
			var target = $(this);
			var currentBlock = target.closest('.item');
			var blockToDisplay = currentBlock.children('.sub_tree');
			
			if(currentBlock.hasClass('open')){
				blockToDisplay.slideUp('slow', function(){
					currentBlock.addClass('closed').removeClass('open');
				});
			}else{
				blockToDisplay.slideDown('slow', function(){
					currentBlock.addClass('open').removeClass('closed');
				});
			}
		});
		$(document).on('change', '.custom_tree >.item > .tree_header .checkable_checkbox', function(e) {
			var target = $(this);
			var checked = target.is(':checked');
			if(checked){
				var parent = target.closest('.custom_tree').parent('.item');
				if(parent.length > 0){
					var parentCheckbox = parent.find('> .tree_header .checkable_checkbox');
					parentCheckbox.prop('checked', true);
					parentCheckbox.trigger('change');
				}
			}
		});
	},
	loadNavContent : function(navLink, navItem, navContentParent){
		var loaded = navItem.attr('data-loaded');
		if(!loaded){
			navContentParent.show();
			var url = navLink.attr('href');
			var finalUrl = mskApp.Strings.appendDataToUrl(url, {'actionOpenMode' : 'in_nav'});
			mskApp.Tools.quickAjaxCall(url, null, 'in_nav', mskApp.theme.loaders.getInnerReplace(navContentParent, true), navLink, mskApp.theme.contentViewers.getInnerReplace(navContentParent, true), mskApp.theme.alertViewers.getDefault(navContentParent), null, function(){navItem.attr('data-loaded', '1');});
		}else{
			navContentParent.show();
		}
	},
	initListPanel : function(){
		$(document).on('click', '.list_panel.ajax .item_action:not(.no_ajax):not(.confirm_command)', function(e){
			e.preventDefault();
			mskApp.theme.listManager.runItemAction($(this));
		});
		$(document).on('click', '.list_panel.ajax .main_action:not(.no_ajax):not(.confirm_command)', function(e){
			e.preventDefault();
			mskApp.theme.listManager.runMainAction($(this));
		});
		$(document).on('click', '.list_panel.ajax .pagination .pagination_link:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			var url = target.attr('href');
			mskApp.theme.listManager.runPagination(target);
		});
		$(document).on('click', '.list_panel.ajax .list_limiter .limiter_link:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			var url = target.attr('href');
			mskApp.theme.listManager.runLimiter(url, target);
		});
		$(document).on('change', '.list_panel .checkable_checkbox:not(.check_all_switcher)', function(e){
			var listPanel = $(this).closest('.list_panel');
			var selectedTotal = listPanel.find('.checkable_checkbox:not(.check_all_switcher):checked').length;
			var selectedDataIndicator = listPanel.find('.selected_data_indicator');
			if(selectedTotal > 0){
				var text = (selectedTotal > 1) ? selectedDataIndicator.attr('data-plural_text') : selectedDataIndicator.attr('data-singular_text');
				if(!mskApp.Strings.isEmpty(text)){
					selectedDataIndicator.text(text.replace('%d', selectedTotal));
					selectedDataIndicator.show();
				}
				listPanel.find('.bulk_action_area').show();
			}else{
				selectedDataIndicator.hide();
				listPanel.find('.bulk_action_area').hide();
			}
		});
		$(document).on('click', '.list_panel .bulk_action:not(.confirm_command)', function(e){
			e.preventDefault();
			var target = $(this);
			mskApp.theme.listManager.runBulkAction(target.attr('href'), target);
		});
		$(document).on('click', 'table tr.filter .search_btn', function(e){
			e.preventDefault();
			var target = $(this);
			var filterRow = target.closest('tr.filter');
			mskApp.theme.listManager.runAction('innerFilter', filterRow.closest('table').attr('data-search_url'), target, 'replace', filterRow.find('input, select').serialize());
		});
		$(document).on('submit', '.list_panel.ajax .specific_filter_form:not(.no_ajax)', function(e){
			e.preventDefault();
			var form = $(this);
			mskApp.theme.listManager.runAction('specificFilter', form.attr('action'), form, 'replace', form.serialize());
		});
		$(document).on('click', '.list_panel.ajax .sort_links a:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			mskApp.theme.listManager.runAction('sort', target.attr('href'), target, 'replace');
		});
		$(document).on('click', '.list_panel.ajax .search_reset_btn:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			mskApp.theme.listManager.runAction('searchReset', target.attr('href'), target, 'replace');
		});
		$(document).on('click', '.list_panel .item_action.confirm_command', function(e){
			e.preventDefault();
			var target = $(this);
			mskApp.theme.showConfirmDialog(target.attr('confirm_text'), function(){
				var listPanel = target.closest('.list_panel');
				if(listPanel.hasClass('ajax') && !target.hasClass('no_ajax')){
					mskApp.theme.listManager.runItemAction(target);
				}else{
					location.href = target.attr('href');
				}
			});
		});
		$(document).on('click', '.list_panel .bulk_action.confirm_command', function(e){
			e.preventDefault();
			var target = $(this);
			var totalSelected = target.closest('.list_panel').find('.checkable_checkbox:not(.check_all_switcher):checked').length;
			if(totalSelected > 0){
				mskApp.theme.showConfirmDialog(mskApp.parser.getString(target.attr('confirm_text'), '%d').replace('%d', totalSelected) , function(){
					mskApp.theme.listManager.runBulkAction(target.attr('href'), target);
				});
			}
		});
	},
	listManager : {
		runAction : function(type, url, target, defaultOpenMode, data, loader, contentViewer, alertViewer, redirectionRunner, success, options){
			var pageContent = target.closest('.list_panel');
			var isMainPageContent = mskApp.theme.isMainPageContent(pageContent);
			
			var openMode = mskApp.parser.getString(target.attr('data-open_mode'), defaultOpenMode);
			
			if(mskApp.Strings.isEmpty(openMode)){
				openMode = mskApp.theme.listManager.getDefaultOpenMode(target, pageContent, isMainPageContent);
			}
			if(isMainPageContent && (openMode == 'replace')){
				openMode = 'replace_main';
			}
			if(redirectionRunner == null){
				redirectionRunner = mskApp.theme.redirections.getAuto(pageContent, mskApp.theme.getMainPageContent(pageContent));
			}
			if(contentViewer == null){
				contentViewer = mskApp.theme.getContentViewerByOpenMode(openMode, true, pageContent, pageContent.parent(), false, false, true, '');
			}
			if(alertViewer == null){
				alertViewer = mskApp.theme.alertViewers.getDefault(mskApp.theme.getMainAlertZone(pageContent));
			}
			if(loader == null){
				loader = mskApp.theme.loaders.getModal();
			}
			mskApp.Tools.quickAjaxCall(url, data, openMode, loader, target, contentViewer, alertViewer, redirectionRunner, success, options);
		},
		runItemAction : function(target){
			mskApp.theme.listManager.runAction('item', target.attr('href'), target);
		},
		runMainAction : function(target){
			mskApp.theme.listManager.runAction('main', target.attr('href'), target);
		},
		runBulkAction : function(url, target){
			var data = target.closest('.list_panel').find('.checkable_checkbox:not(.check_all_switcher):checked').serialize();
			mskApp.theme.listManager.runAction('bulk', url, target, 'replace', data);
		},
		runPagination : function(url, target){
			mskApp.theme.listManager.runAction('pagination', url, target, 'replace');
		},
		runLimiter : function(url, target){
			mskApp.theme.listManager.runAction('limiter', url, target, 'replace');
		},
		getDefaultOpenMode : function(target, pageContent, isMainPageContent){
			var isInModal = (pageContent.closest('.dialog_wrapper').length > 0);
			var openMode = mskApp.parser.getString(pageContent.attr('data-action_open_mode'), 'modal');
			if((openMode == 'modal') && isInModal){
				openMode = 'replace';
			}
			/*var action = target.attr('data-action');
			var directActions = ['delete', 'activate', 'desactivate'];*/
			if(target.attr('data-direct')){
				openMode = 'replace';
			}
			return openMode;
		},
	},
	
	initFormPanel : function(){
		$(document).on('submit', '.form_panel.ajax form:not(.no_ajax)', function(e){
			e.preventDefault();
			var form = $(this);
			var url = form.attr('action');
			var data = form.serialize();
			var loader = mskApp.theme.loaders.getModal();
			var pageContent = form.closest('.form_panel');
			var openMode = mskApp.parser.getString(pageContent.attr('data-used_open_mode'), 'replace');
			var contentViewer = null;
			var alertViewer = null;
			if(openMode == 'modal'){
				contentViewer = mskApp.theme.contentViewers.getExistingModalReplace(pageContent);
				alertViewer = mskApp.theme.alertViewers.getToast();
			}else{
				contentViewer = mskApp.theme.contentViewers.getExistingReplace(pageContent, true);
				alertViewer = mskApp.theme.alertViewers.getDefault(mskApp.theme.getMainAlertZone(pageContent));
			}
			mskApp.Tools.quickAjaxCall(url, data, openMode, mskApp.theme.loaders.getModal(), form, contentViewer, alertViewer, mskApp.theme.redirections.getAuto(pageContent));
		});
		$(document).on('click', '.form_panel.ajax .btn_form_back:not(.no_ajax), .form_panel.ajax form .btn_cancel:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			var url = target.attr('href');
			mskApp.theme.runBackAction(url, target, target.closest('.form_panel'));
		});
	},
	initViewPanel : function(){
		$(document).on('click', '.view_panel.ajax .btn_view_back:not(.no_ajax)', function(e){
			e.preventDefault();
			var target = $(this);
			var url = target.attr('href');
			mskApp.theme.runBackAction(url, target, target.closest('.view_panel'));
		});
	},
	runBackAction : function(url, target, pageContent){
		var usedOpenMode = pageContent.attr('data-used_open_mode');
		var contentViewer = null;
		var alertViewer = null;
		var openMode = null;
		if(usedOpenMode == 'modal'){
			contentViewer = mskApp.theme.contentViewers.getModalCallerReplace(pageContent, true);
			openMode = contentViewer.finalOpenMode;
		}else{
			openMode = mskApp.parser.getString(usedOpenMode, 'replace');
			contentViewer = mskApp.theme.contentViewers.getExistingReplace(pageContent, true);
			alertViewer = mskApp.theme.getAlertZoneFromPageContent(pageContent);
		}
		var loader = mskApp.theme.loaders.getModal();
		mskApp.Tools.quickAjaxCall(url, null, openMode, loader, target, contentViewer, alertViewer);
	},
	isMainPageContent : function(pageContent){
		return pageContent.parent().hasClass('main_content');
	},
	getMainPageContent : function(pageContent){
		return pageContent.closest('.main_content');
	},
	getMainAlertZone : function(pageContent){
		return mskApp.theme.getMainPageContent(pageContent).prev('.alerts');
	},
	getAlertZoneFromPageContent : function(pageContent){
		return mskApp.theme.getMainPageContent(pageContent).prev('.alerts');
	},
	getContentViewerByOpenMode : function(openMode, withAnimation, existingContent, contentParent, modal, showClose, extraLarge, dialogClass){
		var contentViewer = null;
		if(openMode == 'modal'){
			contentViewer = mskApp.theme.contentViewers.getModal(existingContent, modal, showClose, extraLarge, dialogClass);
		}else if((openMode == 'replace') || (openMode == 'replace_main')){
			if(existingContent != null){
				contentViewer = mskApp.theme.contentViewers.getExistingReplace(existingContent, withAnimation);
			}else if(contentParent != null){
				contentViewer = mskApp.theme.contentViewers.getInnerReplace(contentParent, withAnimation);
			}
		}else if(openMode == 'in_nav'){
			contentViewer = mskApp.theme.contentViewers.getInnerReplace(contentParent, withAnimation);
		}
		return contentViewer;
	},
	getPageContent : function(content){
		return '<div class="pageContent"><div class="pageSuccess"></div><div class="pageErrors"></div>'+content+'</div>';
	},
	getSuccessHtml : function(message){
		return message;
	},
	getErrorsHtml : function(errors){
		return mskApp.Arrays.isArrayOrObject(errors) ? errors.join() : errors;
	},
	initRichEditor : function(target){
		tinymce.init({
			selector: target.attr('data-init-selector')+':not(.initialized)',
			menubar: false,
            toolbar: ['styleselect fontselect fontsizeselect',
                'undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify',
                'bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code'], 
            plugins : 'advlist autolink link image lists charmap print preview code'
		});
	},
	
	initListSortable : function(target){
		$(target.attr('data-init-selector')+':not(.initialized)').sortable({
			handle: ' .sortable_handle',
			cursor: 'move',
			stop: function( event, ui ) {
				var parentBlock = $(event.target);
				var items = parentBlock.sortable('toArray', {attribute : 'data-id'});
				TableManager.processSort(parentBlock, items);
			}
		});
	},
	
	initDatepicker : function(target){
		var arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
		var locale = target.attr('data-locale');
		var orientation = target.attr('data-orientation');
		var options = {
			templates: arrows
		};
		options['todayHighlight'] = mskApp.parser.getBool(target.attr('data-todayhighlight') ,true);
		options['clearBtn'] = mskApp.parser.getBool(target.attr('data-clearbtn') ,true);
		options['autoclose'] = mskApp.parser.getBool(target.attr('data-autoclose') ,true);
		options['format'] = mskApp.parser.getString(target.attr('data-format') ,'yyyy-mm-dd');
		if(mskApp.parser.getBool(target.attr('data-todaybtn') ,true)){
			options['todayBtn'] = 'linked';
		}
		
		if(!mskApp.Strings.isEmpty(orientation)){
			options['orientation'] = orientation;
		}
		if(!mskApp.Strings.isEmpty(locale)){
			options['language'] = moment.locale(locale);
		}
		target.datepicker(options);
	},
	
	initDatetimepicker : function(target){
		var arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
		var locale = target.attr('data-locale');
		var orientation = target.attr('data-orientation');
		var options = {
			templates: arrows
		};
		options['todayHighlight'] = mskApp.parser.getBool(target.attr('data-todayhighlight') ,true);
		options['clearBtn'] = mskApp.parser.getBool(target.attr('data-clearbtn') ,true);
		options['autoclose'] = mskApp.parser.getBool(target.attr('data-autoclose') ,true);
		options['format'] = mskApp.parser.getString(target.attr('data-format') ,'yyyy-mm-dd');
		if(mskApp.parser.getBool(target.attr('data-todaybtn') ,true)){
			options['todayBtn'] = 'linked';
		}
		
		if(!mskApp.Strings.isEmpty(orientation)){
			options['pickerPosition'] = orientation;
		}
		if(!mskApp.Strings.isEmpty(locale)){
			options['language'] = moment.locale(locale);
		}
		target.datetimepicker(options);
	},
	
	initSelect2 : function(target){
		var options = {
			allowClear: true
		};
		var placeholder = target.attr('data-placeholder');
		if(!mskApp.Strings.isEmpty(placeholder)){
			options['placeholder'] = placeholder;
		}
		target.select2(options);
	},
	
	changeCellPosition : function(target, position){
		var item = target.hasClass('value') ? target : target.find('.value');
		item.text(position);
	},
	
	getLoaderIcon : function(customClass){
		return '<i class="fa fa-spinner fa-spin'+mskApp.Strings.addPrefix(customClass, ' ')+'"></i>';
	},
	contentViewers : {
		getModal : function(callerPageContent, modal, showClose, extraLarge, dialogClass){
			function TmpModal(callerPageContent, dialogClass){
				var self=this;
				self.dialogClass = dialogClass;
				self.callerPageContent = callerPageContent;
				self.extraLarge = extraLarge;
				self.modal = modal;
				self.showClose = showClose;
				this.show = function(content){
					var uniqueId = null;
					var attributesStr = '';
					if(self.callerPageContent != null){
						uniqueId = 'id_'+mskApp.Tools.getUniqueId();
						self.callerPageContent.attr('data-unique_id', uniqueId);
						attributesStr = 'data-caller_unique_id="'+uniqueId+'"';
						/*var contentElement = $(content);
						contentElement.attr('data-caller_unique_id', uniqueId);
						content = contentElement.get(0).outerHTML;*/
					}
					var finalClass = 'dialog_wrapper tmp_dialog';
					if(self.extraLarge){
						finalClass = mskApp.Strings.addSuffix(finalClass, ' ')+'dialog_max_size';
					}
					finalClass += mskApp.Strings.addPrefix(self.dialogClass, ' ');
					mskApp.theme.showDialog(content, self.modal, self.showClose, finalClass, attributesStr);
				};
			};
			return new TmpModal(callerPageContent, modal, showClose, extraLarge, dialogClass);
		},
		getModalCallerReplace : function(currentPageContent, withAnimation){
			function TmpModalCallerReplace(currentPageContent, withAnimation){
				var self=this;
				self.finalOpenMode = 'replace';
				self.withAnimation = withAnimation;
				self.currentPageContent = currentPageContent;
				self.dialog = self.currentPageContent.closest('.dialog_wrapper');
				self.callerPageContentUniqueId = self.dialog.attr('data-caller_unique_id');
				self.callerPageContent = null;
				if(!mskApp.Strings.isEmpty(self.callerPageContentUniqueId)){
					self.callerPageContent = $('[data-unique_id='+self.callerPageContentUniqueId+']');
					self.finalOpenMode = mskApp.parser.getString(self.callerPageContent.attr('data-used_open_mode'), 'replace');
					if(mskApp.theme.isMainPageContent(self.callerPageContent) && (self.finalOpenMode == 'replace')){
						self.finalOpenMode = 'replace_main';
					}
				}
				
				this.show = function(content){
					mskApp.theme.closeDialog(self.dialog);
					if(self.callerPageContent != null){
						mskApp.theme.contentViewers.getExistingReplace(self.callerPageContent, self.withAnimation).show(content);
					}
				};
				this.getAlertViewer = function(){
					var result = null;
					if(self.callerPageContent != null){
						result = mskApp.theme.alertViewers.getDefault(mskApp.theme.getMainAlertZone(self.callerPageContent));
					}
					return result;
				};
			};
			return new TmpModalCallerReplace(currentPageContent, withAnimation);
		},
		getExistingModalReplace : function(currentPageContent){
			function TmpExistingModalReplace(currentPageContent){
				var self=this;
				self.currentPageContent = currentPageContent;
				self.dialog = self.currentPageContent.closest('.dialog_wrapper');
				self.callerPageContentUniqueId = self.dialog.attr('data-caller_unique_id');
				self.callerPageContent = null;
				if(!mskApp.Strings.isEmpty(self.callerPageContentUniqueId)){
					self.callerPageContent = $('[data-unique_id='+self.callerPageContentUniqueId+']');
				}
				this.show = function(content){
					mskApp.theme.closeDialog(self.dialog);
					if(self.callerPageContent != null){
						mskApp.theme.contentViewers.getModal(self.callerPageContent, false, false, true, '').show(content);
					}
				};
			};
			return new TmpExistingModalReplace(currentPageContent);
		},
		getExistingReplace : function(target, withAnimation){
			function TmpExistingReplace(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						self.target.fadeOut('50', function() {
							var contentElement = $(content);
							contentElement.hide();
							self.target.after(contentElement);
							contentElement.fadeIn('100', function() {self.target.remove();});
						});
					}else{
						self.target.after(content);
						self.target.remove();
					}
				};
			};
			return new TmpExistingReplace(target, withAnimation);
		},
		getInnerReplace : function(target, withAnimation){
			function TmpInnerReplace(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						self.target.fadeOut('50', function() {
							self.target.html(content).fadeIn('100');
						});
					}else{
						self.target.html(content);
					}
				};
			};
			return new TmpInnerReplace(target, withAnimation);
		},
		getAppend : function(target, withAnimation){
			function TmpAppend(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						var contentElement = $(content);
						contentElement.hide();
						self.target.append(contentElement);
						contentElement.fadeIn('100');
					}else{
						self.target.append(content);
					}
				};
			};
			return new TmpAppend(target, withAnimation);
		},
		getPrepend : function(target, withAnimation){
			function TmpPrepend(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						var contentElement = $(content);
						contentElement.hide();
						self.target.prepend(contentElement);
						contentElement.fadeIn('100');
					}else{
						self.target.prepend(content);
					}
				};
			};
			return new TmpPrepend(target, withAnimation);
		},
		getInsertAfter : function(target, withAnimation){
			function TmpInsertAfter(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						var contentElement = $(content);
						contentElement.hide();
						self.target.after(contentElement);
						contentElement.fadeIn('100');
					}else{
						self.target.after(content);
					}
				};
			};
			return new TmpInsertAfter(target, withAnimation);
		},
		getInsertBefore : function(target, withAnimation){
			function TmpInsertBefore(target, withAnimation){
				var self=this;
				self.target = target;
				self.withAnimation = withAnimation;
				this.show = function(content){
					if(self.withAnimation){
						var contentElement = $(content);
						contentElement.hide();
						self.target.before(contentElement);
						contentElement.fadeIn('100');
					}else{
						self.target.before(content);
					}
				};
			};
			return new TmpInsertBefore(target, withAnimation);
		},
	},
	loaders : {
		getIconAppend : function(target){
			function TmpIconAppend(target){
				var self=this;
				self.target = target;
				this.show = function(){
					self.target.append(mskApp.theme.getLoaderIcon('loader_icon'));
				};
				this.hide = function(){
					self.target.children('.loader_icon').remove();
				};
			};
			return new TmpIconAppend(target);
		},
		getIconPrepend : function(target){
			function TmpIconPrepend(target){
				var self=this;
				self.target = target;
				this.show = function(){
					self.target.prepend(mskApp.theme.getLoaderIcon('loader_icon'));
				};
				this.hide = function(){
					self.target.children('.loader_icon').remove();
				};
			};
			return new TmpIconPrepend(target);
		},
		getIconReplace : function(target){
			function TmpIconReplace(target){
				var self=this;
				self.target = target;
				self.icon = self.target.children('i:first-child');
				this.show = function(){
					if(self.icon.length == 0){
						self.target.prepend(mskApp.theme.getLoaderIcon('loader_icon'));
					}else{
						self.icon.hide();
						self.icon.after(mskApp.theme.getLoaderIcon('loader_icon'));
					}
				};
				this.hide = function(){
					self.target.children('.loader_icon').remove();
					self.icon.show();
				};
			};
			return new TmpIconReplace(target);
		},
		getModal : function(){
			function TmpModal(){
				var self=this;
				self.modal = null;
				this.show = function(){
					self.modal = mskApp.theme.showFullDialog(mskApp.theme.getLoaderIcon('loader_icon'), false, null, null, false, null, null, 'modal-dialog-centered modal-sm');
				};
				this.hide = function(){
					mskApp.theme.closeDialog(self.modal);
					if(self.modal != null){
						setTimeout(function(){mskApp.theme.closeDialog(self.modal);},500);
					}
				};
			};
			return new TmpModal();
		},
		getInnerReplace : function(target){
			function TmpInnerReplace(target){
				var self=this;
				self.target = target;
				this.show = function(){
					self.target.html(mskApp.theme.getLoaderIcon('loader_icon'));
				};
				this.hide = function(){
					self.target.html('');
				};
			};
			return new TmpInnerReplace(target);
		},
		getRedirection : function(parentLoader){
			function TmpRedirection(parentLoader){
				var self=this;
				self.parentLoader = parentLoader;
				this.show = function(){
					
				};
				this.hide = function(){
					if(self.parentLoader != null){
						self.parentLoader.hide();
					}
				};
			};
			return new TmpRedirection(parentLoader);
		},
	},
	alertViewers : {
		getDefault : function(alertZone){
			function TmpDefault(alertZone){
				var self=this;
				self.alertZone = alertZone;
				this.showAlerts = function(type, alerts){
					var html = '<div class="alert alert-'+type+'" role="alert"><div class="alert-text">';
					for(i in alerts){
						html += '<p>'+alerts[i]+'</p>';
					}
					html += '</div></div>';
					self.alertZone.append(html);
				};
				this.clearAlerts = function(){
					self.alertZone.html('');
				};
			};
			return new TmpDefault(alertZone);
		},
		getToast : function(alertZone){
			function TmpToast(alertZone){
				var self=this;
				self.alertZone = alertZone;
				this.showAlerts = function(type, alerts){
					var functionByType = {danger: 'error'};
					var functionName = (typeof(functionByType[type]) !== 'undefined') ? functionByType[type] : type;
					if(typeof(toastr[functionName]) === 'function'){
						for(i in alerts){
							toastr[functionName](alerts[i]);
						}
					}
				};
				this.clearAlerts = function(){
					toastr.clear();
				};
			};
			return new TmpToast(alertZone);
		},
		getNativeAlert : function(){
			function TmpNativeAlert(){
				this.showAlerts = function(type, alerts){
					alert(alerts.join(', '));
				};
				this.clearAlerts = function(){
					
				};
			};
			return new TmpNativeAlert();
		},
	},
	redirections : {
		getAuto : function(currentPageContent, mainPageContent){
			function TmpAuto(currentPageContent, mainPageContent){
				var self = this;
				self.currentPageContent = currentPageContent;
				self.mainPageContent = mainPageContent;
				if(self.mainPageContent == null){
					self.mainPageContent = mskApp.theme.getMainPageContent(self.currentPageContent);
				}
				this.run = function(jsonData, parentCaller){
					var currentPageContent = self.currentPageContent;
					var finalOpenMode = null;
					var finalContentViewer = null;
					var finalAlertViewer = null;
					
					var redirectionType = (typeof(jsonData.redirectionType) !== 'undefined') ? jsonData.redirectionType : null;
					if(redirectionType == 'default_action'){
						var usedOpenMode = self.currentPageContent.attr('data-used_open_mode');
						if(usedOpenMode == 'modal'){
							finalContentViewer = mskApp.theme.contentViewers.getModalCallerReplace(self.currentPageContent, true);
							finalOpenMode = finalContentViewer.finalOpenMode;
							finalAlertViewer = finalContentViewer.getAlertViewer();
						}else{
							finalOpenMode = mskApp.parser.getString(self.currentPageContent.attr('data-open_mode'), 'replace');
							finalContentViewer = mskApp.theme.contentViewers.getExistingReplace(self.currentPageContent, true);
							finalAlertViewer = parentCaller.alertViewer;
						}
					}else if((redirectionType == 'same_action') || (redirectionType == 'inner_action')){
						var usedOpenMode = self.currentPageContent.attr('data-used_open_mode');
						if(usedOpenMode == 'modal'){
							finalContentViewer = mskApp.theme.contentViewers.getExistingModalReplace(self.currentPageContent);
							finalOpenMode = 'modal';
						}else{
							finalOpenMode = mskApp.parser.getString(self.currentPageContent.attr('data-open_mode'), 'replace');
							finalContentViewer = mskApp.theme.contentViewers.getExistingReplace(self.currentPageContent, true);
							finalAlertViewer = parentCaller.alertViewer;
						}
					}else if((redirectionType == 'other_controller') || (redirectionType == 'back_to_caller')){
						finalOpenMode = 'replace_main';
						finalContentViewer = mskApp.theme.contentViewers.getExistingReplace(self.mainPageContent, true);
					}else if(redirectionType == 'login'){
						location.href = jsonData.redirectionUrl;
					}else{
						location.href = jsonData.redirectionUrl;
					}
					if(mskApp.theme.isMainPageContent(self.currentPageContent) && (finalOpenMode == 'replace')){
						finalOpenMode = 'replace_main';
					}
					if(finalAlertViewer === null){
						if(finalOpenMode === 'modal'){
							finalAlertViewer = mskApp.theme.alertViewers.getToast();
						}else{
							finalAlertViewer = mskApp.theme.alertViewers.getDefault(mskApp.theme.getMainAlertZone(self.mainPageContent));
						}
					}
					mskApp.Tools.quickAjaxCall(jsonData.redirectionUrl, null, finalOpenMode, mskApp.theme.loaders.getRedirection(parentCaller.loader), parentCaller.target, finalContentViewer, finalAlertViewer);
				};
			};
			return new TmpAuto(currentPageContent, mainPageContent);
		},
		getDefault : function(openMode, contentViewer, alertViewer, redirectionRunner, success, options){
			function TmpDefault(openMode, contentViewer, alertViewer, redirectionRunner, success, options){
				var self = this;
				self.openMode = openMode;
				self.contentViewer = contentViewer;
				self.alertViewer = alertViewer;
				self.redirectionRunner = redirectionRunner;
				self.success = success;
				self.options = options;
				this.run = function(jsonData, parentCaller){
					var finalContentViewer = (self.contentViewer == null) ? parentCaller.contentViewer : self.contentViewer;
					var finalAlertViewer = (self.alertViewer == null) ? parentCaller.alertViewer : self.alertViewer;
					var finalOpenMode = (self.openMode == null) ? parentCaller.openMode : self.openMode;
					mskApp.Tools.quickAjaxCall(jsonData.redirectionUrl, null, finalOpenMode, mskApp.theme.loaders.getRedirection(parentCaller.loader), parentCaller.target, finalContentViewer, finalAlertViewer, self.redirectionRunner, self.success, self.options);
				};
			};
			return new TmpDefault(openMode, contentViewer, alertViewer, redirectionRunner, success, options);
		},
		getReload : function(){
			function TmpReload(){
				this.run = function(jsonData, parentCaller){
					location.href = jsonData.redirectionUrl;
				};
			};
			return new TmpReload();
		},
	},
	
	handleGlobalEvent : function(){
		$(document).on('click', '.show_hide', function(e){
			e.preventDefault();
			$($(this).attr('target_to_hide')).hide();
			$($(this).attr('target_to_show')).show();
		});
		$(document).on('change', 'table .check_all_switcher', function(e){
			var target = $(this);
			var table = target.closest('table');
			table.find('>tbody>tr>td.cell_checkable .checkable_checkbox').prop('checked', target.is(':checked')).trigger('change');
		});
		$(document).on('click', '.selectable_table td:not(.cell_checkable), .selectable_table th:not(.cell_checkable)', function(e){
			var checkBox = $(this).parent().find('>.cell_checkable .checkable_checkbox');
			checkBox.prop('checked', !checkBox.is(':checked'));
			checkBox.trigger('change');
		});
		
		$(document).on('click', '.table_search_btn', function(e){
			var form = $(this).closest("form");
			form.find("input[name='action']:first").val("");
		});
		
		$(document).on('click', '.table_checkbox_multiple tbody tr', function(e){
			var checkItem = $(this).find('.check_all_item:first');
			checkItem.prop('checked', !checkItem.is(':checked'));
		});
		$(document).on('click', '.mirror_link', function(e){
			e.preventDefault();
			$($(this).attr('data-target')).trigger('click');
		});
		$(document).on('click', '.other_submit_btn', function(e){
			e.preventDefault();
			var target = $(this);
			var form = target.closest('form');
			form.find('input[name="submitType"]').val(target.attr('data-name'));
			form.submit();
		});
		$(document).on('focusin', function(e) {
			if ($(e.target).closest('.mce-window').length) {
				e.stopImmediatePropagation();
			}
		});
	},

	showConfirmDialog : function(confirmText, callback){
		swal.fire({
			buttonsStyling: false,
			text: confirmText,
			type: 'error',
			confirmButtonText: mskApp.l(['framework', 'common', 'theme'], 'Yes, delete!'),
			confirmButtonClass: 'btn btn-sm btn-bold btn-danger',
			showCancelButton: true,
			cancelButtonText: mskApp.l(['framework', 'common', 'theme'], 'No, cancel'),
			cancelButtonClass: 'btn btn-sm btn-bold btn-brand'
		}).then(function(result) {
			if (result.value) {
				callback();
			}
		});
	},
};

$(document).ready(function() {
	mskApp.theme.init();
	mskApp.ContentInitializer.runAll();
});