Chalk.component('.confirmable', function(i, el) {
	
	var message = $(el).attr('data-message') || 'Are you sure?';
	$(el).click(function(ev) {
		if (!confirm(message)) {
			ev.preventDefault();
		}	
	});
	
}); 