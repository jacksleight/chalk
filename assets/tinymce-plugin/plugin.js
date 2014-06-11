tinymce.PluginManager.add('ayre', function(editor, url) {

    var openLinkModal = function() {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            html            = selection.getContent()
            text            = selection.getContent({format: 'text'})
            richSelection   = /</.test(html) && (!/^<a [^>]+>[^<]+<\/a>$/.test(html) || html.indexOf('href=') == -1);
    
        Ayre.modal(Ayre.baseUrl + 'content/select', {}, function(res) {
            if (!res) {
                return;
            }
            var content = res.contents[0];
            var attrs = {
                href: Ayre.rootBaseUrl + '_c' + content.id,
                'data-ayre': JSON.stringify({attrs: {href: ['url', content.id]}})
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

    var openWidgetModal = function(name) {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            html            = selection.getContent()
            text            = selection.getContent({format: 'text'});
    
        Ayre.modal(Ayre.baseUrl + 'content/widget', {data: {widget: name}}, function(res) {
            if (!res) {
                return;
            }
            var attrs = {
                'class': 'ayre-widget',
                'data-ayre': JSON.stringify({html: ['render', res.widget.name]})
            };
            var el = editor.dom.create('div', attrs, res.widget.label);
            editor.execCommand('mceInsertContent', false, el.outerHTML);
        });

    };

    var menu = [ 
        {
            text: 'Internal Link',
            onclick: openLinkModal
        }, 
        {
            text: 'External Link',
            onclick: function() { tinyMCE.activeEditor.buttons.link.onclick(); }
        },           
        {
            text: 'Special Character',
            onclick: function() { tinyMCE.activeEditor.buttons.charmap.onclick(); }
        }
    ];

    if (Ayre.widgets) {

        var groups = {}, group, widget;
        for (var i = 0; i < Ayre.widgets.length; i++) {
            widget = Ayre.widgets[i];
            name   = widget.name;
            group  = widget.group;
            widget = {
                text: widget.label || undefined,
                onclick: function() { openWidgetModal(name); }
            };
            if (group && groups[group]) {
                menu[groups[group]].menu.push(widget);
            } else if (group) {
                menu.push({text: group, menu: [widget]});
                groups[group] = menu.length - 1;
            } else {
                menu.push(widget);
            }
        }
        menu.push(menu.splice(2, 1)[0]);
    }

    editor.addButton('ayreinsert', {
        type: 'menubutton',
        text: 'Insert',
        icon: false,
        menu: menu
    });

});