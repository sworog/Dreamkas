define(
    [
        '/kit/block.js'
    ],
    function(Block) {
        var Page = Block.extend({
            className: 'page',

            open: function(pageTpl, data) {
                var page = this;

                page.clear();
                page.$el.addClass('preloader preloader_spinner');

                require(['tpl!' + pageTpl], function(pageTpl) {
                    page.$el
                        .html(pageTpl(data))
                        .removeClass('preloader preloader_spinner')
                        .require();
                })
            },
            clear: function() {
                var page = this;

                page.$el.find(['block']).each(function() {
                    $(this).data('block').remove();
                });

                page.$el.empty();
            }
        });

        return new Page({
            el: document.getElementById('page')
        });
    });