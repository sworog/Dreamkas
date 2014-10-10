define(function(require, exports, module) {
    //requirements
	var Page_profit = require('blocks/page/profit/profit');

    return Page_profit.extend({
        content: require('ejs!./content.ejs'),
		ProfitCollection: require('resources/storesProfit/collection'),
		params: {
			dateFrom: function(){
				var page = this,
					currentTime = Date.now();

				return page.formatDate(moment(currentTime).subtract(1, 'month'));
			}
		},
		blocks: {
			table_storesProfit: require('blocks/table/storesProfit/storesProfit')
		}
    });
});