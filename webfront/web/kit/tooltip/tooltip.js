define(
    [
        '/kit/block.js',
        './tpl/tpl.js'
    ],
    function(Block, tpl) {
        return Block.extend({
            tpl: tpl,
            initialize: function() {
                var block = this;

                block.render();

                $(document).on('click', function(e) {
                    if (block.$trigger && e.target != block.$trigger[0]) {
                        block.hide();
                    }
                });
            },
            events: {
                'click': function(e) {
                    e.stopPropagation();
                }
            },
            render: function() {
                var block = this;

                block.$el = $(block.tpl.main({
                    block: block
                }));

                block.$el
                    .appendTo('body')
                    .initBlocks();

                block.el = block.$el[0];
            },
            show: function(opt) {
                var block = this;

                opt = _.extend({
                    $trigger: $('#page')
                }, opt);

                block.$trigger = opt.$trigger;

                block.$el
                    .css({
                        top: block.$trigger.offset().top + block.$trigger.height(),
                        left: block.$trigger.offset().left
                    })
                    .show();
            },
            hide: function() {
                var block = this;

                block.$el.hide();
            }
        });
    }
);