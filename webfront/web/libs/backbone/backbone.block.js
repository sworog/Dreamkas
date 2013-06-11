(function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['backbone', 'underscore', 'jquery'], factory);
    } else {
        // Browser globals
        Backbone.Block = factory(Backbone, _, jQuery);
    }
}(this, function(Backbone, _, $) {

    function deepExtend(obj) {
        var slice = Array.prototype.slice,
            hasOwnProperty = Object.prototype.hasOwnProperty;

        _.each(slice.call(arguments, 1), function(source) {
            for (var prop in source) {
                if (hasOwnProperty.call(source, prop)) {
                    if ($.isPlainObject(obj[prop]) && $.isPlainObject(source[prop])) {
                        obj[prop] = deepExtend({}, obj[prop], source[prop]);
                    } else {
                        switch (source[prop]) {
                            case 'false':
                                source[prop] = false;
                                break;
                            case 'true':
                                source[prop] = true;
                                break;
                        }
                        obj[prop] = source[prop];
                    }
                }
            }
        });

        return obj;
    }

    function inherit(protoProps, staticProps) {
        var parent = this;
        var child;

        // The constructor function for the new subclass is either defined by you
        // (the "constructor" property in your `extend` definition), or defaulted
        // by us to simply call the parent's constructor.
        if (protoProps && _.has(protoProps, 'constructor')) {
            child = protoProps.constructor;
        } else {
            child = function() {
                return parent.apply(this, arguments);
            };
        }

        // Add static properties to the constructor function, if supplied.
        deepExtend(child, parent, staticProps);

        // Set the prototype chain to inherit from `parent`, without calling
        // `parent`'s constructor function.
        var Surrogate = function() {
            this.constructor = child;
        };
        Surrogate.prototype = parent.prototype;
        child.prototype = new Surrogate;

        // Add prototype properties (instance properties) to the subclass,
        // if supplied.
        if (protoProps) deepExtend(child.prototype, protoProps);

        // Set a convenience property in case the parent's prototype is needed
        // later.
        child.__super__ = parent.prototype;

        return child;
    }

    var Block = Backbone.View.extend({
        templates: {},
        className: null,
        blockName: null,
        addClass: null,
        tagName: 'div',

        constructor: function(props) {
            var block = this;

            deepExtend(block, props);

            block.cid = _.uniqueId('block');
            block._ensureElement();
            block.findElements();
            block.delegateEvents();
            block.initialize.apply(block, arguments);

            block.$el
                .addClass(block.className)
                .addClass(block.addClass)
                .attr('block', block.blockName || block.className)
                .data('block', block);

        },
        initialize: function() {
            var block = this;

            if (block.templates.index) {
                block.render();
            }
        },
        render: function() {
            var block = this,
                $template = $(block.templates.index({
                    block: block
                }));

            block.$el.children('[block]').each(function() {
                $(this).data('block').remove();
            });

            block.$el
                .html($template)
                .require();

            block.findElements();
            block._afterRender();
        },
        findElements: function() {
            var block = this,
                $context = arguments[0] || block.$el,
                elements = [];

            if (block.className) {
                $context.find('[class*="' + block.className + '__"]').each(function() {
                    var classes = _.filter($(this).attr('class').split(' '), function(className) {
                        return className.indexOf(block.className + '__') === 0;
                    });

                    elements = _.union(elements, classes);
                });

                _.each(elements, function(className) {
                    var elementName = className.split('__')[1];

                    block['$' + elementName] = block.$el.find('.' + className);
                });
            }
        },
        _afterRender: function() {

        },
        remove: function() {
            var block = this;

            block.$el.find(['block']).each(function() {
                $(this).data('block').remove();
            });

            Backbone.View.prototype.remove.call(this);
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
        }
    });

    Block.extend = inherit;

    return Block;

}));