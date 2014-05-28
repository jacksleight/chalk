Ayre.component('.content', function(i, el) {
	
	var select	= $(el).find('.content-select');
	var holder	= $(el).find('.content-holder');
	var input	= $(el).find('input[type=hidden]');
	select.click(function(ev) {
		Ayre.modal(Ayre.baseUrl + 'content/select', function(data) {
			input.val(data.contents[0].id);
			holder.html(data.contents[0].card);
		});
	});
	
});