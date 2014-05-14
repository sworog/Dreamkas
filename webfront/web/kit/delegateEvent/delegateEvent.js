define(function(require, exports, module) {
    //requirements
    var matchesSelector = require('../matchesSelector/matchesSelector');

    return function(view, eventName, selector, callback) {

        view._handlers = view._handlers || [];

        if (typeof selector === 'function') {
            callback = selector;
            selector = null;
        }

        if (typeof callback !== 'function') {
            throw new TypeError('View#delegate expects callback function');
        }

        var root = view.el;

        var bound = callback.bind(view);

        var handler = selector ? function(event) {
            for (var element = event.target; element && element !== root; element = element.parentNode) {
                if (matchesSelector(element, selector)) {
                    // event.currentTarget or event.target are read-only.
                    event.delegateTarget = element;
                    return bound(event);
                }
            }
        } : bound;

        root.addEventListener(eventName, handler, false);

        view._handlers.push({
            eventName: eventName,
            selector: selector,
            callback: callback,
            handler: handler
        });

        return handler;
    };
});