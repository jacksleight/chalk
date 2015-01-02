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

/* Plugin */

tinymce.PluginManager.add('chalk', function(editor, url) {

    var openLinkModal = function() {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            code            = selection.getContent()
            text            = selection.getContent({format: 'text'})
            richSelection   = /</.test(code) && (!/^<a [^>]+>[^<]+<\/a>$/.test(code) || code.indexOf('href=') == -1);
    
        Chalk.modal(Chalk.browseUrl.replace('{entity}', Chalk.contentName), {}, function(res) {
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

    var openWidgetModal = function(entity, mode, params, el) {

        var params    = params || {}, 
            el        = el || null,
            dom       = editor.dom,
            selection = editor.selection,
            html      = selection.getContent()
            text      = selection.getContent({format: 'text'});
    
        Chalk.modal(Chalk.widgetUrl.replace('{entity}', entity) + '?mode=' + mode, {data: params, method: 'post'}, function(res) {
            if (!res) {
                return;
            }
            if (res.mode && res.mode == 'delete') {
                if (el) {
                    el.remove();
                }
                return;
            }
            var attrs = {
                'data-chalk': JSON.stringify({
                    widget: {
                        name: res.entity,
                        params: res.params
                    }
                })
            };
            if (!el) {
                el = editor.dom.create('div', attrs);
                editor.execCommand('mceInsertContent', false, el.outerHTML + '<p></p>');
            } else {
                for (var name in attrs) {
                    el.attr(name, attrs[name]);
                }
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
            text: 'Internal Link',
            onclick: openLinkModal
        }, {
            text: 'External Link',
            onclick: function() { tinyMCE.activeEditor.buttons.link.onclick(); }
        }, {
            text: 'Horizontal Rule',
            onclick: function() { tinyMCE.activeEditor.buttons.hr.onclick(); }
        }, {
            text: 'Special Character',
            onclick: function() { tinyMCE.activeEditor.buttons.charmap.onclick(); }
        }
    ];

    if (Chalk.widgets) {

        var groups = {}, group, entity;
        for (var i = 0; i < Chalk.widgets.length; i++) {
            entity = Chalk.widgets[i];
            group  = entity.group;
            item = {
                text: entity.singular || undefined,
                onclick: function(entity) { openWidgetModal(entity, 'add'); }.bind(this, entity.name)
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
        menu.push(menu.splice(2, 1)[0]);
        menu.push(menu.splice(2, 1)[0]);

    }

    editor.addButton('chalkinsert', {
        type: 'menubutton',
        text: 'Insert',
        icon: false,
        menu: menu
    });

    editor.addButton('chalksource', {
        type: 'button',
        tooltip: 'Source',
        icon: 'code',
        onclick: openSourceModal
    });

    editor.on('click', function(ev) {
        ev.preventDefault();
        var target = $(ev.target);
        var code = target.attr('data-chalk');
        if (code) {
            if (ev.shiftKey) {
                openWidgetSourceModal(code, target);
            } else {
                data = JSON.parse(code).widget;
                openWidgetModal(data.name, 'edit', data.params, target);
            }
        }
    });

});

/* Configure */

var assetsUrl   = Chalk.rootBaseUrl + 'vendor/jacksleight/chalk/public/assets';
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
    for (var i = 0; i < Chalk.styles.length; i++) {
        style    = Chalk.styles[i];
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
    skin_url: assetsUrl + '/vendor/tinymce/skins/lightgray',
    content_css: [
        assetsUrl + '/styles/editor.css',
        'data:text/css;charset=utf-8;base64,' + Base64.encode(css)
    ],
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
        'hr',
        'visualblocks',
        'searchreplace',
        'lists',
        'chalk'].join(' '),
    toolbar: [
        'styleselect', 'bold', 'italic', '|',
        'bullist', 'numlist', 'table', '|',
        'chalkinsert', 'unlink', '|',
        'pastetext', '|',
        'chalksource'].join(' '),
    statusbar: false,
    browser_spellcheck: true,
    element_format: 'html',
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
            editor.theme.resizeTo(null, $(editor.getElement()).outerHeight() - 37);
        });
   }
});