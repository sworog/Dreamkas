define(function(require, exports, module) {
    //requirements
    var BaseClass = require('utils/baseClass'),
        getText = require('utils/getText'),
        delegateEvent = require('utils/delegateEvent'),
        undelegateEvents = require('utils/undelegateEvents'),
        stringToElement = require('utils/stringToElement');

    require('lodash');

    // Cached regex to split keys for `delegate`.
    var delegateEventSplitter = /^(\S+)\s*(.*)$/;

    return BaseClass.extend({

        constructor: function() {
            var block = this;

            BaseClass.apply(block, arguments);

            block._initElement();
            block.initialize.apply(block, arguments);
            block.initBlocks();
            block.startListening();
        },

        cid: module.id,
        elements: {},
        blocks: [],

        el: function() {
            var block = this;

            //TODO: multiple elements

            return document.getElementsByClassName(block.get('className'))[0];
        },

        className: function() {
            var block = this;

            return block.get('cid').split('/').pop();
        },

        template: function(obj) {
            var block = this;

            return '<div id="' + block.get('id') + '" class="' + block.get('className') + '"></div>'
        },

        initialize: function() {
        },

        getText: function() {
            var args = [this.dictionary].concat([].slice.call(arguments));
            return getText.apply(null, args);
        },

        templateToElement: function(){
            var block = this;

            return stringToElement(block.template(block));
        },

        render: function() {
            var block = this,
                newEl = block.templateToElement();

            block.set('status', 'rendering');

            block.removeBlocks();

            block.el.innerHTML = newEl.innerHTML;

            _.forEach(newEl.attributes, function(attribute) {
                block.el.setAttribute(attribute.name, attribute.value);
            });

            block.initElements();
            block.initBlocks();

            block.set('status', 'rendered');
        },

        initBlocks: function() {
            var block = this;

            if (!block.__blocks) {
                block.__blocks = block.blocks;
            }

            block.blocks = _.transform(block.get('__blocks'), function(result, blockInitializer, key) {
                result[key] = block.get('__blocks.' + key);
            });
        },

        _initElement: function(el) {
            var block = this;

            block.undelegateEvents();

            block.__el = block.__el || block.el;

            el = el || block.get('__el') || block.templateToElement();

            block.el = typeof el === 'string' ? document.querySelector(el) : el;

            block.initElements();
            block.delegateEvents();

            return block;
        },

        initElements: function() {
            //TODO: className with multiple classes

            var block = this,
                className = block.get('className'),
                elementsClassName = className ? className.split('_')[0] + '__' : null,
                elements = {},
                selector = '[class*="' + elementsClassName + '"]',
                allElements;

            block.__elements = block.__elements ||  block.elements;

            if (elementsClassName) {

                allElements = block.el.querySelectorAll(selector);

                elements = _.groupBy(allElements, function(el) {
                    var elementClassName = _.find(el.classList, function(className) {
                        return className.indexOf(elementsClassName) === 0;
                    });

                    return elementClassName.split('__')[1];
                });
            }

            _.forEach(block.__elements, function(value, key){
                elements[key] = block.get('__elements.' + key);
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
        delegateEvents: function(events) {
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

        undelegateEvents: function() {
            var block = this;

            undelegateEvents(block);

            return block;
        },

        startListening: function(listeners) {
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

        remove: function() {
            var block = this;

            var parentNode = block.el.parentNode;

            block.removeBlocks();

            if (parentNode) {
                parentNode.removeChild(block.el);
            }

            block.stopListening();
            block.undelegateEvents();

            return block;
        },

        removeBlocks: function() {
            var block = this;

            _.forEach(block.blocks, function(blockToRemove, blockName) {

                if (blockToRemove && typeof blockToRemove.remove === 'function') {
                    blockToRemove.remove();
                    delete block.blocks[blockName];
                }

            });
        }
    });
});