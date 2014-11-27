Chalk.component('.stackable', function(i, el) {
	
	var list		= $(el).find('.stackable-items');
	var button		= $(el).find('.stackable-add');
	var template	= $(el).find('.stackable-template').html();
	var count		= list.children().length;

	var add = function() {
		var content = $($.parseHTML(Mustache.render(template, {i: count++}).trim())[0]);
		list.append(content);
		setTimeout(function() {
			Chalk.initialize(content);
			list.height('auto');
			setTimeout(function() {
				list.height(list.height());
			}, 1000);
		}, 1);
	}
	var del = function(el) {
		el.remove();
		refresh();
		list.height('auto');
		list.height(list.height());
	}
	var refresh = function() {
		list.children().each(function(i) {
			$(this).find('input, textarea, select, button, label').each(function() {
				if ($(this).attr('name')) {
					$(this).attr('name', $(this).attr('name').replace(/\[\d\]/, '[' + i + ']'));
				}
			});
		});
	}

	button.click(function(ev) {
		add();
	});
	$(el).on('click', '.stackable-delete', function(ev) {
		del($(this).closest('.stackable-item'));
	});
	
	list.sortable({
		handle: '.stackable-move',
		placeholder: 'stackable-placeholder',
		helper: function() {
			return $('<div class="stackable-helper">');
		},
		forcePlaceholderSize: true,
		forceHelperSize: true,
		axis: 'y',
		create: function(ev, ui) {
			list.height('auto');
			setTimeout(function() {
				list.height(list.height());
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
	
	if (!list.children()) {
		add();
	}

});