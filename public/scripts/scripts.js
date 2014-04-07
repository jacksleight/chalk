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
		limitConcurrentUploads: 5
	}).bind('fileuploadadd', function (e, data) {
		var file = data.files[0];
		data.context = $($.parseHTML(Mustache.render(template, file).trim())[0]);
		list.prepend(data.context);
	}).bind('fileuploadprogress', function (e, data) {
		var perc = parseInt(data.loaded / data.total * 100, 10);
		data.context.find('.progress .status')
			.css('height', perc + '%');
		if (perc == 100) {
			data.context.find('.info').text('Processing…');
		} else {
			data.context.find('.info').text(perc + '%' + ' Uploaded');
		}
	}).bind('fileuploaddone', function (e, data) {
		var result = data.result.files[0];
		var progress = data.context.find('.progress');
		var el = $($.parseHTML(result.html.trim())[0]);
		el.find('.preview').append(progress);
		data.context.replaceWith(el);
		setTimeout(function() {
			el.find('.progress .status').css('height', 0);			
		}, 0);
	});
	button.click(function(ev) {
		$(el).find('.upload-input').trigger('click');
	});
});