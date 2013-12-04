define(function(require, exports, module) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        __name__: module.id,
        template: require('tpl!./table_grossSalesByStores.html'),
        sortBy: null,
        collections: {
            grossSalesByStores: null
        },
        events: {
            'click [data-sort-by]': function(e){
                var block = this,
                    $target = $(e.currentTarget),
                    modelKey = $target.data('sort-by');

                block.collections.grossSalesByStores.comparator = block.sortBy = modelKey;
                block.collections.grossSalesByStores.sort();
            }
        },
        listeners: {
            'collections.grossSalesByStores': {
                sort: function(){
                    console.log(1);
                }
            }
        }
    });
});