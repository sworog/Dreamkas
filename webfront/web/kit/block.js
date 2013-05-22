define(
    [
        '/kit/page.js'
    ],
    function(page) {
        return Backbone.View.extend({
            constructor: function(opt) {
                var block = this;

                _.extend(block, block.defaults, opt);

                block.cid = _.uniqueId('block');
                block._ensureElement();
                block._findElements();
                block.delegateEvents();
                block.initialize.apply(block, arguments);

                page.addBlocks([block]);
            },
            render: function() {
                var block = this,
                    $tpl = $(block.tpl.main({
                        block: block
                    }));

                block.$el
                    .html($tpl)
                    .initBlocks();

                block._findElements();
                block._afterRender();
            },
            _findElements: function($context) {
                var block = this,
                    $context = $context || block.$el,
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