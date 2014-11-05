Chalk.component('.structure', function(i, el) {
    
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
            $(el).find('.structure-save')
                .prop('disabled', false);
            $(el).find('.structure-cancel')
                .prop('disabled', true);
            $(el).find('.structure-data')
                .val(JSON.stringify(this.serialize()));
            nodes[data.destId] = 1;
            Chalk.set({
                nodes: nodes
            });
        }
    })
    $(el).find('.structure-edit').click(function() {
        $(el).find('.structure-edit')
            .prop('disabled', true);
        $(el).find('.structure-cancel')
            .prop('disabled', false);
        tree.addClass('tree-move');
    });
    $(el).find('.structure-cancel').click(function() {
        $(el).find('.structure-edit')
            .prop('disabled', false);
        $(el).find('.structure-cancel')
            .prop('disabled', true);
        tree.removeClass('tree-move');
    });
    var nodes = Chalk.prefs.nodes || {};
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
        Chalk.set({
            nodes: nodes
        });
    });
    
});