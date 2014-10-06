tinymce.PluginManager.add('chalk', function(editor, url) {

    var openLinkModal = function() {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            html            = selection.getContent()
            text            = selection.getContent({format: 'text'})
            richSelection   = /</.test(html) && (!/^<a [^>]+>[^<]+<\/a>$/.test(html) || html.indexOf('href=') == -1);
    
        Chalk.modal(Chalk.baseUrl + 'content/core_content/select', {}, function(res) {
            if (!res) {
                return;
            }
            var content = res.contents[0];
            var attrs = {
                href: Chalk.rootBaseUrl + '_c' + content.id,
                'data-chalk': JSON.stringify({
                    attrs: {
                        href: ['url', content.id]
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

    var openWidgetModal = function(entity, params, el) {

        var params    = params || {}, 
            el        = el || null,
            dom       = editor.dom,
            selection = editor.selection,
            html      = selection.getContent()
            text      = selection.getContent({format: 'text'});
    
        Chalk.modal(Chalk.baseUrl + 'widget/edit/' + entity, {data: params}, function(res) {
            if (!res) {
                return;
            }
            if (res.delete) {
                if (el) {
                    el.remove();
                }
                return;
            }
            var attrs = {
                'data-chalk-widget': JSON.stringify({
                    entity: res.entity,
                    params: res.params
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
                onclick: function(entity) { openWidgetModal(entity); }.bind(this, entity.name)
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

    editor.on('click', function(ev) {
        ev.preventDefault();
        var target = $(ev.target);
        var data = target.attr('data-chalk-widget');
        if (data) {
            data = JSON.parse(data);
            openWidgetModal(data.entity, data.params, target);
        }
    });

});