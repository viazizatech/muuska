var mskApp = mskApp || {};
mskApp.FileUpload = {
	DEFAULT_MAX_BYTE_PER_UPLOAD : 1.5 * 1024 * 1024,
	DEFAULT_MAX_FILE_SIZE : 100000000,
	init : function(){
		/*$(document).on("click", ".upload_parent_block .btn_remove", function(e) {
			e.preventDefault();
			FileUpload.deleteFile($(this));
		});*/
		mskApp.FileUpload.maxBytePerUpload = mskApp.FileUpload.DEFAULT_MAX_BYTE_PER_UPLOAD;
		mskApp.FileUpload.maxFileSize = mskApp.FileUpload.DEFAULT_MAX_FILE_SIZE;
		/*FileUpload.initTheme();
		FileUpload.initCore();*/
	},
	createUploadContainer : function(parentBlock) {
		return new mskApp.FileUpload.UploadContainer(parentBlock);
	},
	UploadContainer : function(parentBlock) {
		var self = this;
		self.parentBlock = parentBlock;
		self.multiple = (self.parentBlock.attr('data-multiple') == '1');
		self.detailsSavingEnabled = (self.parentBlock.attr('data-details_saving_enabled') == '1');
		self.maxBytePerUpload = mskApp.FileUpload.maxBytePerUpload;
		self.uploadUrl = self.parentBlock.attr('data-url');
		
		self.previewZone = self.parentBlock.find('.preview_zone');
		this.getUploadUrl = function() {
			return self.uploadUrl;
		};
		this.getDeleteUrl = function() {
			return self.parentBlock.attr('data-delete_url');
		};
		this.showErrors = function(errors) {
			errors = mskApp.Arrays.isArrayOrObject(errors) ? errors : [errors];
			self.parentBlock.addClass('has-error');
			var block = self.parentBlock.find('.field_error_block');
			block.show();
			block.html(errors.join(', '));
		};
		this.clearErrors = function() {
			self.parentBlock.removeClass('has-error');
			var block = parentBlock.find('.field_error_block');
			block.hide();
			block.html('');
		};
		this.getPreviewTemplate = function() {
			var previewTemplate = self.parentBlock.find('.preview_template');
			var previewTemplateHtml = previewTemplate.html();
			return previewTemplateHtml;
		};
		this.getPreviewNewContent = function(jsonData) {
			var result = '';
			if(typeof(jsonData.extra.previewContent) !== 'undefined'){
				result = jsonData.extra.previewContent;
			}else{
				var previewTemplateHtml = self.getPreviewTemplate();
				if(!mskApp.Strings.isEmpty(previewTemplateHtml)){
					result = mskApp.Strings.replaceAll(previewTemplateHtml, 'data-name', 'name');
					result = mskApp.Strings.replaceAll(result, '[fileValue]', jsonData.extra.fileValue);
					result = mskApp.Strings.replaceAll(result, '[filePreviewContent]', jsonData.extra.filePreviewContent);
				}
			}
			return result;
		};
		this.onUploadSuccessfullyCompleted = function(processTheme, jsonData) {
			var previewNewContent = self.getPreviewNewContent(jsonData);
			if(processTheme.existingItem != null){
				processTheme.existingItem.remove();
			}
			if(!self.multiple){
				self.previewZone.html(previewNewContent);
			}else{
				processTheme.quickPreview.after(previewNewContent);
			}
			/*processTheme.quickPreview.after(previewNewContent);*/
			self.onPreviewContentChange();
		};
		this.onAjaxError = function() {
			self.showErrors([mskApp.Tools.DEFAULT_ERROR_TEXT]);
		};
		this.onPreviewContentChange = function() {
			if(self.previewZone.children().length > 0){
				self.parentBlock.addClass('has_preview');
			}else{
				self.parentBlock.removeClass('has_preview');
			}
		};
		this.startUpload = function(file, processTheme) {
			if(processTheme.validateFile(file)){
				var processManager = new mskApp.FileUpload.ProcessManager(processTheme, file);
				processManager.run(true);
			}else{
				processTheme.quickPreview.remove();
				self.onPreviewContentChange();
			}
		};
		this.editUpload = function(existingItem) {
			self.createUploadInput(existingItem);
		};
		this.editUploadFromAction = function(target) {
			var existingItem = target.closest('.preview_item');
			self.editUpload(existingItem);
		};
		this.selectFiles = function() {
			self.createUploadInput(null);
		};
		this.createUploadInput = function(existingItem, useCustomMultiple, multiple) {
			var input = $('<input/>').attr('type', 'file');
			if(multiple){
				input.attr('multiple', 'multiple');
			}
			var accept = self.parentBlock.attr('data-accept');
			if(accept){
				input.attr('accept', accept);
			}
			input.trigger('click');
			
			input.on('change', function (e) {
				self.onFileChange(this.files, existingItem, useCustomMultiple, multiple);
			});
			return input;
		};
		this.onFileChange = function(files, existingItem, useCustomMultiple, multiple){
			multiple = useCustomMultiple ? multiple : self.multiple;
			if ((files.length > 0) && (files[0] != null)){
				for(var i = 0; i < files.length; i++){
					if((existingItem == null) && !self.multiple){
						existingItem = self.previewZone.find('.preview_item');
						if(existingItem.length == 0){
							existingItem = null;
						}
					}
					var processTheme = self.createProcessTheme(self.getQuickPreview(files[i], existingItem), existingItem);
					self.startUpload(files[i], processTheme);
					if(!multiple){
						break;
					}
				}
			}
		};
		this.getQuickPreview = function(file, existingItem){
			var previewModelItemHtml = self.getPreviewTemplate();
			var quickPreview = $(previewModelItemHtml);
			if(existingItem != null){
				existingItem.hide();
				quickPreview.insertAfter(existingItem);
			}else{
				self.previewZone.append(quickPreview);
			}
			quickPreview.addClass('quick_preview');
			quickPreview.append(self.getProgressContent());
			self.readFilePreview(file, function(filePreviewContent){
				quickPreview.find('.file_preview').html(filePreviewContent);
			});
			
			self.onPreviewContentChange();
			return quickPreview;
		};
		this.readFilePreview = function(file, callback){
			var imageType = /image.*/;     
            if (file.type.match(imageType)) {
                var reader = new FileReader();
				reader.onload = (function(aImg) {
					return function(e) { 
						callback('<img src="'+event.target.result+'" title="'+file.name+'" />');
					};
				})(callback);
				reader.readAsDataURL(file);
            }else{
				callback(self.getDefaultFilePreviewContent(file));
			}
		};
		this.getDefaultFilePreviewContent = function(file){
			return '<i class="fa fa-file"></i>';
		};
		this.getProgressContent = function(){
			var htmlContent = '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
			return htmlContent;
		};
		this.createProcessTheme = function(quickPreview, existingItem){
			var processTheme = new mskApp.FileUpload.ProcessTheme(self, quickPreview, existingItem);
			return processTheme;
		};
		
		this.showActionLoader = function(target){
			target.find('i').hide();
			target.append('<i class="fa fa-spinner fa-spin icon_loader"><i>');
		};
		this.hideActionLoader = function(target){
			target.find('.icon_loader').remove();
			target.find('i').show();
		};
		this.deleteUpload = function(target) {
			var deleteUrl = self.getDeleteUrl();
			var previewItem = target.closest('.preview_item');
			self.showActionLoader(target);
			if(previewItem.hasClass('saved') || mskApp.Strings.isEmpty(deleteUrl)){
				previewItem.remove();
				self.onPreviewContentChange();
			}else{
				var fileValueField = previewItem.find('.file_value_input');
				mskApp.Tools.ajaxCall({
					url: deleteUrl,
					data: {
						ajax : true,
						unusedFileName : fileValueField.val()
					},
					success: function (jsonData) {
						self.hideActionLoader(target);
						if (!jsonData.hasErrors)
						{
							previewItem.remove();
							self.onPreviewContentChange();
						}else{
							self.showErrors(jsonData.alerts.danger);
						}
					},
					error: function () {
						self.hideActionLoader(target);
						self.onAjaxError();
					},
					type: 'post',
				}, target);
			}
		};
		this.validateFile = function(processTheme, file) {
			var allowedExtensions = self.parentBlock.attr('data-allowed_extensions');
			var excludedExtensions = self.parentBlock.attr('data-excluded_extensions');
			result = true;
			var errors = [];
			if(file == null){
				errors.push(mskApp.l(['framework', 'common', 'FileUpload'], 'No file selected'));
			}else{
				var extension = mskApp.Tools.getFileExtension(file.name);
				if(!mskApp.Strings.isEmpty(excludedExtensions)){
					excludedExtensions = mskApp.Strings.replaceAll(excludedExtensions, '.', '');
					var excludedExtensionsArray = excludedExtensions.split(',');
				
					if(excludedExtensionsArray.indexOf(extension) != -1){
						errors.push(mskApp.l(['framework', 'common', 'FileUpload'], 'This file extension is not allowed.'));
					}
				}
				if(!mskApp.Strings.isEmpty(allowedExtensions)){
					allowedExtensions = mskApp.Strings.replaceAll(allowedExtensions, '.', '');
					var allowedExtensionsArray = allowedExtensions.split(',');
					if(allowedExtensionsArray.indexOf(extension) == -1){
						errors.push(mskApp.l(['framework', 'common', 'FileUpload'], 'This file extension is not allowed. The supported one are:') + ' ' + mskApp.Strings.replaceAll(allowedExtensions, ',', ', '));
					}
				}
			}
			if(errors.length > 0){
				result = false;
				processTheme.showErrors(errors);
			}
			return result;
		};
		this.formatFormData = function(processTheme, formData) {
			if((processTheme.existingItem != null) && !processTheme.existingItem.hasClass('saved')){
				var fileValueField = processTheme.existingItem.find('.file_value_input');
				if(fileValueField.length > 0){
					formData.append('unusedFileName', fileValueField.val());
				}
			}
			if(self.detailsSavingEnabled){
				formData.append('detailsSavingEnabled', 1);
			}
			return formData;
		};
	},
	ProcessTheme : function(container, quickPreview, existingItem) {
		var self = this;
		self.container = container;
		self.quickPreview = quickPreview;
		self.existingItem = existingItem;
		self.progressBlock = self.quickPreview.find('.progress');
		self.progressBar = self.progressBlock.find('.progress-bar');
		this.getMaxBytePerUpload = function() {
			return self.container.maxBytePerUpload;
		};
		this.getUploadUrl = function() {
			return self.container.getUploadUrl();
		};
		this.onBeforeStart = function() {
			self.progressBlock.show();
			self.onProgressChange(0);
		};
		this.onProgressChange = function(percentage) {
			self.progressBar.css('width', percentage + '%');
			self.progressBar.html(percentage + '%');
		};
		this.validateFile = function(file) {
			return self.container.validateFile(self, file);
		};
		this.showErrors = function(errors) {
			self.container.showErrors(errors);
		};
		this.clearErrors = function(processTheme) {
			self.container.clearErrors();
		};
		this.getPreviewTemplate = function() {
			return self.container.getPreviewTemplate();
		};
		this.getPreviewNewContent = function(jsonData) {
			return self.container.getPreviewNewContent(jsonData);
		};
		this.onUploadSuccessfullyCompleted = function(jsonData) {
			/*var previewNewContent = self.getPreviewNewContent(jsonData);
			if(self.existingItem != null){
				self.existingItem.after(previewNewContent);
				self.existingItem.remove();
			}else{
				if(!self.multiple){
					self.previewZone.html('');
				}
				self.previewZone.append(previewNewContent);
			}
			self.onPreviewContentChange();*/
			self.container.onUploadSuccessfullyCompleted(self, jsonData);
		};
		this.onUploadCompleted = function(jsonData) {
			/*self.progressBlock.hide();*/
			if (!jsonData.hasErrors)
			{
				self.clearErrors();
				self.onUploadSuccessfullyCompleted(jsonData);
			}else{
				self.onProcessError();
				self.showErrors(jsonData.alerts.danger);
			}
			self.quickPreview.remove();
		};
		this.formatFormData = function(formData) {
			return self.container.formatFormData(self, formData);
		};
		this.onProcessError = function() {
			if(self.existingItem != null){
				self.existingItem.show();
			}
			self.quickPreview.remove();
			self.container.onPreviewContentChange();
		};
		this.onAjaxError = function() {
			self.container.onAjaxError();
			self.onProcessError();
		};
		/*this.onPreviewContentChange = function() {
			self.container.onPreviewContentChange();
		};*/
	},
	ProcessManager : function(processTheme, file) {
		
		var self = this;
		self.processTheme = processTheme;
		self.file = file;
		self.loadedBytes = 0;
		self.currentIndex = 0;
		self.nextIndex = 0;
		self.hasMore = true;
		self.maxByteToLoad = self.processTheme.getMaxBytePerUpload();
		self.mainFileName = '';
		self.mainFileName = '';
		
		self.processTheme.onBeforeStart();
		this.createNewFile = function(){
			self.maxByteToLoad = ((self.currentIndex + self.maxByteToLoad) > self.file.size) ? (self.file.size - self.currentIndex) : self.maxByteToLoad;
			var blob = file.slice(self.currentIndex, self.currentIndex + self.maxByteToLoad);
			return new File([blob], self.file.name, {lastModifiedDate:self.file.lastModifiedDate, lastModifiedDate:self.file.lastModifiedDate, type:self.file.type});
		};
		this.run = function(isMainProcess){
			var file = (self.file.size > self.maxByteToLoad) ? self.createNewFile() : self.file;
			var hasMore = ((self.currentIndex + self.maxByteToLoad) < self.file.size);
			var formData = new FormData();
			formData.append('ajax', true);
			/*formData.append('action', 'UploadFile');*/
			formData.append('file', file);
			formData.append('hasMoreSubFiles', (hasMore ? 1 : 0));
			if(!isMainProcess){
				formData.append('mainFileName', self.mainFileName);
				formData.append('isSubFile', true);
			}
			formData = self.processTheme.formatFormData(formData);
			mskApp.Tools.ajaxCall({
				url: self.processTheme.getUploadUrl(),
				data: formData,
				success: function (jsonData) {
					if(hasMore && !jsonData.hasErrors){
						self.mainFileName = jsonData.extra.mainFileName;
						self.currentIndex += self.maxByteToLoad;
						self.loadedBytes += self.maxByteToLoad;
						self.run(false);
					}else{
						self.processTheme.onUploadCompleted(jsonData);
					}
				},
				error: function () {
					self.processTheme.onAjaxError();
				},
				cache: false,
				dataType: 'json',
				contentType: false,
				processData: false,
				type: 'post',
				xhr: function () {
					myXhr = $.ajaxSettings.xhr();
					if (myXhr.upload) {
						myXhr.upload.addEventListener('progress', function (evt) {
							if (evt.lengthComputable) {
								var percentage = (evt.loaded / evt.total);
								percentage = parseInt(percentage * 100);
								var progressPerStep = (self.loadedBytes + self.maxByteToLoad) / self.file.size;
								progressPerStep = (progressPerStep > 1) ? 1 : progressPerStep;
								percentage = percentage * progressPerStep;
								percentage = Math.floor((percentage > 100) ? 100 : percentage);
								//percentage = parseFloat((percentage > 100) ? 100 : percentage).toFixed(2);
								self.processTheme.onProgressChange(percentage);
							}
						}, false);
					} else {
						console.log('Uploadress is not supported.');
					}
					return myXhr;
				}
			}, null);
		};
		
	},
};
mskApp.FileUpload.init();