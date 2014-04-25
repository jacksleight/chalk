$('.expandable').each(function(i, el) {
	$(el).find('.expandable-toggle').click(function(ev) {
		$(el).toggleClass('active');
	});
});