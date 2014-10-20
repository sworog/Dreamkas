define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
		productId: 0,
		id: 'modal_product',
		template: require('ejs!./template.ejs'),
		model: function() {
			var block = this,
				ProductModel = require('resources/product/model'),
				groupProducts = PAGE.get('collections.groupProducts'),
				productModel;

			productModel = groupProducts && groupProducts.get(this.productId);

			return productModel || new ProductModel;
		},
        blocks: {
            form_product: function(){
                var block = this,
                    Form_product = require('blocks/form/product/product');

                return new Form_product({
                    model: block.model
                });
            }
        }
    });
});