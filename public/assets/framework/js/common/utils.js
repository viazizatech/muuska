var mskApp = mskApp || {};
mskApp.parser = {
	getString : function(value, defaultValue){
		var result = '';
		if((typeof(value) !== 'undefined') && (value != null) && (value != '')){
			result = value;
		}else if(defaultValue !== null){
			result = defaultValue;
		}
		return result;
	},
	getInt : function(value, defaultValue){
		var result = 0;
		if((typeof(value) !== 'undefined') && (value != null) && (value != '') && !isNaN(value)){
			result = parseInt(value);
		}else if((defaultValue !== null) && !isNaN(defaultValue)){
			result = parseInt(defaultValue);
		}
		return result;
	},
	getFloat : function(value, defaultValue){
		var result = 0;
		if((typeof(value) !== 'undefined') && (value != null) && (value != '') && !isNaN(value)){
			result = parseFloat(value);
		}else if((defaultValue !== null) && !isNaN(defaultValue)){
			result = parseFloat(defaultValue);
		}
		return result;
	},
	getBool : function(value, defaultValue){
		var result = false;
		if((typeof(value) !== 'undefined') && (value !== null) && (value !== '')){
			result = ((value === '0') || (value === 0) || (value === false) || (value === 'false')) ? false : true;
		}/*else if((defaultValue !== null) && (typeof(defaultValue) !== 'undefined') && (defaultValue !== null) && (defaultValue !== '')){
			result = ((defaultValue === '0') || (defaultValue === 0) || (defaultValue === false) || (defaultValue === 'false')) ? false : true;
		}*/else if(defaultValue !== null){
			result = mskApp.parser.getBool(defaultValue);
		}
		return result;
	},
	getArray : function(value, defaultValue){
		var result = [];
		if(Array.isArray(value)){
			result = value;
		}else if((defaultValue !== null) && Array.isArray(defaultValue)){
			result = defaultValue;
		}
		return result;
	},
	getArrayOrObject : function(value, defaultValue){
		var result = {};
		if((typeof(value) === 'object') && (value !== null)){
			result = value;
		}else if((defaultValue !== null) && (typeof(defaultValue) === 'object')){
			result = defaultValue;
		}
		return result;
	},
	getObject : function(value, defaultValue){
		var result = {};
		if((typeof(value) === 'object') && (value !== null) && !Array.isArray(value)){
			result = value;
		}else if((defaultValue !== null) && (typeof(defaultValue) === 'object') && !Array.isArray(defaultValue)){
			result = defaultValue;
		}
		return result;
	},
};
if(typeof(mskApp.vars) === 'undefined'){mskApp.vars = {};}
if(typeof(mskApp.allVars) === 'undefined'){mskApp.allVars = {};}
mskApp.vars.exist = function(name, defaultValue){
	return (typeof(mskApp.allVars[name]) !== 'undefined') ? true : false;
};
mskApp.vars.get = function(name, defaultValue){
	return (typeof(mskApp.allVars[name]) !== 'undefined') ? mskApp.allVars[name] : defaultValue;
};
mskApp.vars.set = function(name, value){
	mskApp.allVars[name] = value;
};
mskApp.vars.getInt = function(name, defaultValue){
	return mskApp.parser.getInt(mskApp.vars.get(name, defaultValue));
};
mskApp.vars.getFloat = function(name, defaultValue){
	return mskApp.parser.getFloat(mskApp.vars.get(name, defaultValue));
};
mskApp.vars.getBool = function(name, defaultValue){
	return mskApp.parser.getBool(mskApp.vars.get(name, defaultValue));
};
mskApp.vars.getArray = function(name, defaultValue){
	return mskApp.parser.getArray(mskApp.vars.get(name, defaultValue));
};
mskApp.vars.getObject = function(name, defaultValue){
	return mskApp.parser.getObject(mskApp.vars.get(name, defaultValue));
};
mskApp.vars.getArrayOrObject = function(name, defaultValue){
	return mskApp.parser.getArrayOrObject(mskApp.vars.get(name, defaultValue));
};
mskApp.l = function(scopes, text, context){
	var result = text;
	var translations = (typeof(mskApp.translations) === 'object') ? mskApp.translations : {};
	for(i in scopes){
		translations = mskApp.getTranslations(translations, scopes[i]);
	}
	var resultType = typeof(translations[text]);
	if(resultType !== 'undefined'){
		if(resultType === 'object'){
			if(mskApp.Strings.isEmpty(context) || (typeof(translations[text][context]) === 'undefined')){
				result = (typeof(translations[text]['']) !== 'undefined') ? translations[text][''] : result;
			}else{
				result = translations[text][context];
			}
		}else{
			result = translations[text];
		}
	}
	return result;
};
mskApp.getTranslations = function(data, currentScope){
	return (typeof(data[currentScope]) !== 'undefined') ? data[currentScope] : {};
};
mskApp.Tools = {
	DEFAULT_ERROR_TEXT : 'An error occurred while connecting to server',
	ajaxCall : function(options, target){
		defaultOptions = {cache: false, dataType: 'json',type: 'post'};
		options = mskApp.Arrays.merge(defaultOptions, options);
		var loadMedia = true;
		if(typeof(options.data)==="undefined"){
			options.data={};
		}
		if(typeof(options.data)==="object"){
			if(options.data == null){
				options.data = {};
			}
			options.data.ajax = "1";
		}else if (typeof(options.data)==="string"){
			options.data +=((options.data=="") ? "" :"&")+"ajax=1";
		}
		var paramSuccess = (typeof(options.success)==="function") ? options.success : null;
		options.success = function(data, textStatus, jqXHR){
			if(loadMedia && (typeof(data)==="object") && (typeof(data.medias)==="object")){
				if(typeof(data.medias.jsFiles)==="object"){
					for(url in data.medias.jsFiles){
						mskApp.Tools.loadJs(url);
					}
				}
				if(typeof(data.medias.cssFiles)==="object"){
					for(url in data.medias.cssFiles){
						mskApp.Tools.loadCss(url);
					}
				}
			}
			if(paramSuccess!=null){
				paramSuccess(data, textStatus, jqXHR);
				mskApp.ContentInitializer.afterPageChanged();
			}
		};
		$.ajax(options);
	},
	quickAjaxCall : function(url, data, openMode, loader, target, contentViewer, alertViewer, redirectionRunner, success, options){
		if(options == null){
			options = {};
		}
		var paramSuccess = (typeof(success)==='function') ? success : null;
		var paramError = (typeof(options.error)==='function') ? options.error : null;
		options.success = function(jsonData, textStatus, jqXHR){
			if((typeof(jsonData.hasRedirection) !== 'undefined') && jsonData.hasRedirection){
				var currentOptions = {url : url, data : data, openMode : openMode, loader : loader, target : target, contentViewer : contentViewer, alertViewer : alertViewer, redirectionRunner : redirectionRunner, options : options};
				if(redirectionRunner != null){
					redirectionRunner.run(jsonData, currentOptions);
				}
			}else{
				if(loader != null){
					loader.hide();
				}
				if((typeof(jsonData.alerts) !== 'undefined') && (alertViewer != null)){
					alertViewer.clearAlerts();
					for(alertType in jsonData.alerts){
						alertViewer.showAlerts(alertType, jsonData.alerts[alertType]);
					}
				}
				if((typeof(jsonData.content) !== 'undefined') && (contentViewer != null)){
					contentViewer.show(jsonData.content);
				}
				if(paramSuccess != null){
					paramSuccess(jsonData, textStatus, jqXHR);
				}
			}
		};
		options.error = function(XMLHttpRequest, textStatus, errorThrown){
			if(loader != null){
				loader.hide();
			}
			if(alertViewer != null){
				alertViewer.clearAlerts();
				alertViewer.showAlerts('danger', ['Error']);
			}
			if(paramError != null){
				paramError(XMLHttpRequest, textStatus, errorThrown);
			}
		};
		var urlData = {};
		var finalUrl = url;
		if(openMode){
			urlData['actionOpenMode'] = openMode;
			finalUrl = mskApp.Strings.appendDataToUrl(finalUrl, urlData);
		}
		options.url = finalUrl;
		options.data = data;
		if(loader != null){
			loader.show();
		}
		mskApp.Tools.ajaxCall(options, target);
	},
	/*ajaxCallQuick : function(target, showModalLoader, successCallback, errorCallback){
		defaultOptions = {cache: false, dataType: 'json',type: 'post'};
		options = ArrayTools.merge(defaultOptions, options);
	},*/
	loadCss : function(url, params){
		if(!Tools.isMediaLoaded(url, true)){
			var content = '<link rel="stylesheet" type="text/css" href="'+url+'"'+
			(((typeof(params)==="object") && (typeof(params.media)!=="undefined")) ? ' media="'+params.media+'"' : '')+'/>';
			$("head").append(content);
			Tools.setMediaLoaded(url, true);
		}
	},
	loadJs : function(url, params){
		if(!Tools.isMediaLoaded(url, false)){
			var content = '<script type="text/javascript" src="'+url+'"'+
				(((typeof(params)==="object") && (typeof(params.async)!=="undefined")) ? ' async' : ' async')+'/></script>';
			$("body").append(content);
			Tools.setMediaLoaded(url, false);
		}
	},
	loadMedia : function(url, params, isCss){
		var options = {url: url, cache: true, dataType: "script"};
		if((typeof(params)==="object") && (typeof(params.async)!=="undefined")){
			options.async=params.async;
		}
		options.success = function(data){
			
		}
		$.ajax(options);
	},
	setMediaLoaded : function(url, isCss){
		var key = isCss ? "cssFiles" : "jsFiles";
		usedMedias[key].push(url);
	},
	isMediaLoaded : function(url, isCss){
		var key = isCss ? "cssFiles" : "jsFiles";
		return (usedMedias[key].indexOf(url)!=-1);
	},
	
	getFileExtension : function(name){
		var extension = name.split('.').pop();
		return (extension == name) ? "" : extension;
	},
	getUniqueId : function(){
		return Math.floor(new Date().valueOf() * Math.random());
	},
};

