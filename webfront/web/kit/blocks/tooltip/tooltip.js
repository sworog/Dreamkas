define(function(require) {
        //requirements
        var Block = require('kit/block'),
            _ = require('underscore'),
            deepExtend = require('kit/utils/deepExtend');

        return Block.extend({
            $trigger: null,
            className: 'tooltip',
            blockName: 'tooltip',
            templates: {
                index: require('tpl!./templates/tooltip.html'),
                content: require('tpl!./templates/content.html')
            },

            initialize: function() {
                var block = this;

                block.$el.appendTo('body');

                block.render();

                $(document)
                    .on({
                        click: function(e) {
                            if (block.$trigger && e.target != block.$trigger[0]) {
                                block.hide();
                            }
                        },
                        keyup: function(e){
                            if (e.keyCode === 27){
                                block.hide();
                            }
                        }
                    });
            },
            events: {
                'click': function(e) {
                    e.stopPropagation();
                },
                'click .tooltip__closeLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.hide();
                }
            },
            show: function(opt) {
                var block = this;

                deepExtend(block, opt);

                block.align();
                block.$el.show();
            },
            align: function(){
                var block = this;

                block.$el
                    .css({
                        top: block.$trigger.offset().top + block.$trigger.height(),
                        left: block.$trigger.offset().left
                    })
            },
            hide: function() {
                var block = this;

                block.$el.hide();
            }
        });
    }
);