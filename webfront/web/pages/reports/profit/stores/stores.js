define(function(require, exports, module) {
    //requirements
	var Page_productsProfit = require('blocks/page/productsProfit/productsProfit');

    return Page_productsProfit.extend({
        content: require('ejs!./content.ejs'),
		ProfitCollection: require('resources/storesProfit/collection'),
		params: {
			dateFrom: function(){
				var page = this,
					currentTime = Date.now();

				return page.formatDate(moment(currentTime).subtract(1, 'month'));
			}
		}
    });
});