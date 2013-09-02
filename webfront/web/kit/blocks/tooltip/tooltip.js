define(function(require) {
        //requirements
        var Block = require('kit/core/block'),
            deepExtend = require('kit/utils/deepExtend');

        return Block.extend({
            __name__: 'tooltip',
            className: 'tooltip',
            $trigger: null,
            templates: {
                index: require('tpl!./templates/tooltip.html'),
                content: require('tpl!./templates/content.html')
            },
            initialize: function() {
                var block = this;

                block.$el.appendTo('body');

                block.render();
            },
            events: {
                'click .tooltip__closeLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    block.hide();
                }
            },
            show: function(opt) {
                var block = this;

                $(document).on('click.' + block.cid, function(e) {
                    if (block.$trigger && e.target != block.$trigger[0] && !$(e.target).closest(block.el).length) {
                        block.hide();
                    }
                });

                $(document).on('keyup.' + block.cid, function(e) {
                    if (e.keyCode === 27) {
                        block.hide();
                    }
                });

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

                $(document)
                    .off('click.' + block.cid)
                    .off('keyup.' + block.cid);

                block.$el.hide();
            }
        });
    }
);