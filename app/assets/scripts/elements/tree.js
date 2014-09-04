Ayre.component('.structure', function(i, el) {
    
    var tree = $(el).find('.tree');
    tree.nestable({
        maxDepth        : 100,
        rootClass       : 'tree',
        listClass       : 'tree-list',
        itemClass       : 'tree-node',
        dragClass       : 'tree-drag',
        handleClass     : 'tree-handle',
        collapsedClass  : 'tree-collapsed',
        placeClass      : 'tree-placeholder',
        emptyClass      : 'tree-empty',
        expandBtnHTML   : '<button type="button" data-action="expand"><span>Expand</span></button>',
        collapseBtnHTML : '<button type="button" data-action="collapse"><span>Collapse</span></button>',
        dropCallback: function(data) {
            $(el).find('.structure-submit').prop('disabled', false);
            $(el).find('.structure-data').val(JSON.stringify(this.serialize()));
            nodes[data.destId] = 1;
            Ayre.set({
                nodes: nodes
            });
        }
    })
    var nodes = Ayre.prefs.nodes || {};
    tree.find('li').each(function() {
        var id = $(this).attr('data-id');
        if (!nodes[id] || nodes[id] == 0) {
            var button = $(this).find('> [data-action=collapse]');
            if (button.length) {
                button[0].click();
            }
        }
    });
    tree.click(function(ev) {
        var target = $(ev.target).is('span')
            ? $(ev.target).parent()
            : $(ev.target);
        if (!target.is('button')) {
            return;
        }
        var id     = target.closest('li').attr('data-id');
        var expand = target.attr('data-action') == 'expand';
        nodes[id]  = expand ? 1 : 0;
        Ayre.set({
            nodes: nodes
        });
    });
    
});