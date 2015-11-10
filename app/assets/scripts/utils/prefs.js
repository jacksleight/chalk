(function() {

	Chalk.set = function(prefs) {
		$.ajax(Chalk.prefsUrl, {data: prefs});
	};

})();