Chalk.component('.input-tag', function(i, el) {

    $(el).selectize({
        // plugins: ['restore_on_backspace'],
        delimiter: ', ',
        options: JSON.parse($(el).attr('data-values')),
        create: function(input) {
            return {
                value: input,
                text:  input
            }
        }
    });
    
});