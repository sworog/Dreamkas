define(function(require, exports, module) {
    //requirements
	var Page_stockSell = require('blocks/page/stockSell/stockSell');

    return Page_stockSell.extend({
        content: require('ejs!./content.ejs'),
		StockSellCollection: require('resources/groupStockSell/collection'),
        models: {
            group: function() {
                var GroupModel = require('resources/group/model');

                return new GroupModel({
                    id: this.params.groupId
                });
            }
        },
        blocks: {
			breadcrumbs: require('./group__breadcrumbs'),
            table_groupStockSell: require('blocks/table/groupStockSell/groupStockSell')
        }
    });
});