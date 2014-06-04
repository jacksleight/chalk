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
//= require ../tinymce-plugin/plugin.js

/* Initialize */

tinyMCE.baseURL = Ayre.rootBaseUrl + 'vendor/jacksleight/ayre/assets/tinymce';
tinyMCE.init({
	content_css: Ayre.rootBaseUrl + 'vendor/jacksleight/ayre/assets/styles/editor.css',
	selector: '.html:not([disabled])',
	menubar: false,
	plugins: 'code paste table contextmenu charmap link image autoresize fullscreen hr visualblocks searchreplace ayre',
	toolbar: 'styleselect bold italic removeformat | bullist numlist table hr | ayrelink link unlink image charmap | pastetext searchreplace | fullscreen visualblocks code',
	contextmenu: "cell row column deletetable",
	statusbar: false,
	autoresize_max_height: 800, 
	paste_retain_style_properties: 'none',
	paste_webkit_styles: 'none',
	paste_word_valid_elements: '-strong/b,-em/i,-p,-ol,-ul,-li,-h1,-h2,-h3,-h4,-h5,-h6,-p/div,-table,-tr,-td[colspan|rowspan],-th,-thead,-tfoot,-tbody,-a[href],sub,sup,strike,br,del',
	formats: {
		// custom_summary: {block: 'p', classes: 'summary'},
		// custom_details: {block: 'p', classes: 'details'},
		// custom_references: {selector: 'ol', classes: 'references'}
	},
	style_formats: [
	    {title: "Header", items: [
	        {title: "Header 1", format: "h1"},
	        {title: "Header 2", format: "h2"},
	        {title: "Header 3", format: "h3"},
	        {title: "Header 4", format: "h4"},
	        {title: "Header 5", format: "h5"},
	        {title: "Header 6", format: "h6"}
	   	]},
	    {title: "Paragraph", items: [
	        {title: "Normal", format: "p"},
	        // {title: "Summary", format: "custom_summary"},
	        // {title: "Details", format: "custom_details"}
	    ]},
	    // {title: "List", items: [
	    //     {title: "References", format: "custom_references"}
	    // ]},
	    {title: "Other", items: [
	        {title: "Quote", format: "blockquote"},
	        {title: "Plain Text", format: "pre"}
	    ]},
	    {title: "Inline", items: [
	        {title: "Bold", format: "bold"},
	        {title: "Italic", format: "italic"},
	        {title: "Superscript", format: "superscript"},
	        {title: "Subscript", format: "subscript"},
	        {title: "Code", format: "code"}
	    ]}
	],
	setup : function(editor) {
		editor.on('init', function(e) {
			editor.theme.resizeTo(null, $(editor.getElement()).height());
        });
   }
});