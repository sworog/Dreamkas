define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore'),
        deepExtend = require('kit/utils/deepExtend'),
        classExtend = require('kit/utils/classExtend'),
        setter = require('kit/utils/setter'),
        getter = require('kit/utils/getter');

    require('jquery.require');

    var Block = Backbone.View
        .extend(setter)
        .extend(getter)
        .extend({
            __name__: null,
            templates: {},

            className: null,
            addClass: null,
            tagName: 'div',

            events: null,
            listeners: null,
            page: null,

            constructor: function() {
                this.cid = _.uniqueId('cid');
                this._configure.apply(this, arguments);
                this._ensureElement();
                this.initialize.apply(this, arguments);
                this.delegateEvents();
            },
            _configure: function(options) {
                var block = this;

                block.defaults = _.clone(block);
                deepExtend(block, options);
            },
            _ensureElement: function() {
                var block = this;

                Backbone.View.prototype._ensureElement.apply(block, arguments);

                block.findElements();

                block.$el
                    .addClass(block.className)
                    .addClass(block.__name__)
                    .addClass(block.addClass)
                    .attr('block', block.__name__)
                    .data(block.__name__, block);
            },
            delegateEvents: function() {
                var block = this;

                Backbone.View.prototype.delegateEvents.apply(block, arguments);

                block.startListening();
            },

            initialize: function() {
            },
            render: function() {
                var block = this,
                    template;

                if (!block.templates.index) {
                    return block;
                }

                if (block.loading) {
                    return block;
                }

                template = block.templates.index(block);

                //block.removeBlocks();

                block.$el.html(template);
                block.$el.require();

                block.findElements();

                return block;
            },
            findElements: function() {
                var block = this,
                    $context = arguments[0] || block.$el,
                    elements = [];

                if (block.__name__) {
                    $context.find('[class*="' + block.__name__ + '__"]').each(function() {
                        var classes = _.filter($(this).attr('class').split(' '), function(className) {
                            return className.indexOf(block.__name__ + '__') === 0;
                        });

                        elements = _.union(elements, classes);
                    });

                    _.each(elements, function(className) {
                        var elementName = className.split('__')[1];

                        block['$' + elementName] = block.$el.find('.' + className);
                    });
                }
            },
            startListening: function() {
                var block = this;

                _.each(block.listeners, function(listener, property) {
                    if (typeof listener === 'function') {
                        block.listenTo(block, listener);
                    } else if (block[property]) {
                        block.listenTo(block[property], listener);
                    }
                });
            },
            'set:loading': function(loading) {
                var block = this;
                block.$el.toggleClass('preloader_spinner', loading);
            }
        });

    Block.extend = classExtend;

    return Block;
});