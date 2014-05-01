$('.selectable').each(function(i, el) {
	var active	= false;
	var checked	= null;
	$(el).mousedown(function(ev) {
		if (!$(ev.target).is('input[type=checkbox]')) {
			return;
		}
		active	= true;
		checked	= !$(ev.target)
			.prop('checked');
	});
	$(el).mouseup(function(ev) {
		active	= false;
		checked	= null;
	});
	var toggle = function(ev) {
		if (!active) {
			return;
		}
		var parent = $(ev.target).closest($(el).children());
		if (checked) {
			parent.addClass('selected');
		} else {
			parent.removeClass('selected');
		}
		parent
			.find('input[type=checkbox]')
			.prop('checked', checked);
	};
	$(el).mouseover(toggle);
	$(el).mouseout(toggle);
	$(el).find('input[type=checkbox]').change(function(ev) {
		var parent = $(ev.target).closest($(el).children());
		if ($(ev.target).prop('checked')) {
			parent.addClass('selected');
		} else {
			parent.removeClass('selected');
		}
	});
});