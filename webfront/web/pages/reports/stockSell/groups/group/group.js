define(function(require, exports, module) {
    //requirements
	var Page_stockSell = require('blocks/page/stockSell/stockSell');

    return Page_stockSell.extend({
        content: require('ejs!./content.ejs'),
        collections: {
			stockSell: function() {
                var GroupStockSellCollection = require('resources/groupStockSell/collection');

                return new GroupStockSellCollection([], {
                    groupId: this.params.groupId,
                    filters: {
                        store: this.params.storeId,
                        dateFrom: this.params.dateFrom,
                        dateTo: this.params.dateTo
                    }
                });
            }
        },
        models: {
            group: function() {
                var GroupModel = require('resources/group/model');

                return new GroupModel({
                    id: this.params.groupId
                });
            }
        },
        blocks: {
            table_groupStockSell: require('blocks/table/groupStockSell/groupStockSell')
        }
    });
});