mskApp.Strings = {
	isEmpty : function(value){
		return ((typeof(value) === 'undefined') || (value == null) || (value == ''));
	},
	replaceAll : function(str, search, replacement){
		return str.split(search).join(replacement);
	},
	appendStringToUrl : function(url, string){
		var result = url;
		if(string != ''){
			var concat = (url.indexOf('?') != -1) ? '&' : '?';
			result = url+concat+string;
		}
		return result;
	},
	appendDataToUrl : function(url, data){
		return mskApp.Strings.appendStringToUrl(url, mskApp.Strings.getStringQueryFromData(data));
	},
	getStringQueryFromData : function(data){
		return jQuery.param(data);
	},
	addPrefix : function(value, prefix){
		result = mskApp.parser.getString(value, '');
		if(result != ''){
			result = prefix + result;
		}
		return result;
	},
	addSuffix : function(value, suffix){
		result = mskApp.parser.getString(value, '');
		if(result != ''){
			result = result + suffix;
		}
		return result;
	},
	addPrefixAndSuffix : function(value, prefix, suffix){
		result = mskApp.parser.getString(value, '');
		if(result != ''){
			result = prefix + result + suffix;
		}
		return result;
	},
};

mskApp.Arrays = {
	merge : function(array1, array2){
		return $.extend({}, array1, array2);
	},
	isArrayOrObject : function(value){
		return (typeof(value)==="object");
	},
};

mskApp.ContentInitializer = {
	list : {},
	add : function(name, callback){
		mskApp.ContentInitializer.list[name] = callback;
	},
	exist : function(name){
		return (typeof(mskApp.ContentInitializer.list[name]) === "function");
	},
	get : function(name){
		return mskApp.ContentInitializer.exist(name) ? mskApp.ContentInitializer.list[name] : null;
	},
	run : function(name, target){
		var callback = mskApp.ContentInitializer.get(name);
		if(callback!=null){
			callback(target);
		}
	},
	runAll : function(){
		$('[data-init-required=1]:not(.initialized)').each(function(){
			var target = $(this);
			mskApp.ContentInitializer.run(target.attr('data-initializer'), target);
			target.addClass('initialized');
			var group = target.attr('data-init-group');
			if((typeof(group) !== "undefined") && (group!="")){
				$('[data-init-group='+group+']').addClass('initialized').attr('data-init-required', 0);
			}
		});
	},
	afterPageChanged : function(){
		setTimeout(mskApp.ContentInitializer.runAll,1000);
	}
};