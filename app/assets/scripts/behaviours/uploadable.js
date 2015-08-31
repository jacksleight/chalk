$(document).bind('drop dragover', function (ev) {
    ev.preventDefault();
});
Chalk.component('.uploadable', function(i, el) {
	
	var button		= $(el).find('.uploadable-button');
	var list		= $(el).find('.uploadable-list');
	var template	= $(el).find('.uploadable-template').html();
	var panel		= $(el).find('.panel');
	Mustache.parse(template);

	$(el).find('.uploadable-input').fileupload({
		dropZone: el,
		dataType: 'json',
		maxChunkSize: 1048576,
		sequentialUploads: true
	}).bind('fileuploadadd', function (e, data) {
		var file = data.files[0];
		data.context = $($.parseHTML('<li class="thumbs_i">' + Mustache.render(template, file).trim() + '</li>')[0]);
		list.prepend(data.context);
		panel.remove();
	}).bind('fileuploadprogress', function (e, data) {
		var perc = parseInt(data.loaded / data.total * 100, 10);
		data.context.find('.progress span')
			.css('height', perc + '%');
		data.context.find('small')
			.text(perc == 100 ? 'Processing…' : perc + '%' + ' Uploaded');
	}).bind('fileuploaddone', function (e, data) {
		var result	= data.result.files[0];
		var replace	= $($.parseHTML('<li class="thumbs_i">' + result.html.trim() + '</li>')[0]);
		data.context.replaceWith(replace);
		data.context = replace;
		var reveal = function() {
			data.context.find('.progress')
				.addClass('out')
			data.context.find('.progress span')
				.css('height', 0);
		};
		var image = data.context.find('img');
		if (image.length) {
			image[0].onload = reveal;
		} else {
			setTimeout(reveal, 0);
		}
	});
	
	button.click(function(ev) {
		$(el).find('.uploadable-input').trigger('click');
	});

});