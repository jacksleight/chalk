Chalk.component('.input-chalk', function(i, el) {
	
	var select		= $(el).find('.input-chalk-select');
	var remove		= $(el).find('.input-chalk-remove');
	var holder		= $(el).find('.input-chalk-holder');
	var input		= $(el).find('input');
	var mode    	= $(el).attr('data-mode');
	var query    	= $(el).attr('data-query');
	
	select.click(function(ev) {
		Chalk.modal(Chalk.selectUrl + query, {}, function(res) {
			if (res.entities) {
				var value;
				if (mode === 'entity') {
					value = res.entities[0].id;
				} else if (mode === 'ref') {
					value = JSON.stringify({
						type: res.entities[0].type,
						id: res.entities[0].id,
						sub: res.entities[0].sub,
					});
				}
				input.val(value);
				input.attr('disabled', false);
				holder.html(res.entities[0].card);
				remove.css('display', 'inline-block');
			}
		});
	});
	remove.click(function(ev) {
		input.val('');
		input.attr('disabled', true);
		holder.html('<span class="placeholder">Nothing Selected</span>');
		remove.hide();
	});

	if (!input.val()) {
		remove.hide();
	}
	
});