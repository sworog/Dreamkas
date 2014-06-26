define(function(require, exports, module) {
    //requirements
    var BaseClass = require('kit/baseClass/baseClass'),
        getText = require('kit/getText'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        delegateEvent = require('kit/delegateEvent/delegateEvent'),
        undelegateEvents = require('kit/undelegateEvents/undelegateEvents'),
        stringToFragment = require('kit/stringToFragment/stringToFragment'),
        _ = require('lodash');

    // Cached regex to split keys for `delegate`.
    var delegateEventSplitter = /^(\S+)\s*(.*)$/;

    return BaseClass.extend({
        constructor: function(params) {
            var block = this;

            deepExtend(this, params);

            block._initElement();
            block.initialize.apply(block, arguments);
            block._initBlocks();
            block._startListening();
        },

        el: null,

        elements: {},
        blocks: {},

        template: function() {
            return '<div></div>';
        },

        initialize: function() {
        },

        getText: function() {
            var block = this,
                args = [].slice.call(arguments, 0);

            return getText.apply(null, [block.nls].concat(args));
        },

        _templateToElement: function() {
            var block = this,
                fragment = stringToFragment(block.template(block)),
                wrapper = document.createElement('div');

            wrapper.appendChild(fragment);

            return wrapper.children[0];
        },

        render: function() {
            var block = this,
                newEl = block._templateToElement();

            block._destroyBlocks();

            block.el.innerHTML = newEl.innerHTML;

            _.forEach(newEl.attributes, function(attribute) {
                block.el.setAttribute(attribute.name, attribute.value);
            });

            block._initElements();
            block._initBlocks();
        },

        _initBlocks: function() {
            var block = this;

            block.__blocks = block.__blocks || block.blocks;

            block.blocks = _.transform(block.get('__blocks'), function(result, blockInitializer, key) {
                result[key] = block.get('__blocks.' + key);
            });
        },

        _initElement: function(el) {
            var block = this;

            block._undelegateEvents();

            block.__el = block.__el || block.el;

            el = el || block.get('__el');

            if (typeof el === 'string') {
                el = document.querySelector(el);
            }

            block.el = el || block._templateToElement();

            block._initElements();
            block._delegateEvents();

            return block;
        },

        _initElements: function() {
            var block = this,
                elements = {};

            block.__elements = block.__elements || block.elements;

            _.forEach(block.__elements, function(value, key) {
                var el = block.get('__elements.' + key);
                elements[key] = typeof el === 'string' ? block.el.querySelector(el) : el;
            });

            block.elements = elements;
        },

        // Set callbacks, where `this.events` is a hash of
        //
        // *{"event selector": "callback"}*
        //
        //     {
        //       'mousedown .title':  'edit',
        //       'click .button':     'save',
        //       'click .open':       function(e) { ... }
        //     }
        //
        // pairs. Callbacks will be bound to the view, with `this` set properly.
        // Uses event delegation for efficiency.
        // Omitting the selector binds the event to `this.el`.
        // This only works for delegate-able events: not `focus`, `blur`, and
        // not `change`, `submit`, and `reset` in Internet Explorer.
        _delegateEvents: function(events) {
            var block = this;

            if (!(events || (events = block.get('events')))) {
                return block;
            }

            for (var key in events) {
                var method = events[key];

                var match = key.match(delegateEventSplitter);
                var eventName = match[1], selector = match[2];

                delegateEvent(block, eventName, selector, method);
            }

            return block;
        },

        _undelegateEvents: function() {
            var block = this;

            undelegateEvents(block);

            return block;
        },

        _startListening: function(listeners) {
            var block = this;

            if (!(listeners || (listeners = block.get('listeners')))) {
                return block;
            }

            _.each(listeners, function(listener, property) {
                if (typeof listener === 'function') {
                    block.listenTo(block, property, listener);
                } else if (block.get(property)) {
                    block.listenTo(block.get(property), listener);
                }
            });

            return block;
        },

        destroy: function() {
            var block = this;

            block._destroyBlocks();
            block.stopListening();
            block.off();
            block._undelegateEvents();
        },

        remove: function() {
            var block = this;

            block.destroy();

            var parentNode = block.el.parentNode;

            if (parentNode) {
                parentNode.removeChild(block.el);
            }

            return block;
        },

        _destroyBlocks: function() {
            var block = this;

            _.forEach(block.blocks, function(blockToDestroy, blockName) {

                if (blockToDestroy && typeof blockToDestroy.destroy === 'function') {
                    blockToDestroy.destroy();
                    delete block.blocks[blockName];
                }

            });
        },
        _removeBlocks: function() {
            var block = this;

            _.forEach(block.blocks, function(blockToDestroy, blockName) {

                var parentNode = blockToDestroy.el.parentNode;

                if (parentNode) {
                    parentNode.removeChild(blockToDestroy.el);
                }

                if (blockToDestroy && typeof blockToDestroy.destroy === 'function') {
                    blockToDestroy.destroy();
                    delete block.blocks[blockName];
                }

            });
        }
    });
});