$('.tree').nestable({
	maxDepth		: 100,
	rootClass		: 'tree',
	listClass		: 'tree-list',
	itemClass		: 'tree-item',
	dragClass		: 'tree-drag',
	handleClass		: 'tree-handle',
	collapsedClass	: 'tree-collapsed',
	placeClass		: 'tree-placeholder',
	emptyClass		: 'tree-empty',
	expandBtnHTML	: '<button data-action="expand"><span>Expand</span></button>',
	collapseBtnHTML	: '<button data-action="collapse"><span>Collapse</span></button>',
});