define(function(require, exports, module) {
    //requirements
    var StockMovementProducts = require('blocks/form/stockMovementProducts/stockMovementProducts');

    return StockMovementProducts.extend({
        model: require('resources/stockInProduct/model'),
		collection: require('resources/stockInProduct/collection'),
		priceField: 'price'
    });
});