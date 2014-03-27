define(function(require, exports, module) {
    //requirements
    var Block = require('block'),
        deepExtend = require('kit/deepExtend/deepExtend');

    require('jquery');

    return Block.extend({
        el: null,
        trigger: null,
        container: '.page__data',
        template: require('tpl!./template.html'),
        events: {
            'click .tooltip__closeLink': function(e) {
                e.preventDefault();

                var block = this;

                block.hide();
            }
        },
        initialize: function(){
            var block = this;

            block.container = document.querySelector(block.container);

            block.container.appendChild(block.el);
        },
        show: function(opt) {
            var block = this,
                $trigger = $(block.trigger);

            $(document).on('click.' + block.cid, function(e) {
                if ($trigger && e.target != $trigger[0] && !$(e.target).closest(block.el).length) {
                    block.hide();
                }
            });

            $(document).on('keyup.' + block.cid, function(e) {
                if (e.keyCode === 27) {
                    block.hide();
                }
            });

            deepExtend(block, opt);

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