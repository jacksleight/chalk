(function() {

	Chalk.set = function(prefs) {
		$.ajax(Chalk.baseUrl + 'prefs', {data: prefs});
	};

})();