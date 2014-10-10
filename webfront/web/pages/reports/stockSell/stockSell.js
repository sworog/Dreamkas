define(function(require, exports, module) {
    //requirements
    var Page_stockSell = require('blocks/page/stockSell/stockSell');

    return Page_stockSell.extend({
        content: require('ejs!./content.ejs'),
		StockSellCollection: require('resources/stockSell/collection'),
        blocks: {
            table_stockSell: require('blocks/table/stockSell/stockSell')
        }
    });
});