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
			// items.height('auto');
			// setTimeout(function() {
			// 	items.height(items.height());
			// }, 1000);
		}, 1);
	}
	var addMulti = function(entities) {
		$(entities).each(function() {
			var entity  = this;
			var wrap	= $($.parseHTML(Mustache.render(template, {i: count++}).trim())[0]);
			var chalk	= wrap.find('.input-chalk');
			var remove	= $(chalk).find('.input-chalk-remove');
			var holder	= $(chalk).find('.input-chalk-holder');
			var input	= $(chalk).find('input');
			var scope	= $(chalk).attr('data-scope') || undefined;
			if (typeof scope != 'undefined') {
				input.val(entity.id);
			} else {
				input.val(JSON.stringify({type: entity.type, id: entity.id}));
			}
			holder.html(entity.card);
			remove.css('display', 'inline-block');
			items.append(wrap);
			setTimeout(function() {
				Chalk.initialize(wrap);
			}, 1);
		});
	}
	var del = function(el) {
		el.remove();
		refresh();
		// items.height('auto');
		// items.height(items.height());
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
			// items.height('auto');
			// setTimeout(function() {
			// 	items.height(items.height());
			// }, 1000);
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
	var chalk = content.find('.input-chalk');
	if (chalk.length) {
		var query = $(chalk).attr('data-query').replace('mode=one', 'mode=all');
		addMultiBtn.css('display', 'inline-block');
		addMultiBtn.click(function(ev) {
			Chalk.modal(Chalk.selectUrl + query, {}, function(res) {
				if (res.entites) {
					addMulti(res.entites);
					var first = $(el).find('.stackable-item:first-child');
					if (first.find('.input-chalk input').val() === '') {
						first.remove();
					}
					refresh();
				}
			});
		});
	}

	if (!items.children().length) {
		add();
	}

});