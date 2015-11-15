Chalk.component('.notifications', function(i, el) {
    
    var stack    = [];
    var visible  = false;
    var interval = 100;
    var maximum  = 4000;

    var add = function(text, type) {
        var notification = $('<li class="notification">' + text + '</li>');
        if (type) {
            notification.addClass('notification-' + type + ' icon-' + type);
        }
        stack.push(notification);
        $(el).append(notification);
    }

    var show = function(notification) {
        visible = true;
        notification.addClass('notification-show');
        var dismiss = function() {
            visible = false;
            setTimeout(function() {
                notification.removeClass('notification-show');
            }, 200);
        };
        setTimeout(dismiss, maximum);
        notification.mouseover(dismiss);
    }

    setInterval(function() {
        if (visible || !stack.length) {
            return;
        }
        var notification = stack.shift();
        show(notification);
    }, interval);

    for (var i = Chalk.notifications.length - 1; i >= 0; i--) {
        var notification = Chalk.notifications[i];
        add(notification[0], notification[1]);
    }

    Chalk.notify = add;

});