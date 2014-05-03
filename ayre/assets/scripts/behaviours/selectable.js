$('.selectable').each(function(i, el) {
	var checkbox = $(el).find('input[type=checkbox]');
	var select = function(ev) {
		if ($(checkbox).prop('checked')) {
			$(el).addClass('selected');
		} else {
			$(el).removeClass('selected');			
		}
	};
	checkbox.change(select);
	select();
});

$('.multiselectable').each(function(i, el) {
	var active	= false;
	var checked	= null
	$(el).mousedown(function(ev) {
		if (!$(ev.target).is('input[type=checkbox] + label')) {
			return;
		}
		ev.preventDefault();
		active	= true;
		checked = !$(ev.target).prev().prop('checked');
	});
	$(el).mouseup(function(ev) {
		active	= false;
		checked	= null;
	});
	var select = function(ev) {
		if (!active) {
			return;
		}
		var selectable = $(ev.target).closest('.selectable');
		var checkbox = $(selectable).find('input[type=checkbox]');
		if (checkbox.prop('checked') != checked) {
			checkbox.trigger('click');
		}
	};
	$(el).mouseover(select);
	$(el).mouseout(select);
});