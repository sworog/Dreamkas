define(
    [
        '/kit/block.js'
    ],
    function(Block) {
        var Page = Block.extend({
            el: document.body,
            className: 'page',
            blockName: 'page',

            open: function(pageTpl, data) {
                var page = this;

                page.clear();
                page.$content.addClass('preloader preloader_spinner');

                require(['tpl!' + pageTpl], function(pageTpl) {
                    page.$content
                        .html(pageTpl(data))
                        .removeClass('preloader preloader_spinner')
                        .require();
                })
            },
            clear: function() {
                var page = this;

                page.$el.children('[block]').each(function() {
                    $(this).data('block').remove();
                });

                page.$content.empty();
            }
        });

        return new Page();
    });