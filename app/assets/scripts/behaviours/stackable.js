Chalk.component('.stackable', function(i, el) {
	
	var items		= $(el).find('.stackable-items');
	var addBtn		= $(el).find('.stackable-add');
	var addMultiBtn	= $(el).find('.stackable-add-multiple');
	var template	= $(el).find('.stackable-template').html();
	var count		= items.children().length;

	var add = function() {
		var content = $($.parseHTML(Mustache.render(template, {i: count++}).trim())[0]);
		items.append(content);
		setTimeout(function() {
			Chalk.initialize(content);
			items.height('auto');
			setTimeout(function() {
				items.height(items.height());
			}, 1000);
		}, 1);
	}
	var addMulti = function(contents) {
		$(contents).each(function() {
			var content = $($.parseHTML(Mustache.render(template, {i: count++}).trim())[0]);
			var control = content.find('.content');
			control.find('input[type=hidden]').val(this.id);
			control.find('.content-holder').html(this.card);
			control.find('.content-remove').css('display', 'inline-block');
			items.append(content);
			setTimeout(function() {
				Chalk.initialize(content);
				items.height('auto');
				setTimeout(function() {
					items.height(items.height());
				}, 1000);
			}, 1);
		});
	}
	var del = function(el) {
		el.remove();
		refresh();
		items.height('auto');
		items.height(items.height());
	}
	var refresh = function() {
		items.children().each(function(i) {
			$(this).find('input, textarea, select, button, label').each(function() {
				if ($(this).attr('name')) {
					$(this).attr('name', $(this).attr('name').replace(/\[\d+\]/, '[' + i + ']'));
				}
			});
		});
	}

	addBtn.click(function(ev) {
		add();
	});
	$(el).on('click', '.stackable-delete', function(ev) {
		if (!confirm('Are you sure?')) {
			return;
		}
		del($(this).closest('.stackable-item'));
	});
	
	items.sortable({
		handle: '.stackable-move',
		placeholder: 'stackable-placeholder',
		helper: function() {
			return $('<div class="stackable-helper">');
		},
		forcePlaceholderSize: true,
		forceHelperSize: true,
		axis: 'y',
		create: function(ev, ui) {
			items.height('auto');
			setTimeout(function() {
				items.height(items.height());
			}, 1000);
		},
		start: function(ev, ui) {
			$(ui.item).find('.editor-content').each(function () {
				tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
			});
		},
		stop: function(ev, ui) {
			$(ui.item).find('.editor-content').each(function () {
				tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
			});
			refresh();
		}
	});
	
	var content = $($.parseHTML(Mustache.render(template, {i: 0}).trim())[0]);
	var control = content.find('.content');
	if (control.length) {
		var entity = control.attr('data-entity');
		addMultiBtn.css('display', 'inline-block');
		addMultiBtn.click(function(ev) {
			Chalk.modal(Chalk.browseUrl.replace('{entity}', entity), {}, function(res) {
				if (res.contents) {
					addMulti(res.contents);
				}
			});
		});
	}

	if (!items.children().length) {
		add();
	}

});