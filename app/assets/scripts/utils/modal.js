(function() {

	var template = $('.modal-template').html();
	Mustache.parse(template);

	Chalk.modal = function(url, options, cb) {
		
		cb = cb || function() {};
		var modal	= $($.parseHTML(Mustache.render(template).trim())[0]);
		var content	= $(modal).find('.modal-content');
		var loader	= $(modal).find('.modal-loader');
		$(document.body).append(modal);
		setTimeout(function() {
			modal.removeClass('hideable-hidden');
		}, 1);

		var xhr;
		var request = function(url, options) {
			if (xhr) {
				xhr.abort();
				xhr = null;
			}
			loader.removeClass('hideable-hidden');
			xhr = $.ajax(url, options)
				.done(function(res) {
					if (typeof res == 'object') {
						if (res.redirect) {
							window.location.href = res.redirect;
							return;
						} else {
							close(res);
							return;
						}
					}
					loader.addClass('hideable-hidden');
					content.removeClass('hideable-hidden');
					update(res);
				});
		};
		var update = function(html) {
			content.html(html);
			setTimeout(function() {
				Chalk.initialize(content);
			}, 1);			
			
			var size = content.find('> :first-child').attr('data-modal-size');
			content.removeAttr('style');
			if (size) {
				size = size.split('x');
				content.css({
					maxWidth: size[0] + 'px'
				});
				if (size[1]) {
					content.css({
						maxHeight: size[1] + 'px'
					});
				}
			}
		};
		var close = function(data) {
			modal.addClass('hideable-hidden');
			setTimeout(function() {
				modal.remove();
			}, 100);
			cb(data || false);
		};

		request(url, options);
		content.click(function(ev) {
			var target = $(ev.target);
			if (target.is('a')) {
				ev.preventDefault();
				request(target.attr('href'));
			} else if (target.attr('formmethod')) {
				target.closest('form').attr('method', target.attr('formmethod'));
			} else if (target.hasClass('modal-close')) {
				ev.preventDefault();
				close();
			}
		});	
		content.submit(function(ev) {
			var target = $(ev.target);
			if (target.is('form')) {
				ev.preventDefault();
				request(target.attr('action'), {
					method: target.attr('method'),
					data: target.serialize()
				});
			}
		});	

	};

	$('[rel=modal]').click(function(ev) {
		ev.preventDefault();
		Chalk.modal($(ev.target).attr('href'));
	});

})();