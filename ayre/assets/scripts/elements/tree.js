$('.structure').each(function(i, el) {
	$(el).find('.tree').nestable({
		maxDepth		: 100,
		rootClass		: 'tree',
		listClass		: 'tree-list',
		itemClass		: 'tree-item',
		dragClass		: 'tree-drag',
		handleClass		: 'tree-handle',
		collapsedClass	: 'tree-collapsed',
		placeClass		: 'tree-placeholder',
		emptyClass		: 'tree-empty',
		expandBtnHTML	: '<button type="button" data-action="expand"><span>Expand</span></button>',
		collapseBtnHTML	: '<button type="button" data-action="collapse"><span>Collapse</span></button>',
		dropCallback: function(data) {
			$(el).find('.structure-submit').prop('disabled', false);
			$(el).find('.structure-data').val(JSON.stringify(this.serialize()));
		}
	})
});