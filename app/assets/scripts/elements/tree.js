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
            .addClass('disabled');
        $(el).find('.structure-add')
            .addClass('disabled', true);
        $(el).find('.structure-cancel')
            .removeClass('disabled');
        $(el).find('.structure-save')
            .removeClass('disabled');
        tree.addClass('tree-move');
    });
    $(el).find('.structure-cancel').click(function() {
        $(el).find('.structure-edit')
            .removeClass('disabled');
        $(el).find('.structure-add')
            .removeClass('disabled', true);
        $(el).find('.structure-cancel')
            .addClass('disabled');
        $(el).find('.structure-save')
            .addClass('disabled');
        tree.removeClass('tree-move');
    });
    var nodes = Chalk.prefs.nodes || {};
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