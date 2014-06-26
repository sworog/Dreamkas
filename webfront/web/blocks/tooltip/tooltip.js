define(function(require) {
    //requirements
    var Block = require('kit/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        $ = require('jquery');

    return Block.extend({
        target: null,
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
                if (block.target && e.target !== block.target && !$.contains(block.el, e.target)) {
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
                $target = $(block.target),
                $container = $(block.container);

            $(block.el)
                .css({
                    top: $target.offset().top + $container.scrollTop() + $target.height(),
                    left: $target.offset().left - $container.offset().left
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