define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'reports',
        collections: {
            stores: require('collections/stores/stores'),
            groupStockSell: function(){
                var GroupStockSellCollection = require('collections/groupStockSell/groupStockSell');

                return new GroupStockSellCollection([], {
                    groupId: this.params.groupId
                });
            }
        },
        blocks: {
            select_store: require('blocks/select/store/store'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            table_groupStockSell: require('blocks/table/groupStockSell/groupStockSell')
        }
    });
});