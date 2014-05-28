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
		var request = function(url, type, data) {
			if (xhr) {
				xhr.abort();
				xhr = null;
			}
			loader.removeClass('hideable-hidden');		
			xhr = $.ajax(url, {
					type: type || 'GET',
					data: data || {}
				})
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
			Ayre.initialize(content);
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
			var target = $(ev.target);
			if (target.is('a')) {
				ev.preventDefault();
				request(target.attr('href'));
			} else if (target.hasClass('modal-close')) {
				ev.preventDefault();
				close();
			}
		});	
		content.submit(function(ev) {
			var target = $(ev.target);
			if (target.is('form')) {
				ev.preventDefault();
				request(target.attr('action'), target.attr('mode'), target.serialize());
			}
		});	

	};

	$('[rel=modal]').click(function(ev) {
		ev.preventDefault();
		Ayre.modal($(ev.target).attr('href'));
	});

})();