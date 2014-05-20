$('.stackable').each(function(i, el) {
	var list		= $(el).find('.stackable-list');
	var template	= $(el).find('.stackable-template').html();
	var i			= list.children().length;
	var add = function() {
		var html = $(temp = $.parseHTML(Mustache.render(template, {i: i++}).trim())[0]);
		list.append(html);
	}
	add();
});