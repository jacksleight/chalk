$('.selectable').each(function(i, el) {
	var checkbox = $(el).find('input[type=checkbox]');
	checkbox.change(function(ev) {
		if ($(checkbox).prop('checked')) {
			$(el).addClass('selected');
		} else {
			$(el).removeClass('selected');			
		}
	});
});