define(function(require, exports, module) {
    //requirements
    var StockMovementProducts = require('blocks/form/stockMovementProducts/stockMovementProducts');

    return StockMovementProducts.extend({
		template: require('ejs!./template.ejs'),
        model: require('resources/invoiceProduct/model'),
		collection: require('resources/invoiceProduct/collection'),
		priceField: 'priceEntered',
		globalEvents: {
			'submit:success': function(data, block){

				if (block.el.id === 'form_product'){
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

				return autocomplete_products;
			}
		}
    });
});