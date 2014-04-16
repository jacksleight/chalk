/* Libraries */

//= require ../tinymce/tinymce.min.js
//= require ../tinymce/themes/modern/theme.min.js
//= require ../tinymce/plugins/code/plugin.min.js
//= require ../tinymce/plugins/paste/plugin.min.js
//= require ../tinymce/plugins/table/plugin.min.js
//= require ../tinymce/plugins/contextmenu/plugin.min.js
//= require ../tinymce/plugins/charmap/plugin.min.js

/* Initialize */

tinyMCE.baseURL = App.options.base + 'tinymce';
tinyMCE.init({
	selector: '.html:not([disabled])',
	menubar: false,
	plugins: 'code paste table contextmenu charmap',
	toolbar: 'styleselect bold italic removeformat | bullist numlist | undo redo | code | table charmap',
	paste_retain_style_properties: 'none',
	paste_webkit_styles: 'none',
	paste_word_valid_elements: '-strong/b,-em/i,-p,-ol,-ul,-li,-h1,-h2,-h3,-h4,-h5,-h6,-p/div,-table,-tr,-td[colspan|rowspan],-th,-thead,-tfoot,-tbody,-a[href],sub,sup,strike,br,del'
});