/* Libraries */

//= require ../../../bower_components/tinymce/tinymce.min.js
//= require ../../../bower_components/tinymce/themes/modern/theme.min.js
//= require ../../../bower_components/tinymce/plugins/charmap/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/code/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/hr/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/image/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/link/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/lists/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/paste/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/searchreplace/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/table/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/visualblocks/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/noneditable/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/autoresize/plugin.min.js
//= require ../../../bower_components/tinymce/plugins/fullscreen/plugin.min.js

/* Plugin */

tinymce.PluginManager.add('chalk', function(editor, url) {

    var openLinkModal = function() {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            code            = selection.getContent()
            text            = selection.getContent({format: 'text'})
            richSelection   = /</.test(code) && (!/^<a [^>]+>[^<]+<\/a>$/.test(code) || code.indexOf('href=') == -1);
    
        Chalk.modal(Chalk.selectUrl + '?filters=core_info_link', {}, function(res) {
            if (!res) {
                return;
            }
            var content = res.contents[0];
            var attrs = {
                href: '#',
                'data-chalk': JSON.stringify({
                    content: {
                        id: content.id
                    }
                })
            };
            if (!text.length) {
                text = content.name;
            }
            if (!richSelection) {
                editor.insertContent(dom.createHTML('a', attrs, dom.encode(text)));
            } else {
                editor.execCommand('mceInsertLink', false, attrs);
            }
        });

    };

    var openSourceModal = function() {
        
        var dom  = editor.dom,
            code = editor.getContent();
    
        Chalk.modal(Chalk.sourceUrl, {data: {lang: 'html', code: code}, method: 'post'}, function(res) {
            if (!res) {
                return;
            }
            editor.setContent(res.code);
        });

    };

    var openWidgetModal = function(type, state, params, el) {

        var params    = params || {}, 
            el        = el || null,
            dom       = editor.dom,
            selection = editor.selection,
            html      = selection.getContent()
            text      = selection.getContent({format: 'text'});

        var url = Chalk.widgetUrl
            .replace('%7Bmethod%7D', 'get')
            .replace('%7Btype%7D', type)
            .replace('%7Bstate%7D', state);
        Chalk.modal(url, {data: params, method: 'post'}, function(res) {
            if (!res) {
                return;
            }
            if (res.method && res.method == 'delete') {
                if (el) {
                    el.remove();
                }
                return;
            }
            var widget = res.widget;
            var attrs = {
                'class': 'mceNonEditable',
                'data-chalk': JSON.stringify({
                    widget: {
                        name: widget.name,
                        params: widget.params
                    }
                })
            };
            if (!el) {
                el = editor.dom.create('div', attrs, widget.html);
                editor.execCommand('mceInsertContent', false, el.outerHTML);
            } else {
                for (var name in attrs) {
                    el.attr(name, attrs[name]);
                }
                el.html(widget.html);
            }
        });

    };

    var openWidgetSourceModal = function(code, el) {
    
        Chalk.modal(Chalk.sourceUrl, {data: {lang: 'json', code: code}, method: 'post'}, function(res) {
            if (!res) {
                return;
            }
            var attrs = {
                'data-chalk': res.code
            };
            for (var name in attrs) {
                el.attr(name, attrs[name]);
            }
        });

    };

    var menu = [ 
        {
            text: 'Horizontal Rule',
            onclick: function() { tinyMCE.activeEditor.buttons.hr.onclick(); }
        }, {
            text: 'Special Character',
            onclick: function() { tinyMCE.activeEditor.buttons.charmap.onclick(); }
        }
    ];

    if (Chalk.widgets) {

        var groups = {}, group, widget, item;
        for (i in Chalk.widgets) {
            widget = Chalk.widgets[i];
            group  = widget.group;
            item = {
                text: widget.singular || undefined,
                onclick: function(widget) { openWidgetModal(widget.name, 'new'); }.bind(this, widget)
            };
            if (group && groups[group]) {
                menu[groups[group]].menu.push(item);
            } else if (group) {
                menu.push({text: group, menu: [item]});
                groups[group] = menu.length - 1;
            } else {
                menu.push(item);
            }
        }
        menu.push(menu.splice(0, 1)[0]);
        menu.push(menu.splice(0, 1)[0]);

    }

    editor.addButton('chalkinsert', {
        type: 'menubutton',
        text: 'Insert',
        icon: false,
        menu: menu
    });

    editor.addButton('chalklink', {
        type: 'button',
        tooltip: 'Add link',
        icon: 'link',
        onclick: openLinkModal
    });

    editor.addButton('chalksource', {
        type: 'button',
        tooltip: 'Edit source',
        icon: 'code',
        onclick: openSourceModal
    });

    editor.on('click', function(ev) {
        ev.stopPropagation();
        ev.preventDefault();
        var el = $(ev.target).closest('div[data-chalk]');
        if (el.length) {
            editor.selection.collapse();
            var code = el.attr('data-chalk');
            if (ev.shiftKey) {
                openWidgetSourceModal(code, el);
            } else {
                var widget = JSON.parse(code).widget;
                openWidgetModal(widget.name, 'saved', widget.params, el);
            }
        }
    });

});

