Chalk.component('.confirmable', function(i, el) {
	
	var message = $(el).attr('data-message') || 'Are you sure?';
    if ($(el).is('select')) {   
        $(el).change(function(ev) {
            if (!confirm(message)) {
                $(el).val(null);
                ev.stopImmediatePropagation();
                ev.stopPropagation();
                ev.preventDefault();
            }          
        });
    } else {    
    	$(el).click(function(ev) {
    		if (!confirm(message)) {
                ev.stopImmediatePropagation();
    			ev.stopPropagation();
    			ev.preventDefault();
    		}	
    	});
    }

	
}); 