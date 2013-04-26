define(
    [
        '/pages/page.js'
    ],
    function(page) {
        return Backbone.View.extend({
            page: page,
            constructor: function(opt) {
                var block = this;

                block.set(_.extend({}, block.defaults, opt));
                block.set('initialized', true);

                page.addBlocks([block]);

                Backbone.View.apply(this, arguments);
            },
            initialize: function() {
                var block = this;

                block.render();
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

                if (_.isFunction(block['set ' + path])) {
                    setValue = block['set ' + path](value, extra);
                }

                if (extra.canceled) {
                    extra.canceled = false;
                    return;
                }

                if (setValue !== undefined) {
                    value = setValue;
                }

                if (_.isObject(value) && !_.isElement(value)) {
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
    });