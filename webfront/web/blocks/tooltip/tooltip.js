define(function(require) {
    //requirements
    var Block = require('kit/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        $ = require('jquery');

    return Block.extend({
        trigger: null,
        container: '.content',
        template: require('ejs!./template.ejs'),
        events: {
            'click .tooltip__closeLink': function(e) {
                e.preventDefault();

                var block = this;

                block.hide();
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.cid = _.uniqueId('tooltip');

            block.container = document.querySelector(block.container);

            block.container.appendChild(block.el);
        },
        show: function(opt) {
            var block = this;

            $(document).on('click.' + block.cid, function(e) {
                if (block.trigger && e.target !== block.trigger && !$(e.target).closest(block.el).length) {
                    block.hide();
                }
            });

            $(document).on('keyup.' + block.cid, function(e) {
                if (e.keyCode === 27) {
                    block.hide();
                }
            });

            deepExtend(block, opt);

            block._removeBlocks();
            block.render();
            block.align();

            $(block.el).show();
        },
        align: function(){
            var block = this,
                $trigger = $(block.trigger),
                $container = $(block.container);

            $(block.el)
                .css({
                    top: $trigger.offset().top + $container.scrollTop() + $trigger.height(),
                    left: $trigger.offset().left - $container.offset().left
                })
        },
        hide: function() {
            var block = this;

            $(document)
                .off('click.' + block.cid)
                .off('keyup.' + block.cid);

            $(block.el).hide();
        }
    });
});