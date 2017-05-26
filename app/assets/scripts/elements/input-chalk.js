Chalk.component('.input-chalk', function(i, el) {
	
	var select		= $(el).find('.input-chalk-select');
	var remove		= $(el).find('.input-chalk-remove');
	var holder		= $(el).find('.input-chalk-holder');
	var input		= $(el).find('input');
	var scope    	= $(el).attr('data-scope') || undefined;
	var query    	= $(el).attr('data-query');
	
	select.click(function(ev) {
		Chalk.modal(Chalk.selectUrl + query, {}, function(res) {
			if (res.entites) {
				if (typeof scope != 'undefined') {
					input.val(res.entites[0].id);
				} else {
					input.val(JSON.stringify({type: res.entites[0].type, id: res.entites[0].id}));
				}
				holder.html(res.entites[0].card);
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