Chalk.component('.input-chalk', function(i, el) {
	
	var select		= $(el).find('.input-chalk-select');
	var remove		= $(el).find('.input-chalk-remove');
	var holder		= $(el).find('.input-chalk-holder');
	var input		= $(el).find('input');
	var scope    	= $(el).attr('data-scope') || undefined;
	var query    	= $(el).attr('data-query');
	
	select.click(function(ev) {
		Chalk.modal(Chalk.selectUrl + query, {}, function(res) {
			if (res.entities) {
				if (typeof scope != 'undefined') {
					input.val(res.entities[0].id);
				} else {
					input.val(JSON.stringify({
						type: res.entities[0].type,
						id: res.entities[0].id,
						sub: res.entities[0].sub,
					}));
				}
				holder.html(res.entities[0].card);
				remove.css('display', 'inline-block');
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