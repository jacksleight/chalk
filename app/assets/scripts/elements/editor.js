Chalk.component('.editor-content', function(i, el) {
	
	var initialize = function(el) {
		tinymce.execCommand('mceRemoveEditor', true, $(el).attr('id'));
		tinymce.execCommand('mceAddEditor', true, $(el).attr('id'));
	};

	if (Chalk.editorContent.loaded) {
		initialize(el);
	} else {
		Chalk.editorContent.queue.push(el);
		if (!Chalk.editorContent.loading) {
			Chalk.editorContent.loading = true;
			$.getScript(Chalk.editorContent.src, function() {
				Chalk.editorContent.loaded = true;
				while (Chalk.editorContent.queue.length) {
					initialize(Chalk.editorContent.queue.pop());
				}
			});
		}
	}
	
});

Chalk.component('.editor-code', function(i, el) {
	
	var initialize = function(el) {
		$(el).hide();
	    var div = $('<div>')
	        .css('height', $(el).outerHeight() + 'px')
	        .insertAfter(el);
	    var editor = ace.edit(div[0]);
	    editor.getSession().setUseWorker(false);
	    if ($(el).hasClass('editor-code-json')) {
			editor.getSession().setMode("ace/mode/json");
	    } else {
			editor.getSession().setMode("ace/mode/html");
	    }
	    editor.getSession().setUseWrapMode(true);
	    editor.getSession().setValue($(el).val());
	    editor.getSession().on('change', function(){
	        $(el).val(editor.getSession().getValue());
	    });
	};

	if (Chalk.editorCode.loaded) {
		initialize(el);
	} else {
		Chalk.editorCode.queue.push(el);
		if (!Chalk.editorCode.loading) {
			Chalk.editorCode.loading = true;
			$.getScript(Chalk.editorCode.src, function() {
				Chalk.editorCode.loaded = true;
				while (Chalk.editorCode.queue.length) {
					initialize(Chalk.editorCode.queue.pop());
				}
			});
		}
	}

});