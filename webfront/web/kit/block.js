define(function(require) {
    //requirements
    var deepExtend = require('kit/utils/deepExtend'),
        classExtend = require('kit/utils/classExtend');

    require('jquery.require');

    var Block = Backbone.View.extend({
        templates: {},

        className: null,
        __name__: null,
        addClass: null,
        tagName: 'div',

        events: null,
        listeners: null,

        _configure: function(options) {
            var block = this;

            block.defaults = _.clone(block);
            block.cid = _.uniqueId('block');
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
            var block = this;

            if (!$.trim(block.$el.html())) {
                block.render();
            }
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
        'set': function(path, value, extra) {
            var block = this,
                keyPath = this,
                setValue;

            extra = deepExtend({
                canceled: false,
                cancel: function() {
                    this.canceled = true;
                }
            }, extra);

            if (_.isObject(path)) {
                _.each(path, function(value, key) {
                    block.set(key, value, extra);
                });
                return;
            }

            if (_.isFunction(block['set:' + path])) {
                setValue = block['set:' + path](value, extra);
            }

            if (extra.canceled) {
                extra.canceled = false;
                return;
            }

            if (setValue !== undefined) {
                value = setValue;
            }

            if (_.isObject(value) && !_.isElement(value) && !_.isArray(value)) {
                _.each(value, function(value, pathPart) {
                    block.set(path + '.' + pathPart, value, extra);
                });
            } else {
                _.each(path.split('.'), function(pathPart, index, path) {
                    if (typeof keyPath[pathPart] == 'undefined') {
                        keyPath[pathPart] = {};
                    }

                    if (index == (path.length - 1)) {
                        keyPath[pathPart] = value;
                    } else {
                        keyPath = keyPath[pathPart];
                    }
                });
            }

            block.trigger('set:' + path, value);
        },
        'set:loading': function(loading) {
            var block = this;
            block.$el.toggleClass('preloader_spinner', loading);
        }
    });

    Block.extend = classExtend;

    return Block;
});