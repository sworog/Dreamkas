define(function(require, exports, module) {
    //requirements
	var StockMovementProducts = require('blocks/form/stockMovementProducts/stockMovementProducts');

    return StockMovementProducts.extend({
		template: require('ejs!./template.ejs'),
		model: require('resources/writeOffProduct/model'),
		collection: require('resources/writeOffProduct/collection'),
		priceField: 'price',
		ProductList: require('./writeOff__productList')
    });
});