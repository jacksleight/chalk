(function() {

	Ayre.components = [];
	Ayre.component = function(selector, func) {
		Ayre.components.push([selector, func]);
	};
	Ayre.initialize = function(el) {
		el = $(el);
		log(Ayre.components);
		$(Ayre.components).each(function(i, component) {
			if (component[0] !== null) {
				el.find(component[0]).each(component[1]);
			} else {
				component[1](0, el);
			}
		});
	};

})();