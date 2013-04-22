define(function() {
    
    var pageBlocks = [];
    
    var Page = Backbone.View.extend({
        open: function(pageTpl, data) {
            var page = this;
            page.clear();
            require(['tpl!' + pageTpl], function(pageTpl) {
                page.$el
                    .html(pageTpl(data))
                    .initBlocks();
            })
        },
        clear: function() {
            var page = this;
            page.removeBlocks();
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
            return selector ? _.where(pageBlocks, selector) : pageBlocks;
        }
    });

    return new Page({
        el: '#page'
    })
});