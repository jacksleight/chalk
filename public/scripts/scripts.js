/* Libraries */

//= require ../../bower_components/log/log.js
//= require ../../bower_components/fastclick/lib/fastclick.js
//= require ../../bower_components/jquery/dist/jquery.js
//= require ../../bower_components/jquery-file-upload/js/vendor/jquery.ui.widget.js
//= require ../../bower_components/jquery-file-upload/js/jquery.iframe-transport.js
//= require ../../bower_components/jquery-file-upload/js/jquery.fileupload.js

/* Initialize */

FastClick.attach(document.body);

$('#fileupload').fileupload({
	dataType: 'json',
	maxChunkSize: 1048576,
	done: function (e, data) {
		$.each(data.result.files, function (index, file) {
			$('<p/>').text(file.name).appendTo(document.body);
		});
	}
});