(function() {

	setInterval(function() {
        $.ajax(Chalk.pingUrl);
    }, 1000 * 60);

})();