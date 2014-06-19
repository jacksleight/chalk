/* Libraries */

//= require ../tinymce/tinymce.min.js
//= require ../tinymce/themes/modern/theme.min.js
//= require ../tinymce/plugins/autoresize/plugin.min.js
//= require ../tinymce/plugins/charmap/plugin.min.js
//= require ../tinymce/plugins/code/plugin.min.js
//= require ../tinymce/plugins/fullscreen/plugin.min.js
//= require ../tinymce/plugins/hr/plugin.min.js
//= require ../tinymce/plugins/image/plugin.min.js
//= require ../tinymce/plugins/link/plugin.min.js
//= require ../tinymce/plugins/lists/plugin.min.js
//= require ../tinymce/plugins/paste/plugin.min.js
//= require ../tinymce/plugins/searchreplace/plugin.min.js
//= require ../tinymce/plugins/table/plugin.min.js
//= require ../tinymce/plugins/visualblocks/plugin.min.js
//= require ../tinymce/plugins/noneditable/plugin.min.js
//= require ../tinymce-plugin/plugin.js

/* Initialize */

Ayre.component(null, function(i, el) {

    var assetsUrl   = Ayre.rootBaseUrl + 'vendor/jacksleight/ayre/assets';
    tinyMCE.baseURL = assetsUrl + '/tinymce';

    var css = '';
    var styles = [
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
        ]},
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
    ];
    if (Ayre.styles) {

        var groups = {}, group, style;
        for (var i = 0; i < styles.length; i++) {
            group = styles[i];
            groups[group.title] = i;
        }
        for (var i = 0; i < Ayre.styles.length; i++) {
            style = Ayre.styles[i];
            group = style.group;
            style = {
                title:    style.label    || undefined,
                selector: style.selector || undefined,
                block:    style.block    || undefined,
                inline:   style.inline   || undefined,
                classes:  style.classes  || undefined
            };
            if (group && groups[group]) {
                styles[groups[group]].items.push(style);
            } else if (group) {
                styles.push({title: group, items: [style]});
                groups[group] = styles.length - 1;
            } else {
                styles.push(style);
            }
        }
        styles.push(styles.splice(2, 1)[0]);
        styles.push(styles.splice(2, 1)[0]);
    
        var css = [], selector, block, inline, classes;
        for (var i = 0; i < Ayre.styles.length; i++) {
            style    = Ayre.styles[i];
            selector = style.selector || '',
            block    = style.block    || '',
            inline   = style.inline   || '',
            classes  = style.classes.split(' ')
            for (var i = 0; i < classes.length; i++) {
                css.push(selector + ' ' + (block || inline) + '.' + classes[i] + ' { ' + style.css + ' }');
            }

        }
        css = css.join();

    }

    tinyMCE.init({
        content_css: [
            assetsUrl + '/build/editor.css',
            'data:text/css;charset=utf-8;base64,' + Base64.encode(css)
        ],
        selector: '.html:not([disabled])',
        menubar: false,
        convert_urls: false,
        plugins:[
            'noneditable',
            'code',
            'paste',
            'table',
            'charmap',
            'link',
            'image',
            'autoresize',
            'fullscreen',
            'hr',
            'visualblocks',
            'searchreplace',
            'lists',
            'ayre'].join(' '),
        toolbar: [
            'styleselect', 'bold', 'italic', 'removeformat', '|',
            'bullist', 'numlist', 'table', 'hr', '|',
            'ayreinsert', 'unlink', '|',
            'pastetext', 'searchreplace', '|',
            'fullscreen', 'visualblocks', 'code'].join(' '),
        statusbar: false,
        browser_spellcheck: true,
        element_format: 'html',
        autoresize_max_height: 800, 
        paste_retain_style_properties: 'none',
        paste_word_valid_elements: [
            '-strong/b', '-em/i',
            '-p', '-p/div', '-ol', '-ul', '-li',
            '-h1', '-h2', '-h3', '-h4', '-h5', '-h6',
            '-table', '-tr', '-td[colspan|rowspan]', '-th', '-thead', '-tfoot', '-tbody',
            '-a[href]', 'sub', 'sup', 'strike', 'br', 'del'].join(','),
        style_formats: styles,
        setup: function(editor) {
            editor.on('init', function(ev) {
                editor.theme.resizeTo(null, $(editor.getElement()).height());
            });
       }
    });

});