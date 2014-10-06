(function() {

	Chalk.components = [];
	Chalk.component = function(selector, func) {
		Chalk.components.push([selector, func]);
	};
	Chalk.initialize = function(el) {
		el = $(el);
		$(Chalk.components).each(function(i, component) {
			if (component[0] !== null) {
				el.find(component[0]).each(component[1]);
			} else {
				component[1](0, el);
			}
		});
	};

})();