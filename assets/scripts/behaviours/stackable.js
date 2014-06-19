Ayre.component('.stackable', function(i, el) {
	
	var list		= $(el).find('.stackable-list');
	var button		= $(el).find('.stackable-button');
	var template	= $(el).find('.stackable-template').html();
	var i			= list.children().length;
	var add = function() {
		var content = $($.parseHTML(Mustache.render(template, {i: i++}).trim())[0]);
		list.append(content);
		setTimeout(function() {
			Ayre.initialize(content);
		}, 1);		
	}
	if (i == 0) {
		add();
	}
	button.click(function(ev) {
		add();
	});

});