Chalk.component('.dropdown', function(i, el) {

    var toggle = $(el).find('.dropdown-toggle');
    toggle.on('click', function (ev) {
        $(el).toggleClass('is-active');
    });

    $(document.body).on('click', function (ev) {
        if (!$(ev.target).is(toggle) && !$.contains(toggle[0], ev.target)) {
            $(el).toggleClass('is-active', false);
        }
    });

});