define(function(require, exports, module) {
    //requirements
    var Block = require('kit/core/block.deprecated');

    return Block.extend({
        __name__: module.id,
        template: require('ejs!./table_grossSalesByStores.html'),
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

                block.ascending = (modelKey === block.sortBy) ? !block.ascending : false;
                block.sortBy = modelKey;

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