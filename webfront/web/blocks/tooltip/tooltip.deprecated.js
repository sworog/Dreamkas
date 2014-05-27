define(function(require) {
        //requirements
        var Block = require('kit/core/block.deprecated'),
            deepExtend = require('kit/deepExtend/deepExtend');

        require('lodash');

        return Block.extend({
            __name__: 'tooltip',
            className: 'tooltip',
            el: function(){
                return document.body.appendChild(document.createElement('div'));
            },
            $trigger: null,
            template: require('tpl!./tooltip.html'),
            templates: {
                index: require('tpl!./tooltip.html'),
                content: require('tpl!./content.html')
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