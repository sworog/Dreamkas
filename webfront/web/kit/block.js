define(
    [
        '/pages/page.js',
        '/helpers/deepExtend.js'
    ],
    function(page, deepExtend) {
        var Block = Backbone.View.extend({
            page: page,
            tpl: {},
            constructor: function(opt) {
                var block = this;

                _.extend(block, block.defaults, opt);

                block.set('initialized', true);

                page.addBlocks([block]);

                Backbone.View.apply(this, arguments);
            },
            render: function() {
                var block = this;

                if (block.tpl.main){
                    block.$el
                        .html(block.tpl.main({
                            block: block
                        }))
                        .initBlocks();
                }
            },
            remove: function() {
                var block = this;
                page.removeBlocks({cid: block.cid});
            },
            'set': function(path, value, extra) {
                var block = this,
                    keyPath = this,
                    setValue;

                extra = _.extend({
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

        // Helper function to correctly set up the prototype chain, for subclasses.
        // Similar to `goog.inherits`, but uses a hash of prototype properties and
        // class properties to be extended.

        Block.extend = function(protoProps, staticProps) {
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
        };

        return Block;
    });