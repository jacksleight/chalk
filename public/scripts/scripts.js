/* Libraries */

//= require ../../bower_components/log/log.js
//= require ../../bower_components/fastclick/lib/fastclick.js
//= require ../../bower_components/jquery/dist/jquery.js
//= require ../../bower_components/jquery-file-upload/js/vendor/jquery.ui.widget.js
//= require ../../bower_components/jquery-file-upload/js/jquery.iframe-transport.js
//= require ../../bower_components/jquery-file-upload/js/jquery.fileupload.js
//= require ../../bower_components/mustache/mustache.js

/* Initialize */

FastClick.attach(document.body);

$('.upload').each(function(i, el) {
	var button		= $(el).find('.upload-button');
	var list		= $(el).find('.upload-list');
	var template	= $(el).find('.upload-template').html();
	Mustache.parse(template);
	$(el).find('.upload-input').fileupload({
		dropZone: el,
		dataType: 'json',
		maxChunkSize: 1048576,
		limitConcurrentUploads: 3
	}).bind('fileuploadadd', function (e, data) {
		var file = data.files[0];
		data.context = $($.parseHTML(Mustache.render(template, file).trim())[0]);
		list.prepend(data.context);
	}).bind('fileuploadprogress', function (e, data) {
		var perc = parseInt(data.loaded / data.total * 100, 10);
		data.context.find('.progress span')
			.css('height', perc + '%');
		data.context.find('.info')
			.text(perc == 100 ? 'Processing…' : perc + '%' + ' Uploaded');
	}).bind('fileuploaddone', function (e, data) {
		var result	= data.result.files[0];
		var replace	= $($.parseHTML(result.html.trim())[0]);
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
		$(el).find('.upload-input').trigger('click');
	});
});

$('.linkable').each(function(i, el) {
	var link = $(el).find('a')[0];
	$(el).click(function(ev) {
		ev.preventDefault();
		window.location.href = link.href;
	});
});

$('.submitable').each(function(i, form) {
	var inputs = $(form).find('input');
	$(inputs).change(function(ev) {
		$(form).trigger('submit');
	});
});

$('.thumbs').each(function(i, el) {
	var width = $(el).children(':first-child').outerWidth();
	var refresh = function() {
		var total = $(el).innerWidth(),
			count = Math.floor(total / width),
			perc  = 100 / count;
		$(el).children().css('width', perc + '%');
	};
	refresh();
	$(window).resize(refresh);
	$(el).css('visibility', 'visible');
});

$('.confirm').each(function(i, el) {
	var message = $(el).attr('data-message') || 'Are you sure?';
	$(el).click(function(ev) {
		if (!confirm(message)) {
			ev.preventDefault();
		}	
	});
});