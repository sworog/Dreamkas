define(function(require, exports, module) {
    //requirements
    var Ractive = require('ractive'),
        delegateEvent = require('kit/delegateEvent/delegateEvent'),
        
        getText = require('kit/getText'),
        get = require('kit/get/get'),
        _ = require('lodash');

    // Cached regex to split keys for `delegate`.
    var delegateEventSplitter = /^(\S+)\s*(.*)$/;

    return Ractive.extend({
        elements: {},
        events: {},
        nls: {},
        listeners: {},
        observers: {},
        data: {
            getText: function() {
                return this.getText.apply(this, arguments);
            },
            formatDate: require('kit/formatDate/formatDate'),
            formatMoney: require('kit/formatMoney/formatMoney'),
            moment: require('moment')
        },

        init: function() {
            var block = this;

            if (block.component){
                block.el = block.fragment.items[0].node;
            }

            block._initElements();
            block._delegateEvents();
            block._startObserving();
            block._startListening();
        },

        getText: function() {
            var block = this,
                args = [].slice.call(arguments, 0);

            return getText.apply(null, [block.nls].concat(args));
        },

        destroy: function() {
            var block = this;

            block._undelegateEvents();

            return block.teardown();
        },

        _initElements: function() {
            var block = this,
                elements = {};

            block.__elements = block.__elements || block.elements;

            _.forEach(block.__elements, function(value, key) {
                var el = get(block, '__elements.' + key);
                elements[key] = typeof el === 'string' ? block.el.querySelector(el) : el;
            });

            block.elements = elements;

            return block;
        },

        // Set callbacks, where `this.events` is a hash of
        //
        // *{"event selector": callback(){}}*
        //
        //     {
        //       'click .open':       function(e) { ... }
        //     }
        //
        // pairs. Callbacks will be bound to the view, with `this` set properly.
        // Uses event delegation for efficiency.
        // Omitting the selector binds the event to `this.el`.
        // This only works for delegate-able events: not `focus`, `blur`, and
        // not `change`, `submit`, and `reset` in Internet Explorer.
        _delegateEvents: function(events) {
            var block = this,
                key;

            events = events || block.events;

            for (key in events) {
                var method = events[key];

                var match = key.match(delegateEventSplitter);
                var eventName = match[1], selector = match[2];

                delegateEvent(block, eventName, selector, method);
            }

            return block;
        },

        _undelegateEvents: function() {
            var block = this;

            var handlers = block._handlers;

            if (!handlers) {
                return;
            }

            var removeListener = function(item) {
                view.el.removeEventListener(item.eventName, item.handler, false);
            };

            // Remove all handlers.
            handlers.forEach(removeListener);
            block._handlers = [];

            return block;
        },

        _startListening: function(listeners) {
            var block = this;

            block.on(listeners || block.listeners);

            return block;
        },

        _startObserving: function(observers) {
            var block = this;

            block.observe(observers || block.observers);

            return block;
        }
    });
});