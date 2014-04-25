$('.autosubmitable').each(function(i, form) {
	var inputs = $(form).find('input');
	$(inputs).change(function(ev) {
		form.submit();
	});
});