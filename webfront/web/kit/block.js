define(
    [
        '/pages/page.js'
    ],
    function(page) {
        return Backbone.View.extend({
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
                var block = this,
                    $el = $(block.tpl.main({
                        block: block
                    }));

                block.$el
                    .html($el)
                    .initBlocks();

                block._afterRender();
            },
            _afterRender: function() {

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
    });