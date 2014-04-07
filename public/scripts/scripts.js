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
	var input		= $(el).find('.upload-input');
	var list		= $(el).find('.upload-list');
	var template	= $(el).find('.upload-template').html();
	Mustache.parse(template);
	input.fileupload({
		dropZone: el,
		dataType: 'json',
		maxChunkSize: 1048576,
		add: function (e, data) {
			$.each(data.files, function (i, file) {
				file.el = $($.parseHTML(Mustache.render(template, file))).prependTo(list);
			});
			data.submit();
		},
		progress: function (e, data) {
			var file = data.files[0];
			var perc = parseInt(data.loaded / data.total * 100, 10);
			file.el.find('.progress-status')
				.css('height', perc + '%')
				.find('span')
				.text(perc + '%');
		},
		done: function (e, data) {
			$.each(data.files, function (i, file) {
				file.el.find('.progress-status span')
					.text('Processing…');
			});
		}
	});
	button.click(function(ev) {
		input.trigger('click');
	});
});