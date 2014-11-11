define(function(require, exports, module) {
    //requirements
    var StockMovementProducts = require('blocks/form/stockMovementProducts/stockMovementProducts');

    return StockMovementProducts.extend({
		model: require('resources/supplierReturnProduct/model'),
		collection: require('resources/supplierReturnProduct/collection'),
		priceField: 'price'
    });
});