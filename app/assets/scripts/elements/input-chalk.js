Chalk.component('.input-chalk', function(i, el) {
	
	var select		= $(el).find('.input-chalk-select');
	var remove		= $(el).find('.input-chalk-remove');
	var holder		= $(el).find('.input-chalk-holder');
	var input		= $(el).find('input');
	var mode    	= $(el).attr('data-mode');
	var query    	= $(el).attr('data-query');
	
	select.click(function(ev) {
		Chalk.modal(Chalk.selectUrl + query, {}, function(res) {
			console.log(res);
			
			if (res.items) {
				var item = res.items[0];
				var value;
				if (mode === 'entity') {
					value = item.ref.id;
				} else if (mode === 'ref') {
					value = item.refString;
				}
				input.val(value);
				input.attr('disabled', false);
				holder.html(item.card);
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