/* Configure */

var assetsUrl   = Chalk.rootBaseUrl + 'vendor/jacksleight/chalk/public/assets';
tinyMCE.baseURL = assetsUrl + '/tinymce';

var css = '';
var classList = {
    table: [
        {title: 'None', value: ''},
    ],
    tableRow: [
        {title: 'None', value: ''},
    ],
    tableCell: [
        {title: 'None', value: ''},
    ],
};
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
if (Chalk.classes) {
    for (var group in Chalk.classes) {
        items = Chalk.classes[group];
        for (var i = 0; i < items.length; i++) {
            classList[group].push(items[i]);
        }
    }
}
if (Chalk.styles) {
    var groups = {}, group, style;
    for (var i = 0; i < styles.length; i++) {
        group = styles[i];
        groups[group.title] = i;
    }
    for (var i = 0; i < Chalk.styles.length; i++) {
        style = Chalk.styles[i];
        group = style.group;
        style = {
            title:    style.label    || undefined,
            selector: style.selector || undefined,
            block:    style.block    || undefined,
            inline:   style.inline   || undefined,
            classes:  style.classes  || undefined
        };
        if (group && groups.hasOwnProperty(group)) {
            styles[groups[group]].items.push(style);
        } else if (group) {
            log(group);
            log(groups[group]);
            styles.push({title: group, items: [style]});
            groups[group] = styles.length - 1;
        } else {
            styles.push(style);
        }
    }
    styles.push(styles.splice(2, 1)[0]);
    styles.push(styles.splice(2, 1)[0]);
    var css = [], selector, block, inline, classes;
    for (var i = 0; i < Chalk.styles.length; i++) {
        style    = Chalk.styles[i];
        selector = style.selector || '',
        block    = style.block    || '',
        inline   = style.inline   || '',
        classes  = style.classes.split(' ')
        for (var j = 0; j < classes.length; j++) {
            css.push(selector + ' ' + (block || inline) + '.' + classes[j] + ' { ' + style.css + ' }');
        }

    }
    css = css.join(' ');
}

tinyMCE.init({
    skin_url: assetsUrl + '/vendor/tinymce/skins/lightgray',
    content_css: [
        assetsUrl + '/styles/editor-content.css',
        'data:text/css;charset=utf-8;base64,' + Base64.encode(css)
    ],
    menubar: false,
    convert_urls: false,
    branding: false,
    plugins:[
        'fullscreen',
        'noneditable',
        'code',
        'paste',
        'table',
        'charmap',
        'link',
        'image',
        'hr',
        'autoresize',
        'visualblocks',
        'searchreplace',
        'lists',
        'chalk'].join(' '),
    toolbar: [
        'styleselect', 'bold', 'italic', 'removeformat', '|',
        'bullist', 'numlist', 'table', '|',
        'chalkinsert', 'chalklink', 'unlink', '|',
        'pastetext', '|',
        'fullscreen', 'chalksource'].join(' '),
    statusbar: false,
    browser_spellcheck: true,
    element_format: 'html',
    autoresize_max_height: 1000,
    preview_styles: false,
    table_grid: false,
    table_advtab: false,
    table_row_advtab: false,
    table_cell_advtab: false,
    table_appearance_options: false,
    table_class_list: classList.table,
    table_row_class_list: classList.tableRow,
    table_cell_class_list: classList.tableCell,
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
            if (editor.getElement().disabled) {
                $(editor.getContainer()).addClass('disabled');
                editor.getBody().setAttribute('contenteditable', false);
            }
        });
   }
});