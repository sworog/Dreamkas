define(
    [
        '/kit/page.js',
        './_utils/deepExtend.js',
        './_utils/inherit.js'
    ],
    function(page, deepExtend, inherit) {
        var Block = Backbone.View.extend({
            templates: {},
            className: null,
            tagName: 'div',

            constructor: function(props) {
                var block = this;

                deepExtend(block, props);

                block.cid = _.uniqueId('block');
                block._ensureElement();
                block.findElements();
                block.delegateEvents();
                block.initialize.apply(block, arguments);
                block.$el.attr('lighthouse', block.className);
                block.$el.data('lighthouse', block);

            },
            initialize: function(){
                var block = this;

                if (block.templates.index){
                    block.render();
                }
            },
            render: function() {
                var block = this,
                    $template = $(block.templates.index({
                        block: block
                    }));

                block.$el
                    .html($template)
                    .initBlocks();

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
                page.removeBlocks({cid: block.cid});
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
    });