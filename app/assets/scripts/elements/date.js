Chalk.component('.picker-datetime', function(i, el) {

    $(el).datepicker({
        dateFormat: 'yy-mm-dd 00:00',
        prevText: '',
        nextText: '',
    });
    
});
Chalk.component('.picker-date', function(i, el) {

    $(el).datepicker({
        dateFormat: 'yy-mm-dd',
        prevText: '',
        nextText: '',
    });
    
});