define(function(require, exports, module) {
    //requirements
    var StockMovementProducts = require('blocks/form/stockMovementProducts/stockMovementProducts');

    return StockMovementProducts.extend({
        model: require('resources/stockInProduct/model'),
		collection: require('resources/stockInProduct/collection'),
		priceField: 'price',
        globalEvents: {
            'submit:success': function(data, block){

                var modal = block.$el.closest('.modal')[0];

                if (modal && modal.id === 'modal_productForInvoice' + this.cid) {
                    this.selectProduct(data);
                }

            }
        },
        blocks: {
            modal_product: require('blocks/modal/product/product'),
            autocomplete_products: function(){

                var block = this,
                    Autocomplete_products = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
                    autocomplete_products = new Autocomplete_products;

                autocomplete_products.on('select', function(productData) {
                    block.selectProduct(productData);
                });

                autocomplete_products.on('deselect', function() {
                    block.deselectProduct();
                });

                return autocomplete_products;
            }
        }
    });
});