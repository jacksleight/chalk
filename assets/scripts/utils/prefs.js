Ayre.set = function(prefs) {
	$.ajax(Ayre.baseUrl + 'prefs', {data: prefs});
};