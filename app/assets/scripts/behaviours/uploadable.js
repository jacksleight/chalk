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
	}).bind('fileuploadprocessfail', function (e, data) {
		data.context.remove();
		var file = data.files[0];
		alert('File \'' + file.name + '\' exceeds the maxiumum size (' + Math.round(data.maxFileSize / 1048576) + 'MB).');
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
		Chalk.initialize(replace);
		setTimeout(function() {
			data.context.find('.progress')
				.addClass('out')
			data.context.find('.progress span')
				.css('height', 0);
		}, 0);
	});
	
	button.click(function(ev) {
		$(el).find('.uploadable-input').trigger('click');
	});

});
Chalk.component('.input-upload', function(i, el) {
	
	var button		= $(el).find('.input-upload-button');
	var holder		= $(el).find('.input-upload-holder');
	var template	= $(el).find('.input-upload-template').html();
	var original;
	Mustache.parse(template);

	$(el).find('.input-upload-input').fileupload({
		dropZone: el,
		dataType: 'json',
		maxChunkSize: 1048576,
		sequentialUploads: false
	}).bind('fileuploadadd', function (e, data) {
		original = holder.html();
		var file = data.files[0];
		data.context = $($.parseHTML(Mustache.render(template, file).trim())[0]);
		holder.html(data.context);
	}).bind('fileuploadprocessfail', function (e, data) {
		holder.html(original);
		var file = data.files[0];
		alert('File \'' + file.name + '\' exceeds the maxiumum size (' + Math.round(data.maxFileSize / 1048576) + 'MB).');
	}).bind('fileuploadprogress', function (e, data) {
		var perc = parseInt(data.loaded / data.total * 100, 10);
		data.context.find('.progress span')
			.css('height', perc + '%');
		data.context.find('small')
			.text(perc == 100 ? 'Processing…' : perc + '%' + ' Uploaded');
	}).bind('fileuploaddone', function (e, data) {
		var result	= data.result.files[0];
		var replace	= $($.parseHTML(result.html.trim())[0]);
		data.context.replaceWith(replace);
		data.context = replace;
		Chalk.initialize(replace);
		setTimeout(function() {
			data.context.find('.progress')
				.addClass('out')
			data.context.find('.progress span')
				.css('height', 0);
		}, 0);
	});
	
	button.click(function(ev) {
		$(el).find('.input-upload-input').trigger('click');
	});

});