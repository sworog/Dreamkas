define(function(require, exports, module) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        __name__: module.id,
        template: require('tpl!./table_grossSalesByStores.html'),
        sortBy: null,
        ascending: true,
        collections: {
            grossSalesByStores: null
        },
        events: {
            'click [data-sort-by]': function(e){
                var block = this,
                    $target = $(e.currentTarget),
                    modelKey = $target.data('sort-by');

                block.sortBy = modelKey;
                block.ascending = !block.ascending;

                block.collections.grossSalesByStores.comparator = function(model){
                    return block.ascending ? model.get(modelKey) : -model.get(modelKey);
                };

                block.collections.grossSalesByStores.sort();

                block.render();

                block.$('[data-sort-by="' + modelKey + '"]')
                    .addClass('table__sortedHeader')
                    .addClass('table__sortedHeader_ascending_' + block.ascending);
            }
        }
    });
});