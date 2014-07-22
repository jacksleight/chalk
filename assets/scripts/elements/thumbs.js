Ayre.component('.thumbs', function(i, el) {
	
	var className = 'thumbs-' + new Date().valueOf();
	$(el).addClass(className);

	var style = document.createElement('style');
	style.setAttribute('media', 'screen')
	style.appendChild(document.createTextNode(''));
	document.head.appendChild(style);

	var min = $(el).children(':first-child').outerWidth();
	var index = -1;
	var refresh = function() {
		var total = $(el).innerWidth(),
			count = Math.floor(total / min),
			width  = 100 / count;
		if (index != -1) {
			style.sheet.deleteRule(index);
			index = -1;
		}
		index = style.sheet.insertRule('.' + className + ' > * { width: ' + width + '% !important; }', 0);
	};
	refresh();
	$(window).resize(refresh);

});