define(
    [
        '/ui/views/page.js'
    ],
    function(page) {
        return Backbone.Block = Backbone.View.extend({
            constructor: function(opt) {
                var block = this;

                block.set(_.extend({}, block.defaults, opt));
                block.set('initialized', true);

                page.addBlocks([block]);

                Backbone.View.apply(this, arguments);
            },
            render: function(){
                var block = this;

                block.$el.html(block.templates.main(block));
            },
            remove: function() {
                var block = this;
                page.removeBlocks({cid: block.cid});
            },
            'get': function(key, params) {
                var block = this,
                    value;

                if (typeof this['get ' + key] == 'function') {
                    value = block['get ' + key](params);
                }

                if (typeof value == 'undefined') {
                    value = block;
                    _.each(key.split('.'), function(keyPart) {
                        value = value[keyPart];
                        if (typeof value == 'undefined') {
                            return false;
                        }
                    });
                }

                return value;
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