(function() {

	var template = $('.modal-template').html();
	Mustache.parse(template);

	Ayre.modal = function(url, cb) {
		
		cb = cb || function() {};
		var modal	= $($.parseHTML(Mustache.render(template).trim())[0]);
		var content	= $(modal).find('.modal-content');
		var loader	= $(modal).find('.modal-loader');
		$(document.body).append(modal);
		setTimeout(function() {
			modal.removeClass('hideable-hidden');
		}, 1);

		var xhr;
		var request = function(url, type) {
			if (xhr) {
				xhr.abort();
				xhr = null;
			}
			loader.removeClass('hideable-hidden');		
			xhr = $.ajax(url, {type: type || 'GET'})
				.done(function(data) {
					if (typeof data == 'object') {
						close(data);
						return;
					}
					loader.addClass('hideable-hidden');
					update(data);
				});
		};
		var update = function(data) {
			content.html(data);
		};
		var close = function(data) {
			modal.addClass('hideable-hidden');
			setTimeout(function() {
				modal.remove();
			}, 100);
			cb(data || false);
		};

		request(url);
		content.click(function(ev) {
			if ($(ev.target).is('a')) {
				ev.preventDefault();
				request($(ev.target).attr('href'));
			} else if ($(ev.target).hasClass('modal-close')) {
				ev.preventDefault();
				close();
			}
		});	

	};

	$('[rel=modal]').click(function(ev) {
		ev.preventDefault();
		Ayre.modal($(ev.target).attr('href'));
	});

})();