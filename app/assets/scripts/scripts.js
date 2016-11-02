/* Libraries */

//= require ../../../bower_components/log/log.js
//= require ../../../bower_components/fastclick/lib/fastclick.js
//= require ../../../bower_components/js-base64/base64.js

//= require ../../../bower_components/jquery/dist/jquery.js
//= require ../../../bower_components/jquery.ui/ui/core.js
//= require ../../../bower_components/jquery.ui/ui/widget.js
//= require ../../../bower_components/jquery.ui/ui/mouse.js
//= require ../../../bower_components/jquery.ui/ui/sortable.js
//= require ../../../bower_components/jquery.ui/ui/datepicker.js

//= require ../../../bower_components/Nestable/jquery.nestable.js

//= require ../../../bower_components/jquery-file-upload/js/vendor/jquery.ui.widget.js
//= require ../../../bower_components/jquery-file-upload/js/jquery.iframe-transport.js
//= require ../../../bower_components/jquery-file-upload/js/jquery.fileupload.js
//= require ../../../bower_components/jquery-file-upload/js/jquery.fileupload-process.js
//= require ../../../bower_components/jquery-file-upload/js/jquery.fileupload-validate.js

//= require ../../../bower_components/selectize/dist/js/standalone/selectize.js

//= require ../../../bower_components/mustache/mustache.js

/* Utilities */

//= require utils/initialize.js
//= require utils/ping.js
//= require utils/prefs.js
//= require utils/modal.js

/* Elements */

//= require elements/notifications.js
//= require elements/tree.js
//= require elements/content.js
//= require elements/editor.js
//= require elements/tag.js
//= require elements/date.js

/* Behaviours */

//= require behaviours/confirmable.js
//= require behaviours/autosubmitable.js
//= require behaviours/clickable.js
//= require behaviours/expandable.js
//= require behaviours/uploadable.js
//= require behaviours/selectable.js
//= require behaviours/stackable.js
//= require behaviours/ajaxable.js

/* Initialize */

FastClick.attach(document.body);
Chalk.initialize(document.body);

/* Execute */

for (var i = 0; i < Chalk.execute.length; i++) {
	Chalk.execute[i]();
}