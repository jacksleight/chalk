$('.content').each(function(i, el) {
	var select	= $(el).find('.content-select');
	var name	= $(el).find('.content-name');
	var input	= $(el).find('input[type=hidden]');
	name.html(input.val());
	select.click(function(ev) {
		Ayre.modal(Ayre.baseUrl + 'content/select', function(data) {
			input.val(data.contents[0]);
			name.html(data.contents[0]);
		});
	});
});