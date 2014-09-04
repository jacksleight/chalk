Ayre.component('.autosubmitable', function(i, el) {

	var inputs = $(el).find('input, textarea, select');
	var button = el.ownerDocument.createElement('input');
    button.style.display = 'none';
    button.type = 'submit';
    el.appendChild(button);

	$(inputs).change(function(ev) {
		button.click();
	});
	
});