$('.autosubmitable').each(function(i, el) {
	var inputs = $(el).find('input, textarea, select');
	$(inputs).change(function(ev) {
		ev.target.form.submit();
	});
});