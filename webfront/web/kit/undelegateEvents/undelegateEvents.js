define(function(require, exports, module) {
    //requirements

    return function(view, eventName, selector, callback) {

        if (typeof selector === 'function') {
            callback = selector;
            selector = null;
        }

        var handlers = view._handlers;

        if (!handlers){
            return;
        }

        var removeListener = function(item) {
            view.el.removeEventListener(item.eventName, item.handler, false);
        };

        // Remove all handlers.
        if (!eventName && !selector && !callback) {
            handlers.forEach(removeListener);
            view._handlers = [];
        } else {
            // Remove some handlers.
            handlers
                .filter(function(item) {
                    return item.eventName === eventName &&
                        (callback ? item.callback === callback : true) &&
                        (selector ? item.selector === selector : true);
                })
                .forEach(function(item) {
                    removeListener(item);
                    handlers.splice(handlers.indexOf(item), 1);
                });
        }
    };
});