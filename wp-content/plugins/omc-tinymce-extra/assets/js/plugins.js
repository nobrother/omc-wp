(function(tinymce, $, global) {
	// Register plugin
	tinymce.PluginManager.add( 'omc_tinymce_extra', function(editor, url){
		var DOM = tinymce.DOM,
				each = tinymce.each,
				__ = editor.editorManager.i18n.translate,
				wp = global.wp,
				hasWpautop = ( wp && wp.editor && wp.editor.autop && editor.getParam( 'wpautop', true ) );
		
		// Create Typography menu
		editor.addButton('typography', {
			type: 'menubutton',
			text: 'Typography',
			menu: stylingMenu('typography')
		});
		
		// Create Headings menu
		editor.addButton('headings', {
			type: 'menubutton',
			text: 'Headings',
			menu: stylingMenu('headings')
		});
		
		
		// Helper: Create styling menu
		function stylingMenu(name) {
			var count = 0, newFormats = [];

			var defaultStyleFormats = [
				{title: 'Headings', items: [
					{title: 'Heading 1', format: 'h1'},
					{title: 'Heading 2', format: 'h2'},
					{title: 'Heading 3', format: 'h3'},
					{title: 'Heading 4', format: 'h4'},
					{title: 'Heading 5', format: 'h5'},
					{title: 'Heading 6', format: 'h6'}
				]},

				{title: 'Inline', items: [
					{title: 'Bold', icon: 'bold', format: 'bold'},
					{title: 'Italic', icon: 'italic', format: 'italic'},
					{title: 'Underline', icon: 'underline', format: 'underline'},
					{title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
					{title: 'Superscript', icon: 'superscript', format: 'superscript'},
					{title: 'Subscript', icon: 'subscript', format: 'subscript'},
					{title: 'Code', icon: 'code', format: 'code'}
				]},

				{title: 'Blocks', items: [
					{title: 'Paragraph', format: 'p'},
					{title: 'Blockquote', format: 'blockquote'},
					{title: 'Div', format: 'div'},
					{title: 'Pre', format: 'pre'}
				]},

				{title: 'Alignment', items: [
					{title: 'Left', icon: 'alignleft', format: 'alignleft'},
					{title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
					{title: 'Right', icon: 'alignright', format: 'alignright'},
					{title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
				]}
			];

			function createMenu(formats) {
				var menu = [];

				if (!formats) {
					return;
				}

				each(formats, function(format) {
					var menuItem = {
						text: format.title,
						icon: format.icon
					};

					if (format.items) {
						menuItem.menu = createMenu(format.items);
					} else {
						var formatName = format.format || "custom" + count++;

						if (!format.format) {
							format.name = formatName;
							newFormats.push(format);
						}

						menuItem.format = formatName;
						menuItem.cmd = format.cmd;
					}

					menu.push(menuItem);
				});

				return menu;
			}

			function createStylesMenu() {
				var menu;

				if (editor.settings[name]) {
					menu = createMenu(editor.settings[name]);
				} else {
					menu = createMenu(defaultStyleFormats);
				}

				return menu;
			}

			editor.on('init', function() {
				each(newFormats, function(format) {
					editor.formatter.register(format.name, format);
				});
			});

			return {
				type: 'menu',
				items: createStylesMenu(),
				onPostRender: function(e) {
					editor.fire('renderFormatsMenu', {control: e.control});
				},
				itemDefaults: {
					preview: true,

					textStyle: function() {
						if (this.settings.format) {
							return editor.formatter.getCssText(this.settings.format);
						}
					},

					onPostRender: function() {
						var self = this;

						self.parent().on('show', function() {
							var formatName, command;

							formatName = self.settings.format;
							if (formatName) {
								self.disabled(!editor.formatter.canApply(formatName));
								self.active(editor.formatter.match(formatName));
							}

							command = self.settings.cmd;
							if (command) {
								self.active(editor.queryCommandState(command));
							}
						});
					},

					onclick: function() {
						if (this.settings.format) {
							toggleFormat(this.settings.format);
						}

						if (this.settings.cmd) {
							editor.execCommand(this.settings.cmd);
						}
					}
				}
			};
		}
		
		// Register buttons
		editor.addButton( 'dropcap', {
			tooltip: 'Drop cap',
			cmd: 'dropcap',
			text: 'Dropcap'
		});
		
		editor.addCommand('dropcap', function() {
			var selected_text = editor.selection.getContent();
			var return_text = '';
			return_text = '<span class="dropcap">' + selected_text + '</span>';
			editor.execCommand('mceInsertContent', 0, return_text);
		});
		
		editor.addMenuItem('example', {
			text: 'Example plugin',
			context: 'tools',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'TinyMCE site',
					url: 'http://www.tinymce.com',
					width: 800,
					height: 600,
					buttons: [{
						text: 'Close',
						onclick: 'close'
					}]
				});
			}
		});
	});
})(tinymce, jQuery, window);