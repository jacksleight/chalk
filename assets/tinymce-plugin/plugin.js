tinymce.PluginManager.add('ayre', function(editor, url) {

    this.showModal = function() {
        
        var dom             = editor.dom,
            selection       = editor.selection,
            html            = selection.getContent()
            text            = selection.getContent({format: 'text'})
            richSelection   = /</.test(html) && (!/^<a [^>]+>[^<]+<\/a>$/.test(html) || html.indexOf('href=') == -1);
    
        Ayre.modal(Ayre.baseUrl + 'content/select', function(data) {
            if (!data) {
                return;
            }
            var content = data.contents[0];
            var attrs = {
                href: '.',
                'data-ayre': JSON.stringify({attrs: {href: ['url_content', content.id]}})
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

    editor.addButton('ayrelink', {
        tooltip: 'Insert content link',
        icon: 'link',
        classes: 'widget btn btn-ayre',
        onclick: this.showModal
    });

});