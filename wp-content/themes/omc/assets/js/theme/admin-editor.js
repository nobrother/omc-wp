// Load jQuery Cookies
// TODO: improve
(function($){
	if(typeof $.cookie === 'undefined'){
		/*! jquery.cookie v1.4.1 | MIT */
		!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?a(require("jquery")):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setTime(+k+864e5*j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0===a.cookie(b)?!1:(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}});
	}	
})(jQuery);

// Code Mirror
(function($){
	$(function(){
		var modeList = {
			js		: 'text/javascript',
			css		: 'text/x-less',
			less	: 'text/x-less',
			php		:	'application/x-httpd-php',
			html	: 'text/html'
		};
		$('textarea.code-editor').each(function(){
			var mode = $(this).data('mode');
			if(!modeList[mode])
				mode = 'html';
			var cookieKey = $(this).data('editor-id') || '';
			var theme = $.cookie(cookieKey+'-theme') || 'default';
			var fontSize = $.cookie(cookieKey+'-font-size') || '15px';
			// Create codemirror instance
			var editor = CodeMirror.fromTextArea(
				this,
				{
					mode: modeList[mode],
					theme: theme,
					styleActiveLine: true,
					lineNumbers: true,
					lineWrapping: true,
					indentWithTabs: true,
					indentSize: 1,
					tabSize: 2,
					matchTags: {bothTags: true},
					matchBrackets : true,
					highlightSelectionMatches: {showToken: /\w/},
					autoCloseTags: true
				}
			)
			// Set height
			if($(this).data('height'))
				editor.setSize('auto', $(this).data('height'));
			// Set fontSize
			$('.CodeMirror').css('font-size', fontSize);
			// Store code mirror instance
			$(this).data('editor', editor);
			// Update textarea when form submit
			var form = $(this).closest('form');
			if(form.length){
				form.on('submit', function(){
					editor.save();
				})
			}
			// Insert editor setting bar
			var html = '<div class="editor-settings">';
			html += '<a class="btn-collapse">Settings</a>';
			html += '<div class="collapse-wrap" style="display:none">';
			html += '<select class="theme-options">';
			for( var themes = omcAdminEditorSettings.themeOptions, i = 0, l = themes.length; i < l; i++ ){
				html += '<option' + (theme == themes[i] ? ' selected' : '') + '>' + themes[i] + '</option>'
			}
			html += '</select>';
			html += '<input class="font-size" value="'+fontSize+'">';
			html += '</div>';
			html += '</div>';
			html = $(html).insertBefore(this);
			// Event: Collapse
			html.find('.btn-collapse').on('click', function(e){
				e.preventDefault();
				if($(this).is('.collapsed')){
					$(this).next().stop(true,true).slideUp(200);
					$(this).removeClass('collapsed');
				}
				else{
					$(this).next().stop(true,true).slideDown(200);
					$(this).addClass('collapsed');
				}
			})
			// Event: Change theme
			html.find('.theme-options').on('change', function(){
				var theme = $(this).val();
				$.cookie(cookieKey+'-theme', theme, 
				{
					 expires : 10*365,           // expires in 10 years
					 path    : '/'
				})
				editor.setOption( 'theme', $(this).val());
			});
			// Event: Font Size
			html.find('.font-size').on('change', function(){
				var fontSize = $(this).val();
				$.cookie(cookieKey+'-font-size', fontSize, 
				{
					 expires : 10*365,           // expires in 10 years
					 path    : '/'
				})
				$('.CodeMirror').css('font-size', fontSize);
			});
		})
	})
})(jQuery);

// Ajax save
(function($){
	$(function(){
		$(ctrl_s_target.target).on('submit', function(e){
			var $this = $(this);
			console.log($this);
			e.preventDefault();
			$.ajax({
				type		: $this.attr('method'),
				url			: $this.attr('action'),
				data		: $this.serialize(),
				beforeSend : function(){
					overlay.addText('Saving...').show(100)
				},
				success	: function(response){
					overlay.addText('Done!').hide(100, 300)
					console.log(response)
				},
				error : function(xhr, status, error){
					overlay.addText('Fail!<br>Error: ' + status).hide(100, 5000)
				}
			});
			$(window).off('beforeunload'); // Prevent the alert
			window.onbeforeunload = null; // Prevent the alert
		})
	})
})(jQuery);

// Ctrl + S
(function($){
	$(function(){
		$(document).on('keydown', '*', function(e){
			e.stopPropagation();
			if(e.ctrlKey && e.which === 83){
				e.preventDefault();
				$(this).closest('form').submit();
			}
		})
	})
})(jQuery);
























