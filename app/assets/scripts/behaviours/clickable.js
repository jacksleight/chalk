Chalk.component('.clickable', function(i, el) {
	
	var target = $(el).find('a')[0];
	$(el).mouseover(function(ev) {
		if ($(ev.target).is('a, input, label')) {
			return;
		}
		$(target).addClass('hover');
	});
	$(el).mouseout(function(ev) {
		if ($(ev.target).is('a, input, label')) {
			return;
		}
		$(target).removeClass('hover');
	});
	$(el).click(function(ev) {
		if ($(ev.target).is('a, input, label')) {
			return;
		}
		target.click();
	});

});