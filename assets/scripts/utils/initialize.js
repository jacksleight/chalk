(function() {

	Ayre.components = [];
	Ayre.component = function(selector, func) {
		Ayre.components.push([selector, func]);
	};
	Ayre.initialize = function(el) {
		el = $(el);
		$(Ayre.components).each(function(i, component) {
			el.find(component[0]).each(component[1]);
		});
	};

})();