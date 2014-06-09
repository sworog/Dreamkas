define(function(require) {
    //requirements
    var deepExtend = require('kit/deepExtend/deepExtend'),
        extendClass = require('kit/extendClass/extendClass'),
        get = require('kit/get/get'),
        set = require('kit/set/set'),
        getText = require('kit/getText');

    require('lodash');
    require('backbone');
    require('jquery');

    var Block = Backbone.View
        .extend({
            __name__: null,
            template: null,
            dictionary: {},

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

                if (!$.trim(this.$el.html())){
                    this.render();
                }
            },
            get: function(){
                var args = [this].concat([].slice.call(arguments));
                return get.apply(null, args);
            },
            set: function(){
                var args = [this].concat([].slice.call(arguments));
                return set.apply(null, args);
            },
            delegateEvents: function() {
                var block = this;

                Backbone.View.prototype.delegateEvents.apply(block, arguments);

                block.startListening();
            },
            initialize: function() {
            },
            translate: function(text){
                return getText(this.get('dictionary'), text);
            },
            render: function() {
                var block = this;

                //block.removeBlocks();

                if (typeof block.template === 'function'){
                    block.el.innerHTML = block.template(block);
                }

                block.requireBlocks();

                block.findElements();

                return block;
            },
            requireBlocks: function() {

                var block = this,
                    $elements = block.$('[require]');

                $elements.each(function() {
                    var el = this,
                        url = $(el).attr('require');

                    require([url], function(Module) {
                        new Module({
                            el: el
                        });

                        $(el).removeAttr('require');
                    });
                });
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
                        block.listenTo(block, property, listener);
                    } else if (block.get(property)) {
                        block.listenTo(block.get(property), listener);
                    }
                });
            },
            destroy: function(){
                var block = this;

                block.stopListening();
            },
            'set:loading': function(loading) {
                var block = this;
                block.$el.toggleClass('preloader_spinner', loading);
            }
        });

    Block.extend = extendClass;

    return Block;
});