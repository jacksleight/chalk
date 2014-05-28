/* Libraries */

//= require ../../bower_components/log/log.js
//= require ../../bower_components/fastclick/lib/fastclick.js

//= require ../../bower_components/jquery/dist/jquery.js
//= require ../../bower_components/Nestable/jquery.nestable.js
//= require ../../bower_components/jquery-file-upload/js/vendor/jquery.ui.widget.js
//= require ../../bower_components/jquery-file-upload/js/jquery.iframe-transport.js
//= require ../../bower_components/jquery-file-upload/js/jquery.fileupload.js

//= require ../../bower_components/mustache/mustache.js

/* Utilities */

//= require utils/initialize.js
//= require utils/prefs.js
//= require utils/modal.js

/* Behaviours */

//= require behaviours/autosubmitable.js
//= require behaviours/clickable.js
//= require behaviours/confirmable.js
//= require behaviours/expandable.js
//= require behaviours/uploadable.js
//= require behaviours/selectable.js
//= require behaviours/stackable.js

/* Elements */

//= require elements/thumbs.js
//= require elements/tree.js
//= require elements/content.js

/* Initialize */

FastClick.attach(document.body);
Ayre.initialize(document.body);