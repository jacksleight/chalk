/* Libraries */

//= require ../tinymce/tinymce.min.js
//= require ../tinymce/themes/modern/theme.min.js
//= require ../tinymce/plugins/code/plugin.min.js
//= require ../tinymce/plugins/paste/plugin.min.js
//= require ../tinymce/plugins/table/plugin.min.js
//= require ../tinymce/plugins/contextmenu/plugin.min.js
//= require ../tinymce/plugins/charmap/plugin.min.js
//= require ../tinymce/plugins/link/plugin.min.js
//= require ../tinymce/plugins/image/plugin.min.js
//= require ../tinymce/plugins/autoresize/plugin.min.js
//= require ../tinymce/plugins/fullscreen/plugin.min.js
//= require ../tinymce/plugins/hr/plugin.min.js
//= require ../tinymce/plugins/visualblocks/plugin.min.js
//= require ../tinymce/plugins/searchreplace/plugin.min.js

/* Initialize */

tinyMCE.baseURL = Ayre.rootBaseUrl + 'vendor/jacksleight/ayre/assets/tinymce';
tinyMCE.init({
	content_css: Ayre.rootBaseUrl + 'vendor/jacksleight/ayre/assets/styles/editor.css',
	selector: '.html:not([disabled])',
	menubar: false,
	plugins: 'code paste table contextmenu charmap link image autoresize fullscreen hr visualblocks searchreplace',
	toolbar: 'styleselect bold italic removeformat | bullist numlist table hr | link image charmap | pastetext searchreplace | fullscreen visualblocks code',
	statusbar: false,
	paste_retain_style_properties: 'none',
	paste_webkit_styles: 'none',
	paste_word_valid_elements: '-strong/b,-em/i,-p,-ol,-ul,-li,-h1,-h2,-h3,-h4,-h5,-h6,-p/div,-table,-tr,-td[colspan|rowspan],-th,-thead,-tfoot,-tbody,-a[href],sub,sup,strike,br,del',
	setup : function(editor) {
		editor.on('init', function(e) {
			editor.theme.resizeTo(null, $(editor.getElement()).height());
        });
   }
});