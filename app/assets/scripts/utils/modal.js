(function() {

	var template = $('.modal-template').html();
	Mustache.parse(template);

	Chalk.modal = function(url, options, cb) {
		
		cb = cb || function() {};
		var modal	= $($.parseHTML(Mustache.render(template).trim())[0]);
		var inner	= $(modal).find('.modal-inner');
		var content	= $(modal).find('.modal-content');
		var button	= $(modal).find('.modal-button');
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
			button.addClass('hideable-hidden');
			loader.removeClass('hideable-hidden');
			xhr = $.ajax(url, options)
				.done(function(res, status, xhr) {
					var data = {};
					var html = null; 
					var json = xhr.getResponseHeader('X-JSON');
					if (json) {
						data = $.extend(data, JSON.parse(json));
					}
					if (typeof res == 'object') {
						data = $.extend(data, res);
					} else {
						html = res;
					}
					if (data.notifications) {
						var notification;
						for (var i = 0; i < data.notifications.length; i++) {
							notification = data.notifications[i];
							Chalk.notify(notification[0], notification[1]);
						}
					}
					if (!html) {
						if (data.redirect) {
							window.location.href = data.redirect;
							return;
						} else {
							close(data);
							return;
						}
					}
					button.removeClass('hideable-hidden');
					loader.addClass('hideable-hidden');
					inner.removeClass('hideable-hidden');
					update(html);
				});
		};
		var update = function(html) {
			content.html(html);
			content.find('*[autofocus]:lt(1)').focus();
			setTimeout(function() {
				Chalk.initialize(content);
			}, 1);			
			
			var size = content.find('> :first-child').attr('data-modal-size');
			inner.removeAttr('style');
			if (size) {
				size = size.split('x');
				inner.css({
					maxWidth: size[0] + 'px'
				});
				if (size[1]) {
					inner.css({
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
		modal.click(function(ev) {
			var target = $(ev.target);
			if (target.is('a')) {
				if (target.is('a[target]')) {
					return;
				}
				ev.preventDefault();
				request(target.attr('href'));
			} else if (target.hasClass('modal-close')) {
				ev.preventDefault();
				close();
			} else {
				if (target.attr('formmethod')) {
					target.closest('form').attr('method', target.attr('formmethod'));
				}
				if (target.attr('formaction')) {
					target.closest('form').attr('action', target.attr('formaction'));
				}
			}
		});	
		modal.submit(function(ev) {
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