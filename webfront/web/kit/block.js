define(
    [
        '/pages/page.js'
    ],
    function(page) {
        return Backbone.View.extend({
            page: page,
            constructor: function(opt) {
                var block = this;

                _.extend(block, block.defaults, opt);

                block.set('initialized', true);

                page.addBlocks([block]);

                Backbone.View.apply(this, arguments);
            },
            render: function() {
                var block = this;

                block.$el
                    .html(block.tpl.main({
                        block: block
                    }))
                    .initBlocks();
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

                if (block.events && _.isFunction(block.events['set:' + path])) {
                    setValue = block.events['set:' + path].call(block, value, extra);
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
                        var oldValue = keyPath[pathPart];

                        if (typeof keyPath[pathPart] == 'undefined') {
                            keyPath[pathPart] = {};
                        }

                        if (index == (path.length - 1)) {
                            keyPath[pathPart] = value;
                        } else {
                            keyPath = keyPath[pathPart];
                        }

                        if (keyPath[pathPart] !== oldValue){
                            block.trigger('update:' + path, value);
                        }

                    });
                }

                block.trigger('set:' + path, value);
            }
        });
    });