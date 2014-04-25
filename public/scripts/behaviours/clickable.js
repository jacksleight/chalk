$('.clickable').each(function(i, el) {
	var target = $(el).find('a')[0];
	$(el).mouseover(function(ev) {
		if ($(ev.target).is('a')) {
			return;
		}
		$(target).addClass('hover');
	});
	$(el).mouseout(function(ev) {
		if ($(ev.target).is('a')) {
			return;
		}
		$(target).removeClass('hover');
	});
	$(el).click(function(ev) {
		if ($(ev.target).is('a')) {
			return;
		}
		target.click();
	});
});