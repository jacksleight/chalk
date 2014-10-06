Chalk.component('.content', function(i, el) {
	
	var select	= $(el).find('.content-select');
	var remove	= $(el).find('.content-remove');
	var holder	= $(el).find('.content-holder');
	var input	= $(el).find('input[type=hidden]');
	var entity	= $(el).attr('data-entity');
	
	select.click(function(ev) {
		Chalk.modal(Chalk.baseUrl + 'content/' + entity + '/select', {}, function(res) {
			if (res.contents) {
				input.val(res.contents[0].id);
				holder.html(res.contents[0].card);
				remove.show();
			}
		});
	});
	remove.click(function(ev) {
		input.val('');
		holder.html('<span class="placeholder">Nothing Selected</span>');
		remove.hide();
	});

	if (!input.val()) {
		remove.hide();
	}
	
});