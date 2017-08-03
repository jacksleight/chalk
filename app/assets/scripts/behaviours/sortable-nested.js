Chalk.component('.sortable-nested', function(i, el) {
    
    $(el).nestable({
        maxDepth        : 100,
        rootClass       : 'sortable-nested',
        listClass       : 'sortable-nested-list',
        itemClass       : 'sortable-nested-item',
        dragClass       : 'sortable-nested-drag',
        handleClass     : 'sortable-nested-handle',
        placeClass      : 'sortable-nested-placeholder',
        collapsedClass  : 'sortable-nested-collapsed',
        expandBtnHTML   : '',
        collapseBtnHTML : '',
        dropCallback: function(data) {
            var data = {};
            $('.sortable-nested').each(function() {
                data[$(this).attr('data-id')] = $(this).nestable('serialize');
            });
            $('.sortable-nested-data').text(JSON.stringify(data));
        }
    })

});