define(function() {

    var pageBlocks = [],
        $page;

    return {
        open: function(pageTpl, data) {
            var page = this;

            $page = $page || $('#page');

            $page.addClass('preloader preloader_spinner');
            page.clear();
            require(['tpl!' + pageTpl], function(pageTpl) {
                $page
                    .html(pageTpl(data))
                    .initBlocks()
                    .removeClass('preloader preloader_spinner');
            })
        },
        clear: function() {
            $page.empty();

            _.each(pageBlocks, function(block) {
                block.stopListening();
            });

            pageBlocks = [];
        },
        addBlocks: function(blocks) {
            pageBlocks = pageBlocks.concat(blocks);
        },
        removeBlocks: function(selector) {
            var page = this,
                blocks = page.findBlocks(selector);

            _.each(blocks, function(block) {
                block.stopListening();
                block.$el.remove();
            });

            pageBlocks = _.difference(pageBlocks, blocks);
        },
        findBlocks: function(selector) {
            if (typeof selector === 'string') {
                selector = {
                    $el: $(selector)
                }
            }

            return selector ? _.where(pageBlocks, selector) : pageBlocks;
        }
    }